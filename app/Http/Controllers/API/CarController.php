<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Car;
use App\Models\CarModel;
use App\Models\User;
use App\API\VinApi;
use App\Helpers\CarHelper;
// use App\Models\CrowdFunding;
// use App\Helpers\UserHelper;
// use App\Wechat;

class CarController extends AppBaseController
{
    protected $user;
    const mobileRules = [
        'mobile'    => 'required|string|size:11',
    ];
    const rules = [
        'mobile'    => 'required|string|size:11',
        'vin'       => 'required|string|size:17',
        'plate_no'  => 'required|string|min:5|max:8',
    ];
    private function _getUser($mobile)
    {
        if (!$user = User::firstWhere('mobile', $mobile)) {
            $user = User::create([
                'mobile' => $mobile,
                'email' => $mobile."@xiaofeice.com",
                'password' => \bcrypt($mobile)
            ]);
        }
        $this->user = $user;
    }

    /**
     * 获取用户车辆列表
     *
     * @OA\Get(
     *  path="/api/cars",
     *  tags={"Car"},
     *  @OA\Parameter(name="mobile",  in="query",required=true,explode=true,@OA\Schema(type="string"),description="用户手机号"),
     *  @OA\Response(response=200,description="successful operation"),
     *  security={{ "api_key":{} }}
     * )
     */
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), self::mobileRules);
        if ($validator->fails()) {
            return $this->sendError(array_map(fn($k): string =>$k[0], $validator->errors()->toArray()));
        }
        $this->_getUser($request->input('mobile'));

        $data = [];
        foreach ($this->user->cars as $car){
            $data[] = $car->info();
        }
        return $this->sendResponse($data);
    }

    /**
     * 获取单个车辆信息
     *
     * @OA\Get(
     *  path="/api/cars/{id}",
     *  tags={"Car"},
     *  @OA\Parameter(name="id",  in="path",required=true,explode=true,@OA\Schema(type="string"),description="车辆ID"),
     *  @OA\Parameter(name="mobile",  in="query",required=true,explode=true,@OA\Schema(type="string"),description="用户手机号"),
     *  @OA\Response(response=200,description="successful operation"),
     *  security={{ "api_key":{} }}
     * )
     */
    public function get($id, Request $request)
    {
        $validator = Validator::make($request->all(), self::mobileRules);
        if ($validator->fails()) {
            return $this->sendError(array_map(fn($k): string =>$k[0], $validator->errors()->toArray()));
        }
        $this->_getUser($request->input('mobile'));

        $data = null;
        if ($car = $this->user->cars()->find($id)){
            $data = $car->info();
        }else{
            return $this->sendError("您没有该车辆信息！");
        }
        return $this->sendResponse($data);
    }

    /**
     * 添加车辆信息
     *
     * @OA\Post(
     *  path="/api/cars",
     *  tags={"Car"},
     *   @OA\RequestBody(
     *       required=false,
     *       @OA\MediaType(
     *           mediaType="application/x-www-form-urlencoded",
     *           @OA\Schema(
     *               type="object",
     *               @OA\Property(property="mobile",description="用户手机号",type="string"),
     *               @OA\Property(property="plate_no",description="车牌号",type="string"),
     *               @OA\Property(property="vin",description="17位车架号（VIN码）",type="string"),
     *           )
     *       )
     *   ),
     *  @OA\Response(response=200,description="successful operation"),
     *  security={{ "api_key":{} }}
     * )
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, self::rules);
        if ($validator->fails()) {
            return $this->sendError(array_map(fn($k): string =>$k[0], $validator->errors()->toArray()));
        }
        $this->_getUser($request->input('mobile'));

        $input['user_id'] = $this->user->id;
        $vin = $input['vin'];
        // check car model id with vin
        try{
            $model = CarHelper::vinToModel($vin);
        }catch(\Exception $e) {
            \Log::debug($e->getMessage());
            return $this->sendError("车架号码（VIN码）不正确，无法获取车型信息。");
        }
        $input['car_model_id'] = $model->id ?? null;
        $car = Car::create($input);

        return $this->sendResponse($car->info());
    }

    /**
     * 更新车辆信息
     *
     * @OA\Put(
     *  path="/api/cars/{id}",
     *  tags={"Car"},
     *  @OA\Parameter(name="id",  in="path",required=true,explode=true,@OA\Schema(type="string"),description="车辆ID"),
     *  @OA\RequestBody(
     *       required=false,
     *       @OA\MediaType(
     *           mediaType="application/x-www-form-urlencoded",
     *           @OA\Schema(
     *               type="object",
     *               @OA\Property(property="mobile",description="用户手机号",type="string"),
     *               @OA\Property(property="plate_no",description="车牌号",type="string"),
     *               @OA\Property(property="vin",description="17位车架号（VIN码）",type="string"),
     *           )
     *       )
     *   ),
     *  @OA\Response(response=200,description="successful operation"),
     *  security={{ "api_key":{} }}
     * )
     */
    public function update($id, Request $request)
    {
        $validator = Validator::make($request->all(), self::rules);
        if ($validator->fails()) {
            return $this->sendError(array_map(fn($k): string =>$k[0], $validator->errors()->toArray()));
        }
        $this->_getUser($request->input('mobile'));

        $input = [];
        $input['plate_no'] = $request->input('plate_no');
        $input['vin'] = $request->input('vin');
        $input['user_id'] = $this->user->id;
        if (!$car = Car::find($id)) {
            return $this->sendError("没有该车辆信息！");
        }

        // check car model id with vin
        $vin = $input['vin'];
        if ($car->vin != $vin) {
            try{
                $model = CarHelper::vinToModel($vin);
            }catch(\Exception $e) {
                \Log::debug($e->getMessage());
                return $this->sendError("车架号码（VIN码）不正确，无法获取车型信息。");
            }
            $input['car_model_id'] = $model->id ?? null;
        }
        $car->update($input);

        return $this->sendResponse($car->refresh()->info());
    }

    /**
     * 删除车辆
     *
     * @OA\Delete(
     *  path="/api/cars/{id}",
     *  tags={"Car"},
     *  @OA\Parameter(name="id",  in="path",required=true,explode=true,@OA\Schema(type="string"),description="车辆ID"),
     *  @OA\Parameter(name="mobile",  in="query",required=true,explode=true,@OA\Schema(type="string"),description="用户手机号"),
     *  @OA\Response(response=200,description="successful operation"),
     *  security={{ "api_key":{} }}
     * )
     */
    public function delete($id, Request $request)
    {
        $validator = Validator::make($request->all(), self::mobileRules);
        if ($validator->fails()) {
            return $this->sendError(array_map(fn($k): string =>$k[0], $validator->errors()->toArray()));
        }
        $this->_getUser($request->input('mobile'));

        if ($car = $this->user->cars()->find($id)){
            $car->delete();
        }else{
            return $this->sendError("您没有该车辆信息！");
        }
        return $this->sendResponse(null, "删除成功！");
    }
}
