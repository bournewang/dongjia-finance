<?php
namespace App\Helpers;
use App\Models\User;
use App\Wechat;
use Carbon\Carbon;
use DB;

class UserHelper{

    // should be call while confirm user's challenge
    static public function createQrCode(User $user)
    {
        $url  ="user/{$user->id}.jpg";
        $path = \Storage::disk('public')->path($url);
        if (!file_exists(dirname($path))){
            // \Log::debug("dir not exists $path, create");
            mkdir(dirname($path));
        }
        // \Log::debug("url: $url, save qrcode to path: $path");
        try{
            $app = Wechat::app();
            $response = $app->getClient()->postJson('/wxa/getwxacodeunlimit', [
                'scene' => "referer_id={$user->id}",
                'page' => 'pages/profile/page',
                'width' => 430,
                'check_path' => false,
            ]);
            $response->saveAs($path);

            // $qrcodeUrl = url("storage/$url");
            $user->update(["qrcode" => "storage/$url"]);
        } catch (\Throwable $e) {
            // 失败
            \Log::debug($e->getMessage());
            // return $this->sendError("获取二维码失败: ".$e->getMessage());
        }
    }

    static public function recommendStats($user)
    {
        return [
            'register_consumers' => $user->recommends()->where('level', User::REGISTER_CONSUMER)->count(),
            'partner_consumers' => 0,//$user->recommends()->where('level', User::PARTNER_CONSUMER)->count(),
            'challenge_consumers' => $user->recommends()->where('level', '>', User::PARTNER_CONSUMER)->count(),
        ];
    }

    static public function totalTeamMembers($user)
    {
        return DB::table('relations')->where('path', 'like', "%,{$user->id},%")->count();
    }

    static public function teamOverview($user)
    {
        $str = cache1("user.{$user->id}.team-overview", function()use($user){
            $register_members   = $user->recommends()->where('level', User::REGISTER_CONSUMER)->count();
            $partner_members    = $user->recommends()->where('level', User::PARTNER_CONSUMER)->count();
            $appoint_managers   = $user->recommends()->where('level', '>', User::PARTNER_CONSUMER)->count();
            return [
                ['label' => __("Register Consumers"),       "value" => $register_members],
                ["label" => __("Partner Consumers"),        'value' => $partner_members],
                ["label" => __("Appoint Consumer Managers"),"value" => $appoint_managers]
            ];
        }, 3600);
        return json_decode($str);
    }

    static public function communityStationNotice($user)
    {
        $str = cache1("user.{$user->id}.community-station-notice", function()use($user){
            $total_consumers= $user->recommends()->where('level', User::REGISTER_CONSUMER)      ->count();
            $total_managers = $user->recommends()->where('level', '>', User::PARTNER_CONSUMER)  ->count();
            $new_consumers  = $user->recommends()->where('level', User::REGISTER_CONSUMER)      ->where('created_at', '>', today()->toDateString())->count();
            $new_managers   = $user->recommends()->where('level', '>', User::PARTNER_CONSUMER)  ->where('created_at', '>', today()->toDateString())->count();

            return ["notice" => str_replace([
                "{total_consumers}", "{total_managers}", "{new_consumers}", "{new_managers}"],
                [$total_consumers, $total_managers, $new_consumers, $new_managers],
                config("challenge.community_station_notice")
            )];
        }, 3600);
        return json_decode($str)->notice;

    }

    static public function teamDetail($user)
    {
        $str = cache1("user.{$user->id}.team-detail", function()use($user){
            $data = [];
            foreach ($user->recommends as $member) {
                $data[] = [
                    "head" => ["img" => $member->avatar, "label" => $member->nickname ?? $member->mobile, "desc" => User::levelOptions()[$member->level]],
                    "data" => $member->challenge ? self::teamOverview($member) : null
                ];
            }
            return $data;
        }, 3600 * 12);
        return json_decode($str);
    }

    static public function nextLevel($user)
    {
        $levels = array_keys(config("challenge.levels"));
        $index = array_search($user->level, $levels);
        if ($index === false){
            return $level;
        }
        $index++;
        $next = $index < count($levels) ? $index : (count($levels)-1);
        return $levels[$next];
    }
}
