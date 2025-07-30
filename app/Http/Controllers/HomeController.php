<?php

namespace App\Http\Controllers;

use App\Models\CategoryModel;
use App\Models\ProductModel;
use App\Models\OrderModel;
use App\Models\AccountModel;
use App\Models\OrderProductModel;

class HomeController extends Controller 
{
    public function index()
    {
        $newProducts = ProductModel::orderBy('created_at', 'desc')
                                    ->orderBy('id', 'desc')
                                    ->limit(9)
                                    ->get();
        $newProducts = ProductModel::formatPrice($newProducts);

        foreach ($newProducts as $key => $product) {
            $reviews = OrderProductModel::where('product_id', $product->id)
                                        ->where('vote', '!=', null)
                                        ->get();
            $total = $reviews->count();
            if ($total != 0) {
                $sum = 0;
                foreach ($reviews as $review) {
                    $sum += $review->vote;
                }
                $ratio = $sum / $total;

                $product->ratio = $ratio;
                $product->total = $total;
            } else {
                $product->ratio = 0;
                $product->total = 0;
            }
        }

        // Lấy danh sách order và name của khách hàng
        $reviews = OrderModel::join('accounts', 'accounts.id', '=', 'orders.account_id')
                            ->select('orders.*', 'accounts.name')
                            ->orderBy('orders.created_at', 'desc')
                            ->limit(5)
                            ->get();
        
        $page = view('home/index', [
            'NEW_PRODUCTS' => $newProducts,
            'REVIEWS' => $reviews,
        ]);

        return $this->loadLayout($page);
    }

    public function statistical()
    {
        // Tính tổng doanh thu tất cả các đơn hàng đã hoàn thành
        $totalRevenue = 0;
        $orders = OrderModel::where('status', 'complete')->get();
        foreach ($orders as $order) {
            $total_temp = 0;
            $products = OrderProductModel::where('order_id', $order->id)->get();
            foreach ($products as $product) {
                $percent = 0;
                if ($order->discount_percent != null && $order->discount_percent != 0) {
                    $percent = $order->discount_percent;
                }

                $total_temp += $product->price * $product->quantity * (100 - $percent) / 100;
            }
            $totalRevenue += $total_temp;
        }

        // Tính tổng doanh thu của tháng này
        $totalRevenueThisMonth = 0;
        $orders_this_month = OrderModel::where('status', 'complete')
                            ->where('updated_at', '>=', date('Y-m-01 00:00:00'))
                            ->get();

        foreach ($orders_this_month as $order) {
            $total_temp = 0;
            $products = OrderProductModel::where('order_id', $order->id)->get();
            foreach ($products as $product) {
                $percent = 0;
                if ($order->discount_percent != null && $order->discount_percent != 0) {
                    $percent = $order->discount_percent;
                }

                $total_temp += $product->price * $product->quantity * (100 - $percent) / 100;
            }
            $totalRevenueThisMonth += $total_temp;
        }

        
        $page = view('admin/statistical', [
            'PRODUCTS' => ProductModel::all(),
            'CATEGORIES' => CategoryModel::all(),
            'ORDERS' => OrderModel::where('status', 'complete')->get(),
            'SHIPPER' => AccountModel::where('role', 'shipper')->get(),
            'ACCOUNTS' => AccountModel::where('role', 'guest')->get(),
            'TOTAL_REVENUE' => $totalRevenue,
            'TOTAL_REVENUE_THIS_MONTH' => $totalRevenueThisMonth,
            'ORDERS_THIS_MONTH' => $orders_this_month,
        ]);

        return $this->loadLayoutUser($page);
    }
}