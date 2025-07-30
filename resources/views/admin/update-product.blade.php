<div class="form">
    <input type="hidden" name="update-product-id" id="update-product-id">
    <div class="form-group">
        <label for="update-product-name">Tên sản phẩm</label>
        <input type="text" id="update-product-name" name="update-product-name">
    </div>
    <div class="form-group">
        <label for="update-product-price">Giá</label>
        <input type="number" min="0" id="update-product-price" name="update-product-price">
    </div>
    <div class="form-group">
        <label for="update-product-category">Danh mục</label>
        <select name="update-product-category" id="update-product-category">
            @foreach ($CATEGORIES as $CATEGORY)
                <option value="{{ $CATEGORY->id }}">{{ $CATEGORY->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label for="update-product-slug">Slug</label>
        <input type="text" id="update-product-slug" name="update-product-slug">
        <button class="btn btn-black" id="btn-change-slug-product" data-action="update-"><i class="fa fa-refresh"></i></button>
    </div>
    <div class="form-group">
        <label for="update-product-description">Mô tả</label>
        <textarea name="update-product-description" id="update-product-description"></textarea>
    </div>
    <div class="form-group">
        <label>Hình ảnh</label>
        <input type="file" id="update-product-image" name="update-product-image" class="hide" accept="image/jpeg, image/png, image/gif, image/jpg">
        <div class="preview">
            <label for="update-product-image" class="preview-image preview-image-update">
                <i class="fa fa-image"></i>
            </label>
        </div>
    </div>
</div>