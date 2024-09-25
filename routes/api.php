<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ChallengeController;
use App\Http\Controllers\API\WechatController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\ImageController;
use App\Http\Controllers\API\CarManagerController;
use App\Http\Controllers\API\PublicController;
use App\Http\Controllers\API\CompanyController;
use App\Http\Controllers\API\CarController;
use App\Http\Controllers\API\AgentController;
use App\Http\Controllers\API\PaymentController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group(['middleware' => ['auth:sanctum']], function(){
    Route::get('/user/qrcode',      [UserController::class, "qrcode"]);
    Route::get('/user/info',        [UserController::class, "info"]);
    Route::post('/user/info',       [UserController::class, "profile"]);
    Route::get('/user/challenge',   [UserController::class, "challenge"]);
    Route::post('/user/challenge',  [UserController::class, "startChallenge"]);
    Route::get('/user/crowdFunding',[UserController::class, "crowdFunding"]);
    Route::get('/user/company',     [UserController::class, "company"]);
    Route::get('/user/partner-company',[UserController::class, "partnerCompany"]);
    Route::get('/user/partner-stats',[UserController::class, "partnerStats"]);
    Route::get('/user/agent',       [UserController::class, "agent"]);
    Route::get('/user/team-overview',[UserController::class,"teamOverview"]);
    Route::get('/user/recommends',  [UserController::class, "recommends"]);
    Route::get('/user/paginate-recommends',  [UserController::class, "paginateRecommends"]);
    Route::post('/user/sales',      [UserController::class, "sales"]);

    Route::get('/user/team-detail', [UserController::class, "teamDetail"]);
    Route::post('/user/images',     [UserController::class, "images"]);
    Route::post('/user/apply',      [UserController::class, "apply"]);
    Route::post('/user/car',        [UserController::class, "saveCar"]);
    Route::get ('/user/car',        [UserController::class, "car"]);
    Route::get ('/consumer/{id}',   [UserController::class, "consumer"]);

    Route::post('/company',         [CompanyController::class, "store"]);
    Route::post('/company/{id}/partner-asset', [CompanyController::class, "partnerAsset"]);

    Route::get ('/managers',        [AgentController::class, "managers"]);
    Route::get ('/managers/{id}',   [AgentController::class, "manager"]);

    Route::post('/payment/register-consumer',[PaymentController::class, "registerConsumer"]);
});
Route::get('/public/index',         [PublicController::class, "index"]);
Route::get('/public/area',          [PublicController::class, "areaData"]);
Route::get('/public/privacy',       [PublicController::class, "privacy"]);
Route::get('/public/form-options',  [PublicController::class, "formOptions"]);
// Route::get('/public/car-options',   [PublicController::class, "carOptions"]);
Route::get('/public/apps',          [PublicController::class, "apps"]);
Route::get('/public/banners',       [PublicController::class, "banners"]);
Route::get('/public/market',        [PublicController::class, "market"]);
Route::get('/public/rules',         [PublicController::class, "rules"]);

Route::get('/challenge/levels',     [ChallengeController::class, "levels"]);
Route::get('/challenge/types',      [ChallengeController::class, "types"]);
Route::get('/challenge/stats',      [ChallengeController::class, "stats"]);
Route::get('/challenge/success',    [ChallengeController::class, "success"]);
Route::get('/challenge/activity',   [ChallengeController::class, "activity"]);
Route::get('/challenge/range',      [ChallengeController::class, "range"]);
Route::post('/wxapp/register',      [WechatController::class, 'register']);
Route::post('/wxapp/login',         [WechatController::class, 'login']);
Route::post('/wxapp/notify',        [WechatController::class, 'notify']);

// car manager
Route::get('/car-manager/funding-stats',    [CarManagerController::class, 'fundingStas']);
Route::get('/car-manager/funding-config',   [CarManagerController::class, 'fundingConfig']);

Route::get('/cars',                 [CarController::class, 'index']);
Route::post('/cars',                [CarController::class, 'store']);
Route::get('/cars/{id}',            [CarController::class, 'get']);
Route::put('/cars/{id}',            [CarController::class, 'update']);
Route::delete('/cars/{id}',         [CarController::class, 'delete']);
// car owner
