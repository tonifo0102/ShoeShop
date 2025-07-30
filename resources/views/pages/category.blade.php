<div class="page">
    <section>
        <div class="path">
            <a href="{{ route("home") }}">Trang chủ</a>
            @if ($CATEGORY)
                <i class="fa fa-angle-right"></i>
                @if ($CATEGORY->slug == '')
                    <a href="{{ route("category") }}" >{{ $CATEGORY->name }}</a>
                @else
                    <a href="{{ route("category.search", ['slug' => $CATEGORY->slug]) }}">{{ $CATEGORY->name }}</a>
                @endif
            @endif
        </div>
        <div class="carts-product">
            @if ($PRODUCTS == null || count($PRODUCTS) == 0)
                @if ($CATEGORY == null)
                    <p class="product-empty">Không tìm thấy danh mục này</p>
                @else
                    <p class="product-empty">Không tìm thấy sản phẩm nào</p>
                @endif
            @else
                @foreach ($PRODUCTS as $PRODUCT)
                    {{ view('component.cart', ['PRODUCT' => $PRODUCT]) }}
                @endforeach
            @endif
        </div>
    </section>
</div>
