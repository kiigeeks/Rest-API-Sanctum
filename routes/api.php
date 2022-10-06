<?php

use App\Http\Controllers\API\AlbumsController;
use App\Http\Controllers\API\AnimalsController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function() {
    Route::get('profil', [AuthController::class, 'show']);
    Route::get('logout', [AuthController::class, 'logout']);
    Route::apiResource('category', CategoryController::class);
    Route::apiResource('animals', AnimalsController::class);
    Route::apiResource('albums', AlbumsController::class);
});


Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

