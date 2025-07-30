<div class="cart-product">
    <div class="image">
        <a href="{{ route('product.detail', ['slug' => $PRODUCT->slug]) }}">
            <img src="{{ asset('Images/' . $PRODUCT->avatar) }}" alt="{{ $PRODUCT->name }}">
        </a>
    </div>
    <div class="content">
        <a href="{{ route('product.detail', ['slug' => $PRODUCT->slug]) }}" class="title">{{ $PRODUCT->name }}</a>

        <div class="rating">
            @if ($PRODUCT->total != 0)
            <ion-icon name="star"></ion-icon>
            <strong>{{ number_format($PRODUCT->ratio, 1) }}</strong>
            <span>({{ $PRODUCT->total }} đánh giá)</span>
            @else
            <ion-icon name="star-outline"></ion-icon>
            <span>Chưa có đánh giá</span>
            @endif
        </div>

        <div class="price">
            <span>{{ $PRODUCT->priceFormatted }}đ</span>
        </div>
    </div>

    <div class="add-to-cart" id="btn-add-product-to-cart" data-id="{{ $PRODUCT->id }}">
        <span>&#43;</span>
    </div>
</div>