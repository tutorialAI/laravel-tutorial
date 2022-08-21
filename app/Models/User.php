<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    //Один из многих, позволяет скрыть некоторые частые запросы в методы,
    // и в контроллере их можно булет вызывать User::find($id)->oldestPost;
    public function oldestPost(): HasOne
    {
        return $this->hasOne(Post::class)->ofMany('creation_date', 'min');
    }

    public function latestPost(): HasOne
    {
        return $this->hasOne(Post::class, 'user_id', 'id')->ofMany('creation_date', 'max');
    }

    /**
     * Отношение "Многие через отношение"
     * Получить все все комментарии для пользователя.
     */
    public function comments()
    {
        return $this->hasManyThrough(
            Comment::class,
            Post::class,
            'user_id', // Внешний ключ в таблице Post
            'post_id',  // Внешний ключ в таблице `Comment
            'id', // Локальный ключ в Comment
            'post_id' // Локальный ключ в Post
        );
    }
}
