<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductModel extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'name',
        'description',
        'price',
        'category_id',
        'avatar',
        'slug',
    ];

    public static function formatPrice($products)
    {
        foreach ($products as $product) {
            $product->priceFormatted = number_format($product->price, 0, ',', '.');
        }

        return $products;
    }

    public static function search($keyword, $minPrice = null, $maxPrice = null)
    {
        $query = ProductModel::where(function($q) use ($keyword) {
            $q->where('name', 'like', "%$keyword%")
              ->orWhere('description', 'like', "%$keyword%");
        });

        // Áp dụng bộ lọc giá nếu có
        if ($minPrice !== null && $minPrice > 0) {
            $query->where('price', '>=', $minPrice);
        }

        if ($maxPrice !== null && $maxPrice > 0) {
            $query->where('price', '<=', $maxPrice);
        }

        // Kiểm tra nếu min > max thì hoán đổi giá trị
        if ($minPrice > 0 && $maxPrice > 0 && $minPrice > $maxPrice) {
            $temp = $minPrice;
            $minPrice = $maxPrice;
            $maxPrice = $temp;
            
            // Cập nhật lại query với giá trị mới
            $query = ProductModel::where('name', 'like', "%$keyword%")
                               ->orWhere('description', 'like', "%$keyword%")
                               ->whereBetween('price', [$minPrice, $maxPrice]);
        }

        $products = $query->orderBy('created_at', 'desc')
                         ->orderBy('id', 'desc')
                         ->get();

        return ProductModel::formatPrice($products);
    }
}
