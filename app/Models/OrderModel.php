<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderModel extends Model
{
    use HasFactory;
    protected $table = 'orders';

    /**
     * Get the account that owns the order.
     */
    public function account()
    {
        return $this->belongsTo(User::class, 'account_id');
    }
    
    /**
     * Get the products for the order.
     */
    public function products()
    {
        return $this->hasMany(OrderProductModel::class, 'order_id');
    }
}
