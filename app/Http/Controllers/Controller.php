<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use App\Models\AccountModel;
use App\Models\ProductModel;
use App\Models\CategoryModel;
use App\Models\DiscountModel;
use App\Models\OrderModel;
use App\Models\OrderProductModel;

use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Route;


class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public $accountModel, $productModel, $categoryModel, $discountModel, $orderModel, $orderProductModel;

    public function __construct()
    {
        $this->accountModel = new AccountModel();
        $this->productModel = new ProductModel();
        $this->categoryModel = new CategoryModel();
        $this->discountModel = new DiscountModel();
        $this->orderModel = new OrderModel();
        $this->orderProductModel = new OrderProductModel();
    }

    public function loadLayout(View $page, $data = [], bool $banner = true, $title = '')
    {
        $logged = Auth::check();

        $data = array_merge($data, [
            'SHOW_DETAIL' => "",
            'HEADER' => view('_layout.header', [
                'LOGO' => env('SHOP_LOGO'),
                'TITLE' => env('SHOP_TITLE'),
                'LOGGED' => $logged
            ]),
            'BANNER' => view('_layout.banner', [
                'CATEGORIES' => CategoryModel::all(),
            ]),
            'FOOTER' => view('_layout.footer', [
                'TITLE' => env('SHOP_TITLE'),
                'PHONE' => env('SHOP_PHONE'),
                'EMAIL' => env('SHOP_EMAIL'),
                'ADDRESS' => env('SHOP_ADDRESS'),
                'USE_BANNER' => $banner,
            ]),
            'HEAD' => view('_layout.head', [
                'TITLE' => env('SHOP_TITLE'),
                'FAVICON' => env('SHOP_FAVICON'),
            ]),
            'LOADING' => view('component.loading'),
            'USE_BANNER' => $banner,
            'PAGE' => $page
        ]);

        return view('_layout.home', $data);
    }

    public function loadLayoutUser(View $page, $data = [], $title = '')
    {
        $role = (string) Auth::user()->role;
        $features = $this->loadFeatureUrl($role);
        $routeName = Route::currentRouteName();
        $routeTitle = $this->getTitleFeatureByURI($routeName, $features);

        $data = array_merge($data, [
            'NAVBAR' => view('_layout.user.navbar', [
                'LOGO' => env('SHOP_LOGO'),
                'TITLE' => env('SHOP_TITLE'),
                'URI' => $routeName,
                'FEATURES' =>  json_decode(json_encode($features)), // convert array to object
            ]),
            'HEAD' => view('_layout.head', [
                'TITLE' => env('SHOP_TITLE'),
                'FAVICON' => env('SHOP_FAVICON'),
            ]),
            'LOADING' => view('component.loading'),
            'TITLE' => $routeTitle,
            'PAGE' => $page
        ]);

        return view('_layout.user.home', $data);
    }

    protected function loadFeatureUrl(string $role)
    {
        $feature = [];
        switch ($role) {
            case 'guest':
                $feature = [
                    'info' => [
                        'name' => 'Thông tin cá nhân',
                        'route' => 'dashboard.info',
                        'icon' => 'fa fa-user'
                    ],
                    'password' => [
                        'name' => 'Đổi mật khẩu',
                        'route' => 'dashboard.change-password',
                        'icon' => 'fa fa-key'
                    ],
                    'cart' => [
                        'name' => 'Giỏ hàng',
                        'route' => 'dashboard.orders',
                        'icon' => 'fa fa-shopping-cart'
                    ],
                    'history-order' => [
                        'name' => 'Lịch sử đặt hàng',
                        'route' => 'dashboard.orders.history',
                        'icon' => 'fa fa-history'
                    ],
                    'logout' => [
                        'name' => 'Đăng xuất',
                        'route' => 'logout',
                        'icon' => 'fa fa-sign-out'
                    ]
                ];
                break;
            case 'shipper':
                $feature = [
                    'info' => [
                        'name' => 'Thông tin cá nhân',
                        'route' => 'dashboard.info',
                        'icon' => 'fa fa-user'
                    ],
                    'password' => [
                        'name' => 'Đổi mật khẩu',
                        'route' => 'dashboard.change-password',
                        'icon' => 'fa fa-key'
                    ],
                    'order' => [
                        'name' => 'Đơn hàng mới',
                        'route' => 'shipper.order_approved',
                        'icon' => 'fa fa-shopping-cart'
                    ],
                    'order-received' => [
                        'name' => 'Đơn hàng đã nhận',
                        'route' => 'shipper.order_received',
                        'icon' => 'fa fa-shopping-cart'
                    ],
                    'logout' => [
                        'name' => 'Đăng xuất',
                        'route' => 'logout',
                        'icon' => 'fa fa-sign-out'
                    ]
                ];
                break;
            case 'admin':
                $feature = [
                    'info' => [
                        'name' => 'Thông tin cá nhân',
                        'route' => 'dashboard.info',
                        'icon' => 'fa fa-user'
                    ],
                    'password' => [
                        'name' => 'Đổi mật khẩu',
                        'route' => 'dashboard.change-password',
                        'icon' => 'fa fa-key'
                    ],
                    'statistical' => [
                        'name' => 'Thống kê',
                        'route' => 'admin.statistical',
                        'icon' => 'fa fa-bar-chart'
                    ],
                    'account-manager' => [
                        'name' => 'Quản lý tài khoản',
                        'route' => 'admin.account-manager',
                        'icon' => 'fa fa-users'
                    ],
                    'category-manager' => [
                        'name' => 'Quản lý danh mục',
                        'route' => 'admin.category_manager',
                        'icon' => 'fa fa-list'
                    ],
                    'product-manager' => [
                        'name' => 'Quản lý sản phẩm',
                        'route' => 'admin.product_manager',
                        'icon' => 'fa fa-product-hunt'
                    ],
                    'discount-manager' => [
                        'name' => 'Quản lý khuyến mãi',
                        'route' => 'admin.discount_manager',
                        'icon' => 'fa fa-gift'
                    ],
                    'order-manager' => [
                        'name' => 'Quản lý đơn hàng',
                        'route' => 'admin.order_manager',
                        'icon' => 'fa fa-shopping-cart'
                    ],
                    'logout' => [
                        'name' => 'Đăng xuất',
                        'route' => 'logout',
                        'icon' => 'fa fa-sign-out'
                    ]
                ];
                break;
            default:
                $feature = [
                    'logout' => [
                        'name' => 'Đăng xuất',
                        'route' => 'logout',
                        'icon' => 'fa fa-sign-out'
                    ]
                ];
                break;
            }
        return $feature;
    }

    protected function getTitleFeatureByURI(string $uri, $features)
    {
        $title = '';
        foreach ($features as $key => $value) {
            if ($value['route'] == $uri) {
                $title = $value['name'];
                break;
            }
        }
        return $title;
    }

    public static function res(bool $status, string $message = '', $data = [])
    {
        return response()->json([
            'status' => $status,
            'data' => $data,
            'message' => $message
        ]);
    }
}
