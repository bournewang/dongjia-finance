<?php
namespace App\Helpers;
use App\Models\User;
use App\Models\Challenge;
use App\Wechat;
use DB;

class ChallengeHelper
{
    static public function makeSuccess($challenge)
    {
        debug("\t ".__FUNCTION__ . " challenge {$challenge->id}");
        debug("\t update status to success");
        $challenge->update(['status' => Challenge::SUCCESS, 'success_at' => now()]);

        // update user's level
        debug("\t update user ".$challenge->user_id ." ".$challenge->user->name . " level to ". $challenge->level);
        $challenge->user->update(['level' => $challenge->level]);
    }

    static public function checkSuccess($challenge)
    {
        debug(__FUNCTION__." challenge $challenge->id ");
        $config = config('challenge.levels')[$challenge->level] ?? null;
        if (!$config) {
            throw new \Exception("invalid level: {$challenge->level}");
        }
        // direct recomment register Consumer
        $direct_recommends_count    = $challenge->user->recommends()->where('level', '>', User::NONE_REGISTER)->count();
        $total_consumers_after_me   = User::where('level', '>', User::NONE_REGISTER)->where('created_at', '>', $challenge->created_at)->count();

        debug("\t direct recommends $direct_recommends_count, need reach ".$config['recommend_members']. " to success");
        debug("\t total team members after me: $total_consumers_after_me need reach ".$config['total_team_members']. " to success");

        if ($direct_recommends_count >= $config['recommend_members']
            && $total_consumers_after_me >= $config['total_team_members']) {
                debug("\t challenge success");
                self::makeSuccess($challenge);
        }
    }

    static public function stats()
    {
        return [
            ['label' => __('Register Consumers'),   'value' => User::where('level', User::REGISTER_CONSUMER)->count()],
            ['label' => __('Partner Consumers'),    'value' => 0], //User::where('level', User::PARTNER_CONSUMER)->count()],
            ['label' => __('Applying Challengers'), 'value' => Challenge::where('status', Challenge::APPLYING)->count()],
            ['label' => __('Approved Challengers'), 'value' => Challenge::whereIn('status', [Challenge::SUCCESS, Challenge::CHALLENGING])->count()]
        ];
    }

    static public function notice()
    {
        $query = "
            WITH user_recommendations AS (
                SELECT referer_id, COUNT(*) as recommends_count
                FROM users
                GROUP BY referer_id
            ),
            joined_data AS (
                SELECT c.*, u.name, coalesce(u2.recommends_count, 0) as recommends_count
                FROM challenges c
                INNER JOIN users u on c.user_id = u.id
                LEFT JOIN user_recommendations u2 on u.id = u2.referer_id
            ),
            ranked_users AS (
                SELECT *, ROW_NUMBER() OVER(PARTITION BY level ORDER BY recommends_count DESC, success_at DESC) as `ranking`
                FROM joined_data
            )
            SELECT * FROM ranked_users
            WHERE `ranking` <= 10";

        $result = DB::select($query);
        $data = [];
        foreach ($result as $challenge){
            if (!isset($data[$challenge->level])) $data[$challenge->level] = [];
            $data[$challenge->level][] = __("Ranking", ["rank" => $challenge->ranking]) . $challenge->name;
        }
        $str = [];
        // foreach ($data as $level => $ranks) {
        foreach (User::levelOptions() as $level => $level_label){
            if ($level < User::COMMUNITY_STATION)continue;
            if ($ranks = ($data[$level] ?? null)) {
                $str[] = __("Level Ranking", ["level_label" => $level_label]) .": " . implode(",", $ranks);
            }else{
                $str[] = __("No challengers in level", ["level_label" => $level_label]);
            }
        }
        return implode("; ", $str);
    }

    static public function ranking()
    {
        $paginator = DB::table('challenges')
           ->select('users.name as name', 'users.avatar', 'challenges.status', 'challenges.level', 'challenges.updated_at',
           // 'users.recommends_count as recommends_count'
            DB::raw('(SELECT COUNT(*) FROM users r WHERE r.referer_id = users.id and r.level > 0) as recommends_count')
           )
           ->join('users', 'users.id', '=', 'challenges.user_id')
           ->orderBy('challenges.level', 'desc')
           ->orderByRaw("FIELD(challenges.status , 'success', 'challenging', 'applying', 'canceled', 'rejected')")
           ->orderBy('recommends_count', 'desc')
           ->simplePaginate(20)
           ;
        $data = [];
        foreach ($paginator->getCollection() as $item){
            $data[] = [
                'name' => $item->name,
                'avatar' => $item->avatar,
                'level' => $item->level,
                'level_label' => User::levelOptions()[$item->level],
                'recommends_count' => $item->recommends_count,
                'status' => $item->status,
                'status_label' => Challenge::statusOptions()[$item->status],
                'updated_at' => $item->updated_at
            ];
        }
        return [
            'hasMorePages' => $paginator->hasMorePages(),
            'items' => $data
        ];
    }

    static public function range()
    {
        $str = cache1("challenge-range", function(){
            $res = DB::table('challenges as c')
                ->join("users as u", "u.id", "=", "c.user_id")
                ->selectRaw("u.id, u.nickname, u.mobile, c.level, c.success_at")
                ->where('c.status', '=', Challenge::SUCCESS)
                ->orderByDesc("level")
                ->orderBy("success_at")
                ->limit(10)
                ->get();
                // ->toArray();
            $data = [];
            $i=1;
            foreach ($res as $item) {
                $data[]  =[
                    'index' => $i++,
                    'label' => ($item->nickname ?? $item->mobile) . User::levelOptions()[$item->level],
                    'value' => $item->success_at
                ];
            }
            return $data;
            }, 3600 * 24);

        return json_decode($str);
    }

    static public function getRank(Challenge $challenge)
    {
        $res = Db::select("select count(id) as total from challenges where status ='challenging';");
        $total = $res[0]->total;

        $sql = "select row_num from (
                    SELECT row_number() over (order by level desc, id asc) row_num, id, level
                    FROM challenges
                    where status ='challenging'
                    order by level desc, id asc
                    ) as t
                    where t.id={$challenge->id};";
        $res = DB::select($sql);
        $rank = $res[0]->row_num ?? null;

        $behind = $total - $rank;

        $str = str_replace(
                ["{total}", "{rank}", "{behind}"],
                [$total, $rank, $behind],
                config("challenge.ranking.overview")
            );

        $percent = $rank * 100 / $total;
        return [
            "percent" => $percent,
            "text" => $str
        ];
    }
}
