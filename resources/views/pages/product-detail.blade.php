<div class="page product-detail">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="breadcrumb">
        <a href="{{ route('home') }}">Trang chủ</a>
        <span>/</span>
        <a href="{{ route('category.search', ['slug' => $CATEGORY->slug]) }}">{{ $CATEGORY->name }}</a>
        <span>/</span>
        <span>{{ $PRODUCT->name }}</span>
    </div>
    
    <div class="product-container">
        <div class="product-image">
            <img src="{{ asset('Images/' . $PRODUCT->avatar) }}" alt="{{ $PRODUCT->name }}">
        </div>
        
        <div class="product-info">
            <h1 class="product-name">{{ $PRODUCT->name }}</h1>
            
            <div class="product-meta">
                <div class="product-category">
                    <span>Danh mục:</span>
                    <a href="{{ route('category.search', ['slug' => $CATEGORY->slug]) }}">{{ $CATEGORY->name }}</a>
                </div>
                
                <div class="product-vote">
                    @if ($PRODUCT->total != 0)
                        <span class="vote-count">{{ $PRODUCT->ratio }}</span>
                        <ion-icon name="star-outline"></ion-icon>
                        <span class="vote-total">({{ $PRODUCT->total }} đánh giá)</span>
                    @else
                        <span>Chưa có đánh giá</span>
                    @endif
                </div>
            </div>
            
            <div class="product-price">
                <span>{{ $PRODUCT->priceFormatted }}đ</span>
            </div>
            
            <div class="product-description">
                <h3>Mô tả sản phẩm</h3>
                <div class="description-content">
                    {!! nl2br(e($PRODUCT->description)) !!}
                </div>
            </div>
            
            <div class="product-actions">
                <div class="quantity-control">
                    <button class="btn-decrease">-</button>
                    <input type="number" id="product-quantity" value="1" min="1" max="10">
                    <button class="btn-increase">+</button>
                </div>
                
                <button class="btn btn-blue btn-add-to-cart" id="btn-add-to-cart" data-id="{{ $PRODUCT->id }}">
                    <ion-icon name="cart-outline"></ion-icon>
                    Thêm vào giỏ hàng
                </button>
            </div>
        </div>
    </div>
    
    @if(count($REVIEWS) > 0)
    <div class="product-reviews">
        <h2>Đánh giá từ khách hàng</h2>
        
        <div class="reviews-list">
            @foreach($REVIEWS as $REVIEW)
                <div class="review-item">
                    <div class="review-header">
                        <div class="review-user">
                            <img src="{{ asset('Images/avatar.jpg') }}" alt="User Avatar">
                            <div class="review-user-info">
                                <span class="review-user-name">
                                    @if(!empty($REVIEW->order) && !empty($REVIEW->order->account))
                                        {{ $REVIEW->order->account->name }}
                                    @else
                                        Khách hàng
                                    @endif
                                </span>
                                <div class="review-rating">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $REVIEW->vote)
                                            <ion-icon name="star"></ion-icon>
                                        @else
                                            <ion-icon name="star-outline"></ion-icon>
                                        @endif
                                    @endfor
                                </div>
                            </div>
                        </div>
                        <div class="review-date">
                            {{ date('d/m/Y', strtotime($REVIEW->updated_at)) }}
                        </div>
                    </div>
                    
                    @if(!empty($REVIEW->note))
                    <div class="review-content">
                        {{ $REVIEW->note }}
                    </div>
                    @elseif(!empty($REVIEW->order) && !empty($REVIEW->order->note))
                    <div class="review-content">
                        {{ $REVIEW->order->note }}
                    </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
    @endif
    
    @if(count($RELATED_PRODUCTS) > 0)
    <div class="related-products">
        <h2>Sản phẩm tương tự</h2>
        
        <div class="carts-product">
            @foreach($RELATED_PRODUCTS as $PRODUCT)
                {{ view('component.cart', ['PRODUCT' => $PRODUCT]) }}
            @endforeach
        </div>
    </div>
    @endif
</div>

