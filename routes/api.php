<?php

use App\Http\Controllers\API\ActivityController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\RegisterActivityController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('login',[AuthController::class,'login']);

Route::post('logout',[AuthController::class,'logout'])->middleware('auth:sanctum');


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:sanctum','isAPIAdmin'])->group(function(){

    Route::get('/checkingAuthenticated',function(){
       
        return response()->json(['messeage'=>"You are in",'status'=>200],200);

    });
    
//   user
    Route::post('store-user',[AuthController::class,'store']);
    Route::get('view-users',[AuthController::class,'index']);
    Route::get('edit-user/{id}',[AuthController::class,'edit']);
    Route::post('update-user/{id}',[AuthController::class,'update']);
    Route::delete('delete-user/{id}',[AuthController::class,'destroy']);  

    Route::post('store-category',[CategoryController::class,'store']);
    Route::get('view-category',[CategoryController::class,'index']);
    Route::get('all-category',[CategoryController::class,'getStatus']);

    Route::get('edit-category/{id}',[CategoryController::class,'getId']);
    Route::post('update-category/{id}',[CategoryController::class,'update']);
    Route::delete('delete-category/{id}',[CategoryController::class,'destroy']);  

    
    Route::post('store-activity',[ActivityController::class,'store']);
    Route::get('view-activity',[ActivityController::class,'index']);
    Route::get('edit-activity/{id}',[ActivityController::class,'getId']);
    Route::post('update-activity/{id}',[ActivityController::class,'update']);
    Route::delete('delete-activity/{id}',[ActivityController::class,'destroy']);  

});

Route::post('store-activity',[ActivityController::class,'store']);
Route::get('view-activity',[ActivityController::class,'index']);
Route::get('view-activity-top',[ActivityController::class,'getTop']);

Route::get('get-user',[AuthController::class,'user']);
Route::get('view-register-activity',[RegisterActivityController::class,'index']);

Route::get('view-all-register-activity',[RegisterActivityController::class,'indexAll']);
Route::get('view-user-activity',[RegisterActivityController::class,'indexAllUser']);
Route::post('delete-register-activity',[RegisterActivityController::class,'destroy']);  



Route::get('get-register-activity/{id}',[RegisterActivityController::class,'getId']);

Route::post('update-register-activity',[RegisterActivityController::class,'update_status']);
Route::post('add-register-activity/{id}',[RegisterActivityController::class,'register_activity']);
// Route::get('edit-activity/{id}',[ActivityController::class,'getId']);

// Route::post('update-category/{id}',[ActivityController::class,'update']);


Route::get('all-category',[CategoryController::class,'getStatus']);
Route::get('view-category',[CategoryController::class,'index']);
Route::post('store-category',[CategoryController::class,'store']);
Route::get('edit-category/{id}',[CategoryController::class,'getId']);