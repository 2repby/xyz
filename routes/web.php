<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


//Route::get('/', function () {
//    return view('welcome');
//});
Route::get('/hello', function () {
    return view('hello', ['title' => 'Hello world!']);
});

Route::get('/', function () {
    return view('main');
});
Route::get('login', function () {
    return redirect('/')->withErrors([
        'error' => 'Войдите в систему для совершения данного действия',
    ]);
})->name('login');

Route::get('/category', [CategoryController::class, 'index']);

Route::get('/item', [ItemController::class, 'index']);

Route::get('/item/create', [ItemController::class, 'create'])->middleware('auth');

Route::get('/item/destroy/{id}', [ItemController::class, 'destroy'])->middleware('auth');

Route::post('/item/update/{id}', [ItemController::class, 'update'])->middleware('auth');

Route::get('/item/edit/{id}', [ItemController::class, 'edit'])->middleware('auth');

Route::post('/item', [ItemController::class, 'store']);

Route::get('/item/{id}', [ItemController::class, 'edit']);

Route::get('/category/{id}', [CategoryController::class, 'show']);

Route::get('/order/{id}', [OrderController::class, 'show']);

//Route::get('/login', [LoginController::class, 'login'])->name('login');

Route::get('/logout', [LoginController::class, 'logout']);

Route::post('/auth', [LoginController::class, 'authenticate']);
