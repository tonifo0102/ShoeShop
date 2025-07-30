<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderProductModel extends Model
{
    use HasFactory;
    protected $table = 'order_product';

    /**
     * Get the order that owns the product.
     */
    public function order()
    {
        return $this->belongsTo(OrderModel::class, 'order_id');
    }
}
