@csrf
{!! $MODAL_ORDER_PAY !!}
<div class="box-dashboard">
    <table id="orders-guest" class="table-datatables ui celled table">
        <thead>
            <tr>
                <th>Hình ảnh</th>
                <th>Tên sản phẩm</th>
                <th>Giá</th>
                <th>Số lượng</th>
                <th>Thành tiền</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($PRODUCTS as $PRODUCT)
            <tr>
                <td>
                    <img src="{{ asset( 'Images/' .$PRODUCT->avatar) }}" alt="{{ $PRODUCT->name }}" />
                </td>
                <td>
                    {{ $PRODUCT->name }}
                </td>
                <td>{{ number_format($PRODUCT->price) }}đ</td>
                <td>
                    <div class="update-count">
                        <button id="down-count-product" class="btn btn-black" data-id="{{ $PRODUCT->product_id }}">-</button>
                        <div id="count-product" data-id="{{ $PRODUCT->product_id }}">{{ $PRODUCT->quantity }}</div>
                        <button id="up-count-product" class="btn btn-black" data-id="{{ $PRODUCT->product_id }}">+</button>
                    </div>
                </td>
                <td id="total_price" data-id="{{ $PRODUCT->product_id }}">{{ number_format($PRODUCT->price * $PRODUCT->quantity) }}đ</td>
                <td>
                    <button class="btn btn-red" id="btn-delete-product-in-cart" data-id="{{ $PRODUCT->product_id }}">Xóa</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="box-dashboard">
    <div class="row">
        <h4>Tổng giá:</h4>
        <i id="total_all_product">{{ number_format($TOTAL_ALL) }}đ</i>
    </div>
    <div class="row">
        <h4>Khuyến mại</h4>
        <div class="apply {{ $ORDER->discount_name == null ? 'hide' : '' }}">Bạn đã áp dụng khuyến mại "<strong>{{ $ORDER->discount_name == null ? '' : $ORDER->discount_name }}</strong>"</div>
        <div class="group">
            <input type="text" placeholder="Nhập mã giảm giá" class="input-text" id="discount-code"/>
            <button class="btn btn-black" id="btn-apply-discount" data-id="{{ $ORDER->id }}">Áp dụng</button>
        </div>
    </div>
    <div class="row">
        <h4>Thành tiền:</h4>
        <i id="into_money">{{ number_format($TOTAL_DISCOUNT) }}đ</i>
    </div>
    <div class="row">
        <div class="center"><button class="btn btn-green" id="btn-show-payment-options">Đặt hàng</button></div>
    </div>
</div>