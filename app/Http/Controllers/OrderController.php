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

        $orderProducts = [];
        foreach ($validated['products'] as $product) {
            $orderProducts[$product['id']] = [
                'quantity' => $product['quantity'],
                'price' => $products[$product['id']] * $product['quantity']
            ];
        }

        $order->products()->attach($orderProducts);
        DB::commit();

        return response('Ok');
    }
}
