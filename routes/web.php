<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\VNPayController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

// Login
Route::middleware('m_client')->get('/login', [AccountController::class, 'login'])->name('login');
Route::middleware('m_api_client')->post('/login', [AccountController::class, 'API_login'])->name('login.post');

// Register
Route::middleware('m_client')->get('/register', [AccountController::class, 'register'])->name('register');
Route::middleware('m_api_client')->post('/register', [AccountController::class, 'API_register'])->name('register.create');

// Forgot password
Route::middleware('m_client')->get('/forgot-password', [AccountController::class, 'forgot_password'])->name('forgot_password');
Route::middleware('m_api_client')->post('/forgot-password', [AccountController::class, 'API_forgot_password'])->name('forgot_password.post');

// Logout
Route::get('/logout', [AccountController::class, 'logout'])->name('logout');

// Search
Route::get('/search', [ProductController::class, 'search'])->name('search');

// Product Detail
Route::get('/product/{slug}', [ProductController::class, 'detail'])->name('product.detail');

// Category
Route::get('/category', [CategoryController::class, 'index'])->name('category');
Route::get('/category/{slug}', [CategoryController::class, 'search'])->name('category.search');

// All
Route::group(['prefix' => 'dashboard', 'middleware' => 'm_logged'], function () {
    // Info
    Route::group(['prefix' => 'info'], function () {
        Route::get('/', [AccountController::class, 'info'])->name('dashboard.info');
    });

    // Change password
    Route::group(['prefix' => 'change-password'], function () {
        Route::get('/', [AccountController::class, 'change_password'])->name('dashboard.change-password');
    });

});

// Guest
Route::group(['prefix' => 'guest', 'middleware' => 'm_guest'], function () {
    // Order
    Route::group(['prefix' => 'orders'], function () {
        Route::get('/', [OrderController::class, 'orders_show'])->name('dashboard.orders');
        Route::get('/history', [OrderController::class, 'orders_history'])->name('dashboard.orders.history');
    });
});

// Admin
Route::group(['prefix' => 'admin', 'middleware' => 'm_admin'], function () {
    // Account manager
    Route::group(['prefix' => 'account-manager'], function () {
        Route::get('/', [AccountController::class, 'account_manager'])->name('admin.account-manager');
    });

    // Category manager
    Route::group(['prefix' => 'category-manager'], function () {
        Route::get('/', [CategoryController::class, 'category_manager'])->name('admin.category_manager');
    });

    // Discount manager
    Route::group(['prefix' => 'discount-manager'], function () {
        Route::get('/', [DiscountController::class, 'discount_manager'])->name('admin.discount_manager');
    });

    // Product manager
    Route::group(['prefix' => 'product-manager'], function () {
        Route::get('/', [ProductController::class, 'product_manager'])->name('admin.product_manager');
    });

    // Order manager
    Route::group(['prefix' => 'order-manager'], function () {
        Route::get('/', [OrderController::class, 'orders_manager'])->name('admin.order_manager');
    });

    // Statistical
    Route::group(['prefix' => 'statistical'], function () {
        Route::get('/', [HomeController::class, 'statistical'])->name('admin.statistical');
    });
});

// Shipper
Route::group(['prefix' => 'shipper', 'middleware' => 'm_shipper'], function () {
    // Order approved
    Route::group(['prefix' => 'order-approved'], function () {
        Route::get('/', [OrderController::class, 'orders_approved'])->name('shipper.order_approved');
    });
    
    // Order received
    Route::group(['prefix' => 'order-received'], function () {
        Route::get('/', [OrderController::class, 'orders_received'])->name('shipper.order_received');
    });
});

// ----------- API -------------

// All user
Route::group(['prefix' => 'dashboard', 'middleware' => 'm_api_logged'], function () {
    // Info
    Route::group(['prefix' => 'info'], function () {
        Route::post('/update', [AccountController::class, 'API_change_info'])->name('dashboard.info.update');
    });

    // Change password
    Route::group(['prefix' => 'change-password'], function () {
        Route::post('/update', [AccountController::class, 'API_change_password'])->name('dashboard.change-password.update');
    });
});

