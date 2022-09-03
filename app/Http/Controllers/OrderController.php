<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderStoreRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use Illuminate\Support\Arr;
use App\Events\OrderShipped;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function store(OrderStoreRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();
        $products = Product::whereIn('id', array_column($validated['products'], 'id'))
            ->pluck('price', 'id');

        $order = Order::create([
            'user_id' => 2,
            'ordered_at' => now()
        ]);

        $orderProducts = $this->mapProducts($validated['products'], $products);
        $order->products()->attach($orderProducts);
        DB::commit();

        // возможно это костыль, но сделал для примера проверки событя
        $order['products'] = $orderProducts;
        OrderShipped::dispatch($order);

        return response('Ok');
    }

    private function mapProducts($requestProducts, $exitsProducts)
    {
        $result = [];
        foreach ($requestProducts as $p) {
            $result[$p['id']] = [
                'quantity' => $p['quantity'],
                'price' => $exitsProducts[$p['id']] * $p['quantity']
            ];
        }

        return $result;
    }
}
