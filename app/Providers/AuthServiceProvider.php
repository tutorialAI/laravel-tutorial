<?php

namespace App\Providers;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use App\Policies\CommentPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\Response;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
         Comment::class => CommentPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Запускается перед всеми проверками
//        Gate::before(function ($user, $ability) {
//            if ($user->isAdministrator()) {
//                return true;
//            }
//        });

        Gate::define('update-comment', function (User $user, Comment $comment) {
            // return $user->id === $comment->post->user_id // - обыяная проерка, которая вернет bool значение

            // Вернуть с сообщением, при случае false
            return $user->id === $comment->post->user_id
                ? Response::allow()
                : Response::deny('У вас нет прав на редактирование этого комментария');
        });

        Gate::define('edit-settings', function (User $user) {
            return $user->isAdmin
                ? Response::allow()
                : Response::deny('Вы должны быть администратором.');
        });
    }
}
