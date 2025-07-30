<div class="page">
    <section>
        <div class="path">
            <a href="{{ route("home") }}">Trang chủ</a>
            <i class="fa fa-angle-right"></i>
            <a href="{{ route("search") }}" >Tìm kiếm</a>
        </div>
        <div class="title"><span class="text-gradient">Từ khóa tìm kiếm: </span><i class="highlight-text">`{{ $KEYWORD }}`</i></div>
        
        <!-- Form lọc theo khoảng giá -->
        <div class="price-filter-section" style="margin: 20px 0; padding: 20px; background: #f8f9fa; border-radius: 10px;">
            <h4 style="margin-bottom: 15px; color: #333;">Lọc theo khoảng giá</h4>
            
            <!-- Hiển thị thông báo lỗi -->
            @if($errors->any())
                <div class="alert alert-danger" style="color: #721c24; background-color: #f8d7da; border: 1px solid #f5c6cb; border-radius: 5px; padding: 10px 15px; margin-bottom: 15px;">
                    <ul style="margin: 0; padding-left: 20px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <form method="GET" action="{{ route('search') }}" class="price-filter-form" style="display: flex; align-items: center; gap: 15px; flex-wrap: wrap;">
                <input type="hidden" name="keyword" value="{{ $KEYWORD }}">
                
                <div class="price-input-group" style="display: flex; align-items: center; gap: 10px;">
                    <label for="min_price" style="font-weight: 500; color: #555;">Từ:</label>
                    <input type="number" 
                           name="min_price" 
                           id="min_price" 
                           placeholder="0" 
                           value="{{ old('min_price', request('min_price')) }}"
                           style="width: 120px; padding: 8px 12px; border: 1px solid {{ $errors->has('min_price') ? '#dc3545' : '#ddd' }}; border-radius: 5px; font-size: 14px;"
                           min="0">
                    <span style="color: #666;">VNĐ</span>
                </div>
                
                <div class="price-input-group" style="display: flex; align-items: center; gap: 10px;">
                    <label for="max_price" style="font-weight: 500; color: #555;">Đến:</label>
                    <input type="number" 
                           name="max_price" 
                           id="max_price" 
                           placeholder="Không giới hạn" 
                           value="{{ old('max_price', request('max_price')) }}"
                           style="width: 120px; padding: 8px 12px; border: 1px solid {{ $errors->has('max_price') ? '#dc3545' : '#ddd' }}; border-radius: 5px; font-size: 14px;"
                           min="0">
                    <span style="color: #666;">VNĐ</span>
                </div>
                
                <button type="submit" 
                        style="padding: 8px 20px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 5px; cursor: pointer; font-weight: 500; transition: transform 0.2s;">
                    Lọc
                </button>
                
                @if(request('min_price') || request('max_price'))
                    <a href="{{ route('search', ['keyword' => $KEYWORD]) }}" 
                       style="padding: 8px 15px; background: #6c757d; color: white; text-decoration: none; border-radius: 5px; font-size: 14px;">
                        Xóa bộ lọc
                    </a>
                @endif
            </form>
            
            @if(request('min_price') || request('max_price'))
                <div style="margin-top: 10px; font-size: 14px; color: #666;">
                    <strong>Đang lọc:</strong> 
                    @if(request('min_price') && request('max_price'))
                        Từ {{ number_format(request('min_price'), 0, ',', '.') }} VNĐ đến {{ number_format(request('max_price'), 0, ',', '.') }} VNĐ
                    @elseif(request('min_price'))
                        Từ {{ number_format(request('min_price'), 0, ',', '.') }} VNĐ trở lên
                    @elseif(request('max_price'))
                        Dưới {{ number_format(request('max_price'), 0, ',', '.') }} VNĐ
                    @endif
                </div>
            @endif
        </div>

        <div class="carts-product">
            @if (count($PRODUCTS) == 0)
                <p class="product-empty">Không tìm thấy sản phẩm nào</p>
            @else
                @foreach ($PRODUCTS as $PRODUCT)
                    {{ view('component.cart', ['PRODUCT' => $PRODUCT]) }}
                @endforeach
            @endif
        </div>
    </section>
</div>
