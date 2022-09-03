<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public $fillable = [
        'name', 'article', 'price', 'quantity'
    ];

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_product');
    }
}
