<?php

namespace App\Listeners;

use App\Events\OrderShipped;
use Illuminate\Contracts\Queue\ShouldQueue; // Этот интерфейс ставит слушатель события в очередь
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendShipmentNotification
{
    /*
     * Свойства $connection, $queue и $delay нужны только для настройки очереди,
     * если не исплементировать интрейвес ShouldQueue, этот не будет иметь смысла
     * */

    /**
     * Имя соединения, на которое должно быть отправлено задание.
     *
     * @var string|null
     */
    public $connection = 'sqs';

    /**
     * Имя очереди, в которую должно быть отправлено задание.
     *
     * @var string|null
     */
    public $queue = 'listeners';

    /**
     * Время (в секундах) до обработки задания.
     *
     * @var int
     */
    public $delay = 60;

    /**
     * Количество попыток запустить слушатель в очереди.
     * После чего будет выброшена ошибка, котору можно обработать в методе failed ниже
     *
     * @var int
     */
    public $tries = 5;

    /*
     *  Слушатель в очереди должен быть отправлен только после того,
     *  как все открытые транзакции базы данных были зафиксированы
     * */
    public $afterCommit = true;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     * Здесь происходит обработка события
     *
     * Можно выполнить остановку распространения события среди других слушателей - вернув false
     * @param OrderShipped $event
     * @return void
     */
    public function handle(OrderShipped $event)
    {
        $order = $event->order;

        // отправить уведомление о поступление заказа на почту
        Mail::to($order->email)->send(new \App\Mail\OrderShipped($order));
    }

    /**
     * Определить, следует ли ставить слушателя в очередь.
     * Возращает true, когда следует ставить слушатель в очередь
     *
     * @param  \App\Events\OrderCreated  $event
     * @return bool
     */
    public function shouldQueue(OrderCreated $event)
    {
        return $event->order->subtotal >= 5000;
    }

    /**
     * Вместо определения количества попыток в свойстве $tries,
     * можно просто определить время, через которое слушатель должен отключиться.
     *
     * @return \DateTime
     */
    public function retryUntil()
    {
        return now()->addMinutes(5);
    }

    /**
     * Обработать слушатель в случае провал задания в очереди.
     *
     * @param  \App\Events\OrderShipped  $event
     * @param  \Throwable  $exception
     * @return void
     */
    public function failed(OrderShipped $event, $exception)
    {
        //
    }
}
