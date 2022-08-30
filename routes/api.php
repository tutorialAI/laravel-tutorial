<?php

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

// роуты проверки авторизации
Route::post('/comments/{id}', [\App\Http\Controllers\CommentController::class, 'edit']);


// если не один из маршрутов не совпал
Route::fallback(function () {
    return ";( route not found, nothing to say";
});
