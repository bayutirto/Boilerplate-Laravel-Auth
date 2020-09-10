<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserGroupController;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function () {
    //Login
    Route::post('login', [LoginController::class, 'login'])->middleware('checkjson');
    Route::get('user-group',[UserGroupController::class, 'find']);

    //User
    Route::get('user', [UserController::class, 'find']);
    Route::post('user', [UserController::class, 'insert']);
    Route::patch('user/{idUser}', [UserController::class, 'update'])->middleware('checkuser');
    Route::delete('user/{idUser}', [UserController::class, 'delete'])->middleware('checkuser');
});
