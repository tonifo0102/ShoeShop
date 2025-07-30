<div class="form">
    <div class="form-group">
        <label for="product-name">Tên sản phẩm</label>
        <input type="text" id="product-name" name="product-name">
    </div>
    <div class="form-group">
        <label for="product-price">Giá</label>
        <input type="number" min="0" id="product-price" name="product-price">
    </div>
    <div class="form-group">
        <label for="product-category">Danh mục</label>
        <select name="product-category" id="product-category">
            @foreach ($CATEGORIES as $CATEGORY)
                <option value="{{ $CATEGORY->id }}">{{ $CATEGORY->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label for="product-slug">Slug</label>
        <input type="text" id="product-slug" name="product-slug">
        <button class="btn btn-black" id="btn-change-slug-product" data-action=""><i class="fa fa-refresh"></i></button>
    </div>
    <div class="form-group">
        <label for="product-description">Mô tả</label>
        <textarea name="product-description" id="product-description"></textarea>
    </div>
    <div class="form-group">
        <label>Hình ảnh</label>
        <input type="file" id="image" name="image" class="hide" accept="image/jpeg, image/png, image/gif, image/jpg">
        <div class="preview">
            <label for="image" class="preview-image">
                <i class="fa fa-image"></i>
            </label>
        </div>
    </div>
</div>