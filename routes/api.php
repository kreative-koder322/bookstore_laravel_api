<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BooksController;
use App\Http\Controllers\CheckoutsController;
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

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');

});

Route::controller(BooksController::class)->group(function () {
    Route::get('books', 'index');
    Route::post('books', 'store');
    Route::get('book/{id}', 'show');
    Route::put('books/{id}', 'update');
    Route::delete('books/{id}', 'destroy');
    Route::post('checkouts', 'checkouts');
    Route::put('checkouts/{id}', 'checkouts');
}); 
Route::group(['middleware' => ['jwt.verify']], function() {
    Route::controller(BooksController::class)->group(function () {
        Route::get('books', 'index');
        Route::post('books', 'store');
        Route::get('book/{id}', 'show');
        Route::put('books/{id}', 'update');
        Route::delete('books/{id}', 'destroy');
    
    }); 

    Route::controller(CheckoutsController::class)->group(function () {
    Route::post('checkouts', 'checkouts');
    Route::put('checkouts/{id}', 'return');
}); 
});