<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index(Request $request)
    {
//        return Post::select('title', 'description', 'creation_date', 'updated_date')
//            ->orderBy('title')
//            ->take(10)
//            ->get();
//
//        $title = Post::where('post_id', 3)->value('title');
//        $singleColumn = Post::pluck('title');
//        $keyPairValue = Post::pluck('post_id', 'title');

        $ids = $request->query('id') ?? 25;

        $posts = Post::orderByRaw('CHAR_LENGTH(description)')
            ->select('post_id', 'title', 'description')
            ->selectRaw('CHAR_LENGTH(description) as desc_length, DATE(creation_date) as creation_date')
            ->withoutGlobalScope(AncientScope::class) // игнорирование глбального дипазона, игнорировать всех withoutGlobalScopes()
            ->ForUser(2)
            ->lazy();
//            ->filter(function ($user) use ($ids) {
//                return $user->post_id > $ids;
//            });

        return view('posts', [
            'posts' => $posts
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }


    // Отношения
    public function display(Post $post)
    {
        // $post = Post::where('post_id', $id);
//        $user = User::find($id)->latestPost;


//        User::find($id)->comments()
        $post = Post::find($post->id);
        //$comments = User::find($id)->comments; // многие через отношение
//        $posts = Post::whereBelongsTo($user)->get();

        //Определение обратной связи Один ко многим
        return view('post', $post);
    }

    public function show(Request $request)
    {
        // Запускает перебор внутренних объектов, как только они потребуются
        // т.е вытащит посты одим запросом, но если мы заходим достать комментарии поста,
        // то он будет делать много внутренних sql запросов, на каждый пост для вытаскивания комментария
//        foreach (Post::lazy() as $post) {
//            $post->comments;
//        }

        // Жадная загрузка вытаскивает все комментарии, вставляя id постов в WHERE IN ()
//        foreach (Post::with('comments')->get() as $post) {
//            $post->comments; // тут массив
//        }

        // “Ленивая” жадная загрузка, В этом подходе мы можем разделить два запроса.
        // Первый, получая начальный результат: select * from `posts`
        $posts = Post::all();
        // А позже, если мы решим (на основании некоторого условия), что нам нужны соответствующие комментарии
        // для этих постов, то мы можем загрузить их постфактом
        if ($someCondition) {
            $comments = $posts->load('comments');
        }



        return view('welcome');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Удаление модели
        $flight = Flight::find(1);
        $flight->delete();

        // Удаление моделей через запрос
        $deletedRows = Flight::where('active', 0)->delete();

        // Удаляет элемены из БД, не извлекая их,
        // т.е мы просто передаем туда грпппу id
        Flight::destroy(collect([1, 2, 3]));
    }
}
