<div class="shipping-info">
    <h3>Thông tin giao hàng</h3>
    <div class="form-group">
        <label for="shipping_address">Địa chỉ giao hàng:</label>
        <input type="text" id="shipping_address" name="shipping_address" class="input-text" value="{{ $USER_ADDRESS ?? '' }}" placeholder="Nhập địa chỉ giao hàng" required />
    </div>
    <div class="form-group">
        <label for="shipping_phone">Số điện thoại:</label>
        <input type="text" id="shipping_phone" name="shipping_phone" class="input-text" value="{{ $USER_PHONE ?? '' }}" placeholder="Nhập số điện thoại" required />
    </div>
</div>

<div class="payment-section">
    <h3>Phương thức thanh toán</h3>
    <div class="payment-options">
        <div class="payment-option">
            <input type="radio" id="pay_direct" name="pay_method" value="direct" checked>
            <label for="pay_direct">
                <div class="payment-icon"><i class="fa fa-money"></i></div>
                <div class="payment-details">
                    <div class="payment-title">Thanh toán khi nhận hàng</div>
                    <div class="payment-description">Trả tiền mặt khi giao hàng thành công</div>
                </div>
            </label>
        </div>
        <div class="payment-option">
            <input type="radio" id="pay_vnpay" name="pay_method" value="vnpay">
            <label for="pay_vnpay">
                <div class="payment-icon"><i class="fa fa-credit-card"></i></div>
                <div class="payment-details">
                    <div class="payment-title">Thanh toán qua VNPAY</div>
                    <div class="payment-description">Thanh toán an toàn qua cổng VNPAY</div>
                </div>
            </label>
        </div>
    </div>
</div>

<div id="vnpay-info" class="pay-info hide">
    <div class="payment-info-box">
        <p>Hệ thống sẽ chuyển hướng đến cổng thanh toán VNPAY.</p>
    </div>
</div>