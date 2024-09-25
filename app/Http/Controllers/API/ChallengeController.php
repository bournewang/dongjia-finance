<?php

namespace App\Http\Controllers\API;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Challenge;
use App\Models\User;
use App\Helpers\ChallengeHelper;
use Log;

class ChallengeController extends ApiBaseController
{
    public function success(Request $request)
    {
        $list = Challenge::whereNotNull('success_at')->orderBy('success_at', 'desc')->limit(20)->get();
        $data = [];
        foreach ($list as $item){
            // $data[] = "$item->success_at ".$item->user->name ."挑战".$item->user->levelLabel()."成功！";
            $data[] = [ "success_at" => $item->success_at, "content" => $item->user->name ."征召".$item->user->levelLabel()."成功！"];
        }

        return $this->sendResponse($data);
    }

    public function stats(Request $request)
    {
        $data = ['stats' => ChallengeHelper::stats()];
        if ($request->input('activity', false)) {
            $ranking = ChallengeHelper::ranking();

            $activity = [];
            foreach ($ranking['items'] as $item){
                // $data[] = "$item->success_at ".$item->user->name ."挑战".$item->user->levelLabel()."成功！";
                $activity[] = [
                    "updated_at" => $item['updated_at'] ?? null,
                    "content" => ($item["name"] ?? null) . ($item['level_label'] ?? null) . ($item['status_label'] ?? null) . ", 征召人数" .($item['recommends_count']??0) . "。",
                    "avatar" => ($item['avatar'] ?? null)
                ];
            }
            $data['activity'] =$activity;
            $data['hasMore'] = $ranking['hasMorePages'];
        }
        return $this->sendResponse($data);
    }

    public function activity(Request $request)
    {
        $ranking = ChallengeHelper::ranking();

        $activity = [];
        foreach ($ranking['items'] as $item){
            $activity[] = [
                "updated_at" => $item['updated_at'] ?? null,
                "content" => ($item["name"] ?? null) . ($item['level_label'] ?? null) . ($item['status_label'] ?? null) . ", 征召人数" .($item['recommends_count']??0) . "。",
                "avatar" => ($item['avatar'] ?? null)
            ];
        }
        return $this->sendResponse([
            'items' => $activity,
            'page' => $request->input('page', 1),
            'hasMorepages' => $ranking['hasMorePages']
        ]);
    }

    public function levels()
    {
        return $this->sendResponse(array_slice(config('challenge.levels'), 3));
    }

    public function types()
    {
        return $this->sendResponse(Challenge::typeOptions());
    }

    /**
     * 获取直推排行榜
     *
     * @OA\Get(
     *  path="/api/challenge/range",
     *  tags={"User"},
     *  @OA\Response(response=200,description="successful operation"),
     *  security={{ "api_key":{} }}
     * )
     */
    public function range()
    {
        return $this->sendResponse(ChallengeHelper::range());
    }
}
