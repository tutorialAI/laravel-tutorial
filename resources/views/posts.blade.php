<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<ul>
    @foreach($posts as $post)
        <li>
        @can('update', $post)
            <!-- Текущий пользователь может обновить пост ... -->
                <a href="/posts/{{$post->post_id}}/edit">edit post</a>
        @endcan

        @can('create')
            <!-- Текущий пользователь может создавать новые посты ... -->
                <a href="/posts/create">create post</a>
        @endcan

        @cannot('update', $post)
            <!-- Текущий пользователь не может обновить пост ... -->
                not update |
        @elsecannot('create', App\Models\Post::class)
            <!-- Текущий пользователь не может создавать новые посты ... -->
                not create
            @endcannot

            {{ $post->post_id }} | {{ $post->creation_date }} | {{ $post->title }} | ({{ $post->desc_length }}) {{ $post->description }}
        </li>
    @endforeach
</ul>
</body>
</html>
