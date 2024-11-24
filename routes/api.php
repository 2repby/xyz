<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryControllerApi;
use App\Http\Controllers\ItemControllerApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('/login', [AuthController::class,'login']);



//Route::get('/item', [ItemControllerApi::class, 'index']);

Route::get('/category', [CategoryControllerApi::class, 'index']);
Route::get('/category/{id}', [CategoryControllerApi::class, 'show']);
Route::get('/categories_total', [CategoryControllerApi::class, 'total']);



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::middleware('auth:sanctum')->get('/logout', [AuthController::class, 'logout']);

Route::group(['middleware' => ['auth:sanctum']],  function (){
    Route::get('/item', [ItemControllerApi::class, 'index']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::get('/logout', [AuthController::class, 'logout']);
    Route::post('/item', [ItemControllerApi::class, 'store']);
    Route::post('/category', [CategoryControllerApi::class, 'store']);
});


