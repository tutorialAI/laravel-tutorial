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

// Неявная привязка запроса к модели по имени, по умоляанию id,
// если нужно чтобы по умолчанию модель привязваловась по другому ключу,
// то в моделе нужно в методе getRouteKeyName венуть имя ключа
Route::get('/store/{user:name}', function (User $user) {
    return "User name " . $user->name . ", user email " . $user->email;
});

//Более сложный пример, привязка пользователя по id, и поста по slug
Route::get('/users/{user}/posts/{post:slug}', function (User $user, Post $post) {
    return "User name" . $user->name . " and his post " . $post->title;
})->missing(function (Request $request) { // переопределяет 404
    return Redirect::route('locations.index');
});

Route::get('/search/{search}', function ($search) {
    return $search;
})->where('search', '.*');

// именовванные маршруты
Route::get('/user/{id}/profile', function ($id) {
    //
})->name('profile');

// применение

// Создание URL-адреса ...
//$url = route('profile', ['id' => 1]);
//
// Создание перенаправления ...
//return redirect()->route('profile', ['id' => 1]);


// домены
Route::domain('{account}.example.com')->group(function () {
    Route::get('user/{id}', function ($account, $id) {
        //
    });
});

// роуты проверки авторизации
Route::post('/comments/{id}', [\App\Http\Controllers\CommentController::class, 'edit']);


// если не один из маршрутов не совпал
Route::fallback(function () {
    return ";( route not found, nothing to say";
});