<script>
    // Define loading functions that might not be available
    function openLoading() {
        $(".loading").removeClass("hide");
    }

    function closeLoading() {
        $(".loading").addClass("hide");
    }
    
    // Get baseURL from the base tag
    const baseURLElement = document.querySelector('base');
    const baseURL = baseURLElement ? baseURLElement.href : "{{ url('/') }}/";
    
    document.addEventListener('DOMContentLoaded', function() {
        // Quantity control
        const decreaseBtn = document.querySelector('.btn-decrease');
        const increaseBtn = document.querySelector('.btn-increase');
        const quantityInput = document.querySelector('#product-quantity');
        
        decreaseBtn.addEventListener('click', function() {
            let currentValue = parseInt(quantityInput.value);
            if (currentValue > 1) {
                quantityInput.value = currentValue - 1;
            }
        });
        
        increaseBtn.addEventListener('click', function() {
            let currentValue = parseInt(quantityInput.value);
            if (currentValue < 10) {
                quantityInput.value = currentValue + 1;
            }
        });
        
        // Add to cart with quantity
        const addToCartBtn = document.querySelector('#btn-add-to-cart');
        
        addToCartBtn.addEventListener('click', function() {
            const productId = this.getAttribute('data-id');
            const quantity = parseInt(quantityInput.value);
            
            // Thực hiện Ajax để thêm vào giỏ hàng
            $.ajax({
                url: baseURL + "dashboard/orders",
                type: "POST",
                data: {
                    product_id: productId,
                    quantity: quantity
                },
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function() {
                    openLoading();
                },
                success: function (response) {
                    closeLoading();
                    if (!response.status) {
                        swal({
                            title: "Thông báo",
                            text: response.message,
                            icon: "warning",
                            buttons: {
                                confirm: "OK"
                            }
                        });
                        return;
                    }

                    swal({
                        title: "Thông báo",
                        text: response.message,
                        icon: "success",
                        buttons: {
                            confirm: "OK"
                        }
                    }).then(function () {
                        if ($("#cart-count").length > 0) {
                            $("#cart-count").html(response.data.count);
                        }
                    });
                },
                error: function(error) {
                    closeLoading();
                    swal({
                        title: "Lỗi",
                        text: "Có lỗi xảy ra khi thêm vào giỏ hàng!",
                        icon: "error",
                        buttons: {
                            confirm: "OK"
                        }
                    });
                }
            });
        });
    });
</script>

<style>
    .product-detail .breadcrumb {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
        font-size: 14px;
    }
    
    .product-detail .breadcrumb a {
        color: #333;
        text-decoration: none;
    }
    
    .product-detail .breadcrumb span {
        margin: 0 8px;
        color: #999;
    }
    
    .product-container {
        display: flex;
        gap: 30px;
        margin-bottom: 40px;
    }
    
    .product-image {
        flex: 1;
        max-width: 500px;
    }
    
    .product-image img {
        width: 100%;
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        object-fit: cover;
    }
    
    .product-info {
        flex: 1;
    }
    
    .product-name {
        font-size: 28px;
        margin-bottom: 15px;
        color: #333;
    }
    
    .product-meta {
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
    }
    
    .product-category a {
        color: #0066cc;
        text-decoration: none;
    }
    
    .product-vote {
        display: flex;
        align-items: center;
        gap: 5px;
    }
    
    .product-price {
        font-size: 24px;
        font-weight: bold;
        color: #e63946;
        margin-bottom: 20px;
    }
    
    .product-description {
        margin-bottom: 30px;
    }
    
    .product-description h3 {
        font-size: 18px;
        margin-bottom: 10px;
        color: #333;
    }
    
    .description-content {
        line-height: 1.6;
        color: #666;
    }
    
    .product-actions {
        display: flex;
        gap: 15px;
        margin-top: 30px;
    }
    
    .quantity-control {
        display: flex;
        align-items: center;
        border: 1px solid #ddd;
        border-radius: 4px;
    }
    
    .quantity-control button {
        width: 40px;
        height: 40px;
        background: #f5f5f5;
        border: none;
        font-size: 18px;
        cursor: pointer;
    }
    
    .quantity-control input {
        width: 50px;
        height: 40px;
        text-align: center;
        border: none;
        border-left: 1px solid #ddd;
        border-right: 1px solid #ddd;
    }
    
    .btn-add-to-cart {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 0 30px;
        height: 40px;
    }
    
    .product-reviews {
        margin-top: 50px;
    }
    
    .product-reviews h2 {
        font-size: 24px;
        margin-bottom: 20px;
        color: #333;
    }
    
    .reviews-list {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }
    
    .review-item {
        padding: 20px;
        border-radius: 8px;
        background: #f9f9f9;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }
    
    .review-header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 15px;
    }
    
    .review-user {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .review-user img {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
    }
    
    .review-user-info {
        display: flex;
        flex-direction: column;
    }
    
    .review-user-name {
        font-weight: bold;
    }
    
    .review-rating {
        color: #ffc107;
    }
    
    .review-date {
        color: #999;
        font-size: 14px;
    }
    
    .review-content {
        line-height: 1.6;
        color: #666;
    }
    
    .related-products {
        margin-top: 50px;
    }
    
    .related-products h2 {
        font-size: 24px;
        margin-bottom: 20px;
        color: #333;
    }
    
    @media (max-width: 768px) {
        .product-container {
            flex-direction: column;
        }
        
        .product-image {
            max-width: 100%;
        }
    }
</style> 