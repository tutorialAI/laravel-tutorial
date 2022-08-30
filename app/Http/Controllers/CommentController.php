<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Comment::class, 'comment');
    }

    public function index()
    {

    }

    public function store(Request $request, Comment $comment)
    {

    }

    /**
     * Авторизация с ипользованием политики.
     *
     * @param Comment $comment
     * @return \Illuminate\Http\Response
     */
    public function show(Comment $comment)
    {
        $comment = Comment::find($comment->id);
        $post = $comment->post;

        return response($post);
    }

    // Авторизация с шлюзами, т.е без использование политики
    public function edit(Request $request)
    {
        $comment = Comment::find($request->id);

        // Проверка прав пользователя на обновление данное записи,
        // с помощью Gate update-post, определенным в AuthServiceProvider
//        if (!Gate::allows('update-comment', $comment)) {
//            abort(403);
//        }
        // Тоже самое, но более короткий вариант
        Gate::authorize('update-comment', $comment);

        // С сообщением от Gate при условие false
//        $response = Gate::inspect('edit-settings', $comment);
//        if (!$response->allowed()) echo $response->message();

        // Gate::forUser($user)->allows('update-comment', $comment) - проверка для другого пользователя
        // Gate::none/any(['update-post', 'delete-comment'], $comment) - набор рарешений через массив,
        // none - все запрещено | any - все разрешено
        // allows, denies, check, any, none, authorize, can, cannot, в blade @can, @cannot, @canany


        // Проверка без написания специального шлюза
        // Gate::allowIf/denyIf(fn ($user) => $user->isAdministrator());

        $comment->text = $request->text;

        $comment->save();
    }

    public function update(Request $request, Comment $comment)
    {

    }

    public function destroy($id)
    {

    }
}
