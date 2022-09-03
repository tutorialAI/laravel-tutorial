<?php

namespace App\Providers;

use App\Listeners\UpdateProductsAmount;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Events\OrderShipped;
use App\Listeners\SendShipmentNotification;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Определить, должны ли автоматически обнаруживаться события и слушатели.
     * Сканирует папку Listeners и с помощью рефлексии ищит у классов метод,
     * начинающейся на handle
     *
     * @return bool
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }

    /**
     * Получить каталоги слушателей, которые следует использовать для обнаружения событий.
     *
     * @return array
     */
    protected function discoverEventsWithin()
    {
//        return [
//            $this->app->path('Listeners'),
//        ];
    }

    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        OrderShipped::class => [
//            SendShipmentNotification::class,
            UpdateProductsAmount::class
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
