<div class="page">
    @csrf
    <section>
        <div class="title"><span class="text-gradient">Sản phẩm mới</span></div>
        <div class="carts-product">
            @if (count($NEW_PRODUCTS) == 0)
                <p class="product-empty">Hiện tại chưa có sản phẩm nào</p>
            @else
                @foreach ($NEW_PRODUCTS as $PRODUCT)
                    {{ view('component.cart', ['PRODUCT' => $PRODUCT]) }}
                @endforeach
            @endif
        </div>
    </section>
    <section>
        <div class="title"><span class="text-gradient">Sản phẩm bán chạy</span></div>
        <div class="carts-product">
            @if (count($NEW_PRODUCTS) == 0)
                <p class="product-empty">Hiện tại chưa có sản phẩm nào</p>
            @else
                @foreach ($NEW_PRODUCTS as $PRODUCT)
                    {{ view('component.cart', ['PRODUCT' => $PRODUCT]) }}
                @endforeach
            @endif
        </div>
    </section>
    <section>
        <div class="title"><span class="text-gradient">Đánh giá khách hàng</span></div>
        <div class="review-customer">
            @if (count($REVIEWS) == 0)
                <p class="product-empty">Hiện tại chưa có đánh giá nào</p>
            @else
                @foreach ($REVIEWS as $REVIEW)
                    @if ($REVIEW->note != "")
                        <div class="item">
                            <img src="{{ asset('Images/avatar.jpg') }}" alt="">
                            <div class="content">
                                <div class="name">{{ $REVIEW->name }}</div>
                                <div class="comment">{{ $REVIEW->note }}</div>
                            </div>
                        </div>
                    @endif
                @endforeach
            @endif
        </div>
    </section>
</div>
