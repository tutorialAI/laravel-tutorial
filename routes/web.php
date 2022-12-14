<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/users/', [UserController::class, 'index']);
//    Route::get('/users/auth', [UserController::class, 'index']);
});


// Методы авторизации черезе политику CommentPolicy
//Route::get('/comments/{comment}', [\App\Http\Controllers\CommentController::class, 'show']);
Route::get('/comments/{comment}', function (\App\Models\Comment $comment) {
    return response('You can update this comment');
})->middleware('can:update,comment');

// })->can('update', 'post'); // тоже самое, что и выше

// Тут все роуты index,update,create и т.д
// Выполняем разные запросы
Route::resource('posts', PostController::class);


// Посредники
Route::middleware(['role:admin', 'token.verified', 'another.middleware'])->prefix('just')->group(function () {
    Route::group('/watch/{movie}', function () {
        return response('You can watch this resource');
    });
});

require __DIR__.'/auth.php';
