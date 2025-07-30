<?php

namespace App\Http\Controllers;

use App\Models\OrderModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class VNPayController extends Controller
{
    public function createPayment(Request $request)
    {
        // Kiểm tra giỏ hàng có tồn tại hay không
        $order = OrderModel::where('account_id', Auth::user()->id)
            ->where('status', 'cart')
            ->first();

        if (!$order) {
            return $this->res(false, 'Chưa có sản phẩm trong giỏ hàng');
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

        // Get total amount from order
        $totalAmount = $request->amount;
        
        $vnp_TmnCode = env('VNPAY_TMN_CODE', ''); // Terminal ID from VNPAY
        $vnp_HashSecret = env('VNPAY_HASH_SECRET', ''); // Secret key from VNPAY
        $vnp_Url = env('VNPAY_URL', 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html');
        $vnp_Returnurl = route('vnpay.return');
        $vnp_TxnRef = $order->id . '_' . time(); // Order ID + timestamp
        $vnp_OrderInfo = 'Thanh toan don hang #' . $order->id;
        $vnp_OrderType = 'billpayment';
        $vnp_Amount = $totalAmount * 100; // VNPAY requires amount in VND * 100
        $vnp_Locale = 'vn';
        $vnp_IpAddr = request()->ip();
        
        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
        );

        // Update the order payment method and shipping info
        $order->payment_method = 'vnpay';
        $order->shipping_address = $request->shipping_address;
        $order->shipping_phone = $request->shipping_phone;
        $order->save();

        if (isset($vnp_HashSecret)) {
            ksort($inputData);
            $query = "";
            $i = 0;
            $hashdata = "";
            foreach ($inputData as $key => $value) {
                if ($i == 1) {
                    $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
                } else {
                    $hashdata .= urlencode($key) . "=" . urlencode($value);
                    $i = 1;
                }
                $query .= urlencode($key) . "=" . urlencode($value) . '&';
            }

            $vnp_Url = $vnp_Url . "?" . $query;
            if (isset($vnp_HashSecret)) {
                $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
                $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
            }
        }

        return $this->res(true, 'Chuyển hướng đến cổng thanh toán VNPAY', ['redirect_url' => $vnp_Url]);
    }

    public function paymentReturn(Request $request)
    {
        // Process the return from VNPAY
        if ($request->vnp_ResponseCode == '00') {
            // Payment successful
            $txnRef = explode('_', $request->vnp_TxnRef)[0];
            $order = OrderModel::find($txnRef);
            
            if ($order) {
                // Update order status
                $order->status = 'wait';
                $order->pay = 'paid';
                $order->updated_at = date('Y-m-d H:i:s');
                $order->save();
                
                return redirect()->route('dashboard.orders.history')->with('success', 'Thanh toán thành công.');
            }
        }
        
        return redirect()->route('dashboard.orders.show')->with('error', 'Thanh toán không thành công.');
    }
} 