<?php

use App\Http\Controllers\UserController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//sign-up users.
Route::post('/users', [UserController::class,'store']);

//log-in users.
Route::get('get-details/{name}/{password}', [UserController::class,'getDetails']);

//forgotten password.
Route::put('update-password/{name}', [UserController::class,'update']);
