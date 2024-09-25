<?php

namespace App\Http\Controllers\API;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Exceptions\ApiException;
use App\Models\User;
use Auth;

class ApiBaseController extends AppBaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    protected $user = null;
    protected $store = null;

    public function __construct(Request $request)
    {
        $this->user = Auth::guard('sanctum')->user();
        // if ($this->user = Auth::guard('sanctum')->user()) {
        //     $this->store = $this->user->store;
        // }elseif (!app()->runningInConsole()){
        //     // throw new ApiException('请重新登录', 999);
        // }
    }

    public function paginateInfo($collection, $request, $func = 'info')
    {
        $paginator = $collection->simplePaginate(20);
        $data = [
            'page' => $request->input('page', 1),
            'hasMorePages' => $paginator->hasMorePages(),
            'items' => []
        ];
        // $collection = $collection->paginate($perpage);
        foreach ($paginator->getCollection() as $model) {
            $data['items'][] = $model->$func();
        }
        return $data;
    }

    protected function buildList($request, $collection, $fields)
    {
        $records = $collection->orderBy('id', 'desc');
        $total = $records->count();
        $perpage = $request->input('perpage', 20);
        $data = [
            'titles' => $fields,
            'total' => $total,
            'pages' => ceil($total/$perpage),
            'page' => $request->input('page', 1),
            'items' => []
        ];
        $records = $records->paginate($perpage);
        foreach ($records as $record) {
            $info = $record->info();
            $item = [];
            foreach ($fields as $key => $label) {
                $item[$key] = $info[$key];
            }
            $data['items'][] = $item;
        }
        return $data;
    }
}
