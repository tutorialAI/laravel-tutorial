<?php

namespace App\Listeners;

use App\Events\OrderShipped;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateProductsAmount
{
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
     * @param  OrderShipped $event
     * @return void
     */
    public function handle(OrderShipped $event)
    {
        $order = $event->order;

        foreach ($order->products as $id => $product) {
            Product::find($id)->decrement('quantity', $product['quantity']);
        }
    }
}
