<?php

namespace App\Http\Controllers;

use App\Models\DiscountModel;
use App\Models\OrderModel;
use App\Models\OrderProductModel;
use App\Models\ProductModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function orders_show()
    {
        $order = OrderModel::where('account_id', Auth::user()->id)
                            ->where('status', 'cart')
                            ->first();

        $products = [];
        if ($order) {
            $products = OrderProductModel::where('order_id', $order->id)->get();
        }

        // Nếu không tìm thấy giỏ hàng, gán giỏ hàng là 1 object rỗng
        if (!$order) {
            $order = new OrderModel();
        }

        $totalAll = 0;
        foreach ($products as $product) {
            $totalAll += $product->price * $product->quantity;
        }

        $total_discount = $totalAll;
        if ($order->discount_id) {
            $discount = DiscountModel::find($order->discount_id);

            // Tính tiền giảm giá
            $total_discount = $totalAll * $discount->percent / 100;
            $total_discount = $totalAll - $total_discount;

            // Bỏ số thập phân
            $total_discount = floor($total_discount);
        }

        $page = view('guest.orders', [
            'ORDER' => $order,
            'PRODUCTS' => $products,
            'TOTAL_ALL' => $totalAll,
            'TOTAL_DISCOUNT' => $total_discount,
            'MODAL_ORDER_PAY' => view('component.modal',[
                'MODAL' => 'modal-order-pay',
                'TITLE' => 'Thanh toán',
                'BODY' => view('guest.pay', [
                    'SHOP_PAYMENT_CARD' => env('SHOP_PAYMENT_CARD'),
                    'SHOP_PAYMENT_NAME' => env('SHOP_PAYMENT_NAME'),
                    'SHOP_PAYMENT_BANK' => env('SHOP_PAYMENT_BANK'),
                    'SHOP_PAYMENT_BRANCH' => env('SHOP_PAYMENT_BRANCH'),
                    'SHOP_PAYMENT_CONTENT' => env('SHOP_PAYMENT_CONTENT'),
                    'ORDER_ID' => $order->id,
                    'USER_ADDRESS' => Auth::user()->address,
                    'USER_PHONE' => Auth::user()->phone,
                ]),
                'FOOTER' => [
                    [
                        'class' => 'btn btn-green',
                        'text' => 'Thanh toán',
                        'attr' => 'id=btn-pay'
                    ],
                ]
            ]),
        ]);
        return $this->loadLayoutUser($page,[], false);
    }

    public function orders_manager()
    {
        $orders = OrderModel::orderByRaw("CASE 
            WHEN status = 'wait' THEN 1 
            WHEN status = 'shipping' THEN 2
            WHEN status = 'complete' THEN 3
            WHEN status = 'cancel' THEN 4
            ELSE 5 END")
            ->orderBy('updated_at', 'desc')
            ->get();

        // Lấy tổng giá tất cả sản phẩm trong giỏ hàng
        foreach ($orders as $order) {
            $totalAll = 0;
            $count = 0;
            $productsInCart = OrderProductModel::where('order_id', $order->id)->get();
            foreach ($productsInCart as $product) {
                $totalAll += $product->price * $product->quantity;
                $count += $product->quantity;
            }

            $total_discount = $totalAll;
            if ($order->discount_id) {
                $discount = DiscountModel::find($order->discount_id);

                // Tính tiền giảm giá
                $total_discount = $totalAll * $discount->percent / 100;
                $total_discount = $totalAll - $total_discount;

                // Bỏ số thập phân
                $total_discount = floor($total_discount);
            }

            $order->total_all = $totalAll;
            $order->total_discount = $total_discount;
            $order->count = $count;
        }

        $page = view('admin.order-manager', [
            'ORDERS' => $orders,
            'MODAL_ORDER_DETAIL' => view('component.modal',[
                'MODAL' => 'modal-order-detail',
                'TITLE' => 'Chi tiết đơn hàng',
                'BODY' => view('admin.order-detail'),
                'FOOTER' => [

                ]
            ])
        ]);
        return $this->loadLayoutUser($page,[], false);
    }

    public function orders_approved()
    {
        $orders = OrderModel::where('status', 'shipping')
                            ->where('shipper_id', null)
                            ->get();

        // Lấy tổng giá tất cả sản phẩm trong giỏ hàng
        foreach ($orders as $order) {
            $totalAll = 0;
            $count = 0;
            $productsInCart = OrderProductModel::where('order_id', $order->id)->get();
            foreach ($productsInCart as $product) {
                $totalAll += $product->price * $product->quantity;
                $count += $product->quantity;
            }

            $total_discount = $totalAll;
            if ($order->discount_id) {
                $discount = DiscountModel::find($order->discount_id);

                // Tính tiền giảm giá
                $total_discount = $totalAll * $discount->percent / 100;
                $total_discount = $totalAll - $total_discount;

                // Bỏ số thập phân
                $total_discount = floor($total_discount);
            }

            $order->total_all = $totalAll;
            $order->total_discount = $total_discount;
            $order->count = $count;
        }

        // Lấy đơn hàng đã nhận
        $ordersReceived = OrderModel::where('status', 'shipping')
                                    ->where('shipper_id', Auth::user()->id)
                                    ->first();

        $productsReceived = [];
        $total = 0;

        if ($ordersReceived) {
            $products = OrderProductModel::where('order_id', $ordersReceived->id)->get();

            foreach ($products as $product) {
                $productsReceived[] = $product;
                $total_temp = $product->price * $product->quantity;
                if ($ordersReceived->discount_id) {
                    $discount = DiscountModel::find($ordersReceived->discount_id);
    
                    // Tính tiền giảm giá
                    $total_temp = $total_temp * (100 - $discount->percent) / 100;
    
                    // Bỏ số thập phân
                    $total_temp = floor($total_temp);
                }
                $total += $total_temp;
            }
        }

        $page = view('shipper.order-approved', [
            'ORDERS' => $orders,
            'MODAL_ORDER_DETAIL' => view('component.modal',[
                'MODAL' => 'modal-order-detail',
                'TITLE' => 'Chi tiết đơn hàng',
                'BODY' => view('admin.order-detail'),
                'FOOTER' => [

                ]
            ]),
            'PRODUCTS_RECEIVED' => $productsReceived,
            'ORDER_RECEIVED' => $ordersReceived,
            'TOTAL' => $total,
        ]);
        return $this->loadLayoutUser($page,[], false);
    }

    public function orders_history()
    {
        $orders = OrderModel::where('status', '!=', 'cart')
                            ->where('account_id', Auth::user()->id)
                            ->orderBy('updated_at', 'desc')
                            ->get();

        // Lấy tổng giá tất cả sản phẩm trong giỏ hàng
        foreach ($orders as $order) {
            $totalAll = 0;
            $count = 0;
            $productsInCart = OrderProductModel::where('order_id', $order->id)->get();
            foreach ($productsInCart as $product) {
                $totalAll += $product->price * $product->quantity;
                $count += $product->quantity;
            }

            $total_discount = $totalAll;
            if ($order->discount_id) {
                $discount = DiscountModel::find($order->discount_id);

                // Tính tiền giảm giá
                $total_discount = $totalAll * $discount->percent / 100;
                $total_discount = $totalAll - $total_discount;

                // Bỏ số thập phân
                $total_discount = floor($total_discount);
            }

            $order->total_all = $totalAll;
            $order->total_discount = $total_discount;
            $order->count = $count;
            $order->status = $this->convertStatusOrder($order->status, $order->shipper_id);
            $order->delivery_time = $this->deliveryTime($order->updated_at);
        }

        $page = view('guest.history', [
            'ORDERS' => $orders,
            'MODAL_ORDER_DETAIL' => view('component.modal',[
                'MODAL' => 'modal-order-detail',
                'TITLE' => 'Chi tiết đơn hàng',
                'BODY' => view('admin.order-detail'),
                'FOOTER' => [
                ]
            ]),
            'MODAL_ORDER_REVIEW' => view('component.modal',[
                'MODAL' => 'modal-order-review',
                'TITLE' => 'Đánh giá đơn hàng',
                'BODY' => view('guest.reviews'),
                'FOOTER' => [
                    [
                        'class' => 'btn btn-green',
                        'text' => 'Đánh giá',
                        'attr' => 'id=btn-review'
                    ],
                ]
            ]),
        ]);
        return $this->loadLayoutUser($page,[], false);
    }

    public function API_add_product_to_cart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'integer|min:1',
        ], [
            'product_id.required' => 'Vui lòng chọn sản phẩm',
            'product_id.integer' => 'Sản phẩm không hợp lệ',
            'product_id.exists' => 'Sản phẩm không tồn tại',
            'quantity.integer' => 'Số lượng không hợp lệ',
            'quantity.min' => 'Số lượng phải lớn hơn 0',
        ]);

        if ($validator->fails()) {
            return $this->res(false, $validator->errors()->first());
        }

        $product = ProductModel::find($request->product_id);

        // Check product is exist in cart
        $order = OrderModel::where('account_id', Auth::user()->id)
                            ->where('status', 'cart')
                            ->first();
        
        // Create new order if not exist
        if (!$order) {
            $order = new OrderModel();
            $order->account_id = Auth::user()->id;
            $order->status = 'cart';
            $order->save();
        }
        else {
            // Check product is exist in cart
            $productInCart = OrderProductModel::where('order_id', $order->id)
                                                ->where('product_id', $product->id)
                                                ->first();
            if ($productInCart) {
                return $this->res(false, 'Sản phẩm đã có trong giỏ hàng');
            }
        }

        // Add product to cart
        $orderProduct = new OrderProductModel();
        $orderProduct->order_id = $order->id;
        $orderProduct->product_id = $product->id;
        $orderProduct->name = $product->name;
        $orderProduct->description = $product->description;
        $orderProduct->price = $product->price;
        $orderProduct->avatar = $product->avatar;
        $orderProduct->quantity = $request->quantity ?? 1; // Sử dụng số lượng từ request hoặc mặc định là 1
        $orderProduct->save();

        // Lấy số lượng sản phẩm trong giỏ hàng
        $count = OrderProductModel::where('order_id', $order->id)->count();

        return $this->res(true, 'Thêm sản phẩm vào giỏ hàng thành công', [
            'count' => $count
        ]);
    }

    public function API_update_count_product(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|integer|exists:products,id',
            'count' => 'required|integer|min:1',
        ], [
            'product_id.required' => 'Vui lòng chọn sản phẩm',
            'product_id.integer' => 'Sản phẩm không hợp lệ',
            'product_id.exists' => 'Sản phẩm không tồn tại',
            'count.required' => 'Vui lòng nhập số lượng',
            'count.integer' => 'Số lượng không hợp lệ',
            'count.min' => 'Số lượng không hợp lệ',
        ]);

        if ($validator->fails()) {
            return $this->res(false, $validator->errors()->first());
        }

        // Check product is exist in cart
        $order = OrderModel::where('account_id', Auth::user()->id)
                            ->where('status', 'cart')
                            ->first();

        if (!$order) {
            return $this->res(false, 'Chưa có sản phẩm trong giỏ hàng');
        }

        $productInCart = OrderProductModel::where('order_id', $order->id)
                                            ->where('product_id', $request->product_id)
                                            ->first();

        if (!$productInCart) {
            return $this->res(false, 'Sản phẩm này không có trong giỏ hàng');
        }

        $productInCart->quantity = $request->count;
        $productInCart->save();

        // Get total price
        $totalPrice = $productInCart->price * $productInCart->quantity;

        // Lấy tổng giá tất cả sản phẩm trong giỏ hàng

        $totalAll = 0;
        $productsInCart = OrderProductModel::where('order_id', $order->id)->get();
        foreach ($productsInCart as $product) {
            $totalAll += $product->price * $product->quantity;
        }

        $total_discount = $totalAll;

        // Kiểm tra có mã giảm giá hay không
        if ($order->discount_id) {
            $discount = DiscountModel::find($order->discount_id);

            // Tính tiền giảm giá
            $total_discount = $totalAll * $discount->percent / 100;
            $total_discount = $totalAll - $total_discount;

            // Bỏ số thập phân
            $total_discount = floor($total_discount);
        }

        return $this->res(true, 'Cập nhật số lượng thành công', [
            'total_price' => $totalPrice,
            'total_all' => $totalAll,
            'total_discount' => $total_discount,
        ]);
    }

    public function API_delete_product_in_cart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|integer|exists:products,id',
        ], [
            'product_id.required' => 'Vui lòng chọn sản phẩm',
            'product_id.integer' => 'Sản phẩm không hợp lệ',
            'product_id.exists' => 'Sản phẩm không tồn tại',
        ]);

        if ($validator->fails()) {
            return $this->res(false, $validator->errors()->first());
        }

        // Check product is exist in cart
        $order = OrderModel::where('account_id', Auth::user()->id)
                            ->where('status', 'cart')
                            ->first();

        if (!$order) {
            return $this->res(false, 'Chưa có sản phẩm trong giỏ hàng');
        }

        $productInCart = OrderProductModel::where('order_id', $order->id)
                                            ->where('product_id', $request->product_id)
                                            ->first();

        if (!$productInCart) {
            return $this->res(false, 'Sản phẩm này không có trong giỏ hàng');
        }

        $productInCart->delete();

        // If cart is empty, delete cart
        $productsInCart = OrderProductModel::where('order_id', $order->id)->get();
        if (count($productsInCart) == 0) {
            $order->delete();
        }

        // Get total price
        $totalPrice = 0;
        foreach ($productsInCart as $product) {
            $totalPrice += $product->price * $product->quantity;
        }

        // Nếu đang áp dụng mã giảm giá, tính lại giá
        $total_discount = $totalPrice;
        if (count($productsInCart) != 0 && $order->discount_id) {
            $discount = DiscountModel::find($order->discount_id);

            // Tính tiền giảm giá
            $total_discount = $totalPrice * $discount->percent / 100;
            $total_discount = $totalPrice - $total_discount;

            // Bỏ số thập phân
            $total_discount = floor($total_discount);
        }

        return $this->res(true, 'Xóa sản phẩm thành công', [
            'total_all' => $totalPrice,
            'total_discount' => $total_discount,
            'is_empty' => count($productsInCart) == 0 ? true : false,
        ]);
    }

    public function API_apply_discount_to_cart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|exists:discounts,code',
        ], [
            'code.required' => 'Vui lòng nhập mã giảm giá',
            'code.string' => 'Mã giảm giá không hợp lệ',
            'code.exists' => 'Mã giảm giá không tồn tại',
        ]);

        if ($validator->fails()) {
            return $this->res(false, $validator->errors()->first());
        }

        $discount = DiscountModel::where('code', $request->code)->first();

        // Kiểm tra mã giảm giá có hợp lệ hay không
        if (!$discount) {
            return $this->res(false, 'Mã giảm giá không tồn tại');
        }

        // Kiểm tra mã giảm giá tới thời gian được áp dụng chưa
        if (strtotime($discount->start_at) > time()) {
            return $this->res(false, 'Mã giảm giá chưa tới thởi gian áp dụng');
        }

        // Kiểm tra mã giảm giá hết hạn chưa
        if (strtotime($discount->end_at) < time()) {
            return $this->res(false, 'Mã giảm giá đã hết hạn');
        }

        // Check product is exist in cart
        $order = OrderModel::where('account_id', Auth::user()->id)
                            ->where('status', 'cart')
                            ->first();

        if (!$order) {
            return $this->res(false, 'Chưa có sản phẩm trong giỏ hàng');
        }

        $discount = DiscountModel::where('code', $request->code)->first();

        if (!$discount) {
            return $this->res(false, 'Mã giảm giá không tồn tại');
        }

        $order->discount_id = $discount->id;
        $order->discount_name = $discount->name;
        $order->discount_percent = $discount->percent;
        $order->discount_code = $discount->code;
        $order->updated_at = date('Y-m-d H:i:s');
        $order->save();

        // Lấy tổng giá
        $totalPrice = 0;
        $productsInCart = OrderProductModel::where('order_id', $order->id)->get();
        foreach ($productsInCart as $product) {
            $totalPrice += $product->price * $product->quantity;
        }

        // Tính tiền giảm giá
        $discountPrice = $totalPrice * $discount->percent / 100;

        // Bỏ số thập phân
        $discountPrice = floor($discountPrice);

        return $this->res(true, 'Áp dụng mã giảm giá thành công', [
            'name' => $discount->name,
            'total_price' => $totalPrice - $discountPrice,
        ]);
    }

    public function API_order_closing(Request $request)
    {
        // Kiểm tra giỏ hàng có tồn tại hay không
        $order = OrderModel::where('account_id', Auth::user()->id)
                            ->where('status', 'cart')
                            ->first();

        if (!$order) {
            return $this->res(false, 'Chưa có sản phẩm trong giỏ hàng');
        }

        // Kiểm tra giỏ hàng có sản phẩm hay không
        $productsInCart = OrderProductModel::where('order_id', $order->id)->get();
        if (count($productsInCart) == 0) {
            return $this->res(false, 'Chưa có sản phẩm trong giỏ hàng');
        }

        // Kiểm tra có đơn hàng nào đang chờ xử lý hay không
        $orderWait = OrderModel::where('account_id', Auth::user()->id)
                            ->where('status', 'wait')
                            ->first();

        if ($orderWait) {
            return $this->res(false, 'Bạn đang có đơn hàng đang chờ xử lý');
        }

        // Validate shipping information
        $validator = Validator::make($request->all(), [
            'shipping_address' => 'required|string|max:255',
            'shipping_phone' => 'required|string|max:20',
        ], [
            'shipping_address.required' => 'Vui lòng nhập địa chỉ giao hàng',
            'shipping_phone.required' => 'Vui lòng nhập số điện thoại',
        ]);

        if ($validator->fails()) {
            return $this->res(false, $validator->errors()->first());
        }

        // Check payment method
        $paymentMethod = $request->payment_method ?? 'direct';
        
        // Save shipping information
        $order->shipping_address = $request->shipping_address;
        $order->shipping_phone = $request->shipping_phone;
        
        // If payment is direct, update order status
        if ($paymentMethod === 'direct') {
            // Chuyển giỏ hàng thành đơn hàng chờ xử lý
            $order->status = 'wait';
            $order->updated_at = date('Y-m-d H:i:s');
            $order->pay = 'unpaid';
            $order->payment_method = $paymentMethod;
            $order->save();

            return $this->res(true, 'Đặt hàng thành công');
        }
        
        // If payment is VNPAY, save payment method and shipping info
        if ($paymentMethod === 'vnpay') {
            $order->payment_method = $paymentMethod;
            $order->save();
            return $this->res(true, 'Chuyển hướng sang cổng thanh toán VNPAY');
        }
        
        // If payment is not valid, return error
        return $this->res(false, 'Phương thức thanh toán không hợp lệ');
    }

    public function API_get_order_detail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|integer|exists:orders,id',
        ], [
            'order_id.required' => 'Vui lòng chọn đơn hàng',
            'order_id.integer' => 'Đơn hàng không hợp lệ',
            'order_id.exists' => 'Đơn hàng không tồn tại',
        ]);

        if ($validator->fails()) {
            return $this->res(false, $validator->errors()->first());
        }


        $order = OrderModel::find($request->order_id);

        // Lấy danh sách sản phẩm trong đơn hàng
        $products = OrderProductModel::where('order_id', $request->order_id)->get();

        // Tính tổng giá nếu có mã giảm giá
        foreach ($products as $product) {
            $product->total_price = $product->price * $product->quantity;
            $total_discount = $product->total_price;

            if ($order->discount_id) {
                $discount = DiscountModel::find($order->discount_id);

                // Tính tiền giảm giá
                $total_discount = $product->total_price * $discount->percent / 100;
                $total_discount = $product->total_price - $total_discount;

                // Bỏ số thập phân
                $total_discount = floor($total_discount);
            }

            $product->total_discount = $total_discount;
        }

        return $this->res(true, 'Lấy danh sách sản phẩm thành công', [
            'products' => $products,
            'shipping_address' => $order->shipping_address,
            'shipping_phone' => $order->shipping_phone
        ]);
    }

    public function API_get_review_order(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|integer|exists:orders,id',
        ], [
            'order_id.required' => 'Vui lòng chọn đơn hàng',
            'order_id.integer' => 'Đơn hàng không hợp lệ',
            'order_id.exists' => 'Đơn hàng không tồn tại',
        ]);

        if ($validator->fails()) {
            return $this->res(false, $validator->errors()->first());
        }

        // Tìm các sản phẩm trong giỏ hàng
        $products = OrderProductModel::where('order_id', $request->order_id)->get();

        // Lấy note
        $order = OrderModel::find($request->order_id);

        // Trả về danh sách sản phẩm
        return $this->res(true, 'Lấy danh sách sản phẩm thành công', [
            'products' => $products,
            'note' => $order->note
        ]);
    }

    public function API_review_order(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|integer|exists:orders,id',
            'note' => 'max:500'
        ], [
            'order_id.required' => 'Vui lòng chọn đơn hàng',
            'order_id.integer' => 'Đơn hàng không hợp lệ',
            'order_id.exists' => 'Đơn hàng không tồn tại',
            'note.max' => 'Đánh giá tối đa 500 ký tự'
        ]);

        if ($validator->fails()) {
            return $this->res(false, $validator->errors()->first());
        }

        // Lấy danh sách sản phẩm đã đánh giá
        $datas = $request->data;

        // Duyệt qua từng sản phẩm, kiểm tra xem có trong giỏ hàng hay không
        foreach ($datas as $data) {
            $product = OrderProductModel::where('order_id', $request->order_id)
                                        ->where('product_id', $data['id'])
                                        ->first();

            if (!$product) {
                return $this->res(false, "Sản phẩm '". $data['name'] ."' không có trong đơn hàng");
            }

            // Kiểm tra vote có hợp lệ hay không
            if ($data['vote'] < 1 || $data['vote'] > 5) {
                return $this->res(false, 'Đánh giá không hợp lệ');
            }

            $product->vote = $data['vote'];
            $product->save();
        }

        $order = OrderModel::find($request->order_id);
        $order->note = $request->note;
        $order->save();

        // Trả về thông báo
        return $this->res(true, 'Đánh giá đơn hàng thành công');
    }

    public function API_order_browsing(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|integer|exists:orders,id',
        ], [
            'order_id.required' => 'Vui lòng chọn đơn hàng',
            'order_id.integer' => 'Đơn hàng không hợp lệ',
            'order_id.exists' => 'Đơn hàng không tồn tại',
        ]);

        if ($validator->fails()) {
            return $this->res(false, $validator->errors()->first());
        }

        // Kiểm tra trạng thái đơn hàng
        $order = OrderModel::find($request->order_id);
        if ($order->status != 'wait') {
            return $this->res(false, 'Đơn hàng không hợp lệ');
        }

        // Chuyển trạng thái đơn hàng thành đang giao hàng
        $order->status = 'shipping';
        $order->updated_at = date('Y-m-d H:i:s');
        $order->save();

        return $this->res(true, 'Đã duyệt đơn hàng');
    }

    public function API_order_receive(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|integer|exists:orders,id',
        ], [
            'order_id.required' => 'Vui lòng chọn đơn hàng',
            'order_id.integer' => 'Đơn hàng không hợp lệ',
            'order_id.exists' => 'Đơn hàng không tồn tại',
        ]);

        if ($validator->fails()) {
            return $this->res(false, $validator->errors()->first());
        }

        // Kiểm tra trạng thái đơn hàng
        $order = OrderModel::find($request->order_id);
        if ($order->status != 'shipping') {
            return $this->res(false, 'Đơn hàng không hợp lệ');
        }

        if ($order->shipper_id != null && $order->shipper_id != Auth::user()->id) {
            return $this->res(false, 'Đơn hàng đã được nhận bởi shipper khác');
        }

        if ($order->shipper_id != null) {
            return $this->res(false, 'Bạn đã nhận đơn hàng này rồi');
        }

        $order->shipper_id = Auth::user()->id;
        $order->updated_at = date('Y-m-d H:i:s');
        $order->save();

        return $this->res(true, 'Đã nhận đơn hàng');
    }

    public function API_order_complete(Request $request)
    {
        // Lấy đơn hàng đang giao
        $order = OrderModel::where('shipper_id', Auth::user()->id)
                            ->where('status', 'shipping')
                            ->first();

        if (!$order) {
            return $this->res(false, 'Bạn không có đơn hàng nào đang giao');
        }

        $order->status = 'complete';
        $order->updated_at = date('Y-m-d H:i:s');
        $order->save();

        return $this->res(true, 'Xác nhận đơn hàng thành công');
    }

    public function API_order_cancel(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|integer|exists:orders,id',
        ], [
            'order_id.required' => 'Vui lòng chọn đơn hàng',
            'order_id.integer' => 'Đơn hàng không hợp lệ',
            'order_id.exists' => 'Đơn hàng không tồn tại',
        ]);

        if ($validator->fails()) {
            return $this->res(false, $validator->errors()->first());
        }

        // Kiểm tra trạng thái đơn hàng
        $order = OrderModel::find($request->order_id);
        if ($order->status != 'wait') {
            return $this->res(false, 'Đơn hàng đã được duyệt, không thể hủy');
        }

        // Chuyển trạng thái đơn hàng thành đã hủy
        $order->status = 'cancel';
        $order->updated_at = date('Y-m-d H:i:s');
        $order->save();

        return $this->res(true, 'Đã hủy đơn hàng');
    }

    public function orders_received()
    {
        // Lấy tất cả đơn hàng đã nhận bởi shipper hiện tại, bao gồm cả đơn hàng đang giao và đã hoàn thành
        $orders = OrderModel::whereIn('status', ['shipping', 'complete'])
                            ->where('shipper_id', Auth::user()->id)
                            ->orderBy('status')
                            ->orderBy('updated_at', 'desc')
                            ->get();

        // Tính tổng giá và số lượng sản phẩm cho mỗi đơn hàng
        foreach ($orders as $order) {
            $totalAll = 0;
            $count = 0;
            $productsInCart = OrderProductModel::where('order_id', $order->id)->get();
            
            foreach ($productsInCart as $product) {
                $totalAll += $product->price * $product->quantity;
                $count += $product->quantity;
            }

            $total_discount = $totalAll;
            if ($order->discount_id) {
                $discount = DiscountModel::find($order->discount_id);
                $total_discount = $totalAll * (100 - $discount->percent) / 100;
                $total_discount = floor($total_discount);
            }

            $order->total_all = $totalAll;
            $order->total_discount = $total_discount;
            $order->count = $count;
        }

        $page = view('shipper.order-received', [
            'ORDERS' => $orders,
            'MODAL_ORDER_DETAIL' => view('component.modal',[
                'MODAL' => 'modal-order-detail',
                'TITLE' => 'Chi tiết đơn hàng',
                'BODY' => view('admin.order-detail'),
                'FOOTER' => []
            ])
        ]);
        
        return $this->loadLayoutUser($page, [], false);
    }

    protected function convertStatusOrder($status, $shipper_id): string
    {
        if ($status == 'wait')
            return 'Chờ xử lý';
        if ($status == 'shipping' && $shipper_id == null)
            return 'Đang tìm kiếm shipper';
        if ($status == 'shipping' && $shipper_id != null)
            return 'Đang giao hàng';
        if ($status == 'complete')
            return 'Đã hoàn thành';
        if ($status == 'cancel')
            return 'Đã hủy';
            
        return 'Không xác định'; // Default return for any unhandled status
    }

    protected function deliveryTime($time): int
    {
        $time = strtotime($time) + 30 * 60;

        $currentTime = time();
        $remaining_minutes = floor(($time - $currentTime) / 60);

        if ($remaining_minutes < 0) {
            return 0;
        }
        return $remaining_minutes;
    }
}