// Guest
Route::group(['prefix' => 'dashboard', 'middleware' => 'm_api_guest'], function () {
    // Order
    Route::group(['prefix' => 'orders'], function () {
        Route::post('/', [OrderController::class, 'API_add_product_to_cart'])->name('dashboard.orders.add-product');
        Route::post('/update-count', [OrderController::class, 'API_update_count_product'])->name('dashboard.orders.update-count-product');
        Route::post('/delete', [OrderController::class, 'API_delete_product_in_cart'])->name('dashboard.orders.delete-product');
        Route::post('/apply-discount', [OrderController::class, 'API_apply_discount_to_cart'])->name('dashboard.orders.apply-discount');
        Route::post('/order-closing', [OrderController::class, 'API_order_closing'])->name('dashboard.orders.order_closing');
        Route::post('/detail', [OrderController::class, 'API_get_order_detail'])->name('guest.orders.get-order-detail');
        Route::post('/review', [OrderController::class, 'API_review_order'])->name('guest.orders.review');
        Route::post('/review/get', [OrderController::class, 'API_get_review_order'])->name('guest.orders.get-order-review');
        Route::post('/cancel', [OrderController::class, 'API_order_cancel'])->name('guest.orders.cancel');
        
        // VNPAY Routes
        Route::post('/vnpay-payment', [VNPayController::class, 'createPayment'])->name('dashboard.orders.vnpay_payment');
    });
});

// VNPAY Return URL - No authentication required for payment returns
Route::get('/vnpay-return', [VNPayController::class, 'paymentReturn'])->name('vnpay.return');

// Admin
Route::group(['prefix' => 'admin', 'middleware' => 'm_api_admin'], function () {
    // Account manager
    Route::group(['prefix' => 'account-manager'], function () {
        Route::post('/locked', [AccountController::class, 'API_change_status_locked'])->name('admin.account-manager.locked');
        Route::post('/create-shipper', [AccountController::class, 'API_create_account_shipper'])->name('admin.account-manager.create-shipper');
    });

    // Category manager
    Route::group(['prefix' => 'category-manager'], function () {
        Route::post('/', [CategoryController::class, 'API_get_category'])->name('admin.category_manager.get-category');
        Route::post('/create', [CategoryController::class, 'API_create_category'])->name('admin.category_manager.create-category');
        Route::post('/update', [CategoryController::class, 'API_update_category'])->name('admin.category_manager.update-category');
        Route::post('/delete', [CategoryController::class, 'API_delete_category'])->name('admin.category_manager.delete-category');
    });

    // Discount manager
    Route::group(['prefix' => 'discount-manager'], function () {
        Route::post('/', [DiscountController::class, 'API_get_discount'])->name('admin.discount_manager.get-discount');
        Route::post('/create', [DiscountController::class, 'API_create_discount'])->name('admin.discount_manager.create-discount');
        Route::post('/update', [DiscountController::class, 'API_update_discount'])->name('admin.discount_manager.update-discount');
        Route::post('/delete', [DiscountController::class, 'API_delete_discount'])->name('admin.discount_manager.delete-discount');
    });

    // Product manager
    Route::group(['prefix' => 'product-manager'], function () {
        Route::post('/', [ProductController::class, 'API_get_product'])->name('admin.product_manager.get-product');
        Route::post('/create', [ProductController::class, 'API_create_product'])->name('admin.product_manager.create-product');
        Route::post('/update', [ProductController::class, 'API_update_product'])->name('admin.product_manager.update-product');
        Route::post('/delete', [ProductController::class, 'API_delete_product'])->name('admin.product_manager.delete-product');
    });

    // Order manager
    Route::group(['prefix' => 'order-manager'], function () {
        Route::post('/detail', [OrderController::class, 'API_get_order_detail'])->name('admin.order_manager.get-order-detail');
        Route::post('/browsing', [OrderController::class, 'API_order_browsing'])->name('admin.order_manager.order-browsing');
        Route::post('/cancel', [OrderController::class, 'API_order_cancel'])->name('admin.order_manager.order-cancel');
    });
});

// Shipper
Route::group(['prefix' => 'shipper', 'middleware' => 'm_api_shipper'], function () {
    // Order approved
    Route::group(['prefix' => 'order-approved'], function () {
        Route::post('/detail', [OrderController::class, 'API_get_order_detail'])->name('shipper.order_approved.get-order-detail');
        Route::post('/receiving', [OrderController::class, 'API_order_receive'])->name('shipper.order_approved.receiving');
        Route::post('/complete', [OrderController::class, 'API_order_complete'])->name('shipper.order_approved.complete');
    });
    
    // Order received
    Route::group(['prefix' => 'order-received'], function () {
        Route::post('/detail', [OrderController::class, 'API_get_order_detail'])->name('shipper.order_received.get-order-detail');
    });
});