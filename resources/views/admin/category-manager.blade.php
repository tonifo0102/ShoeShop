@csrf
{!! $MODAL_CREATE_CATEGORY !!}
{!! $MODAL_UPDATE_CATEGORY !!}
<div class="box-dashboard">
    <div class="row">
        <div class="title">Danh mục</div>
        <button class="btn btn-green" id="btn-open-modal" data-modal="modal-create-category">Thêm danh mục</button>
    </div>
    <table id="category-manager" class="table-datatables ui celled table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Danh mục</th>
                <th>Slug</th>
                <th>Sản phẩm</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($CATEGORIES as $CATEGORY)
                <tr>
                    <td>{{ $CATEGORY->id }}</td>
                    <td>{{ $CATEGORY->name }}</td>
                    <td>{{ $CATEGORY->slug }}</td>
                    <td>{{ $CATEGORY->product_count }}</td>
                    <td>
                        <button id="btn-update-category" class="btn btn-blue" data-id="{{ $CATEGORY->id }}">Cập nhật</button>
                        <button id="btn-delete-category" class="btn btn-red" data-id="{{ $CATEGORY->id }}">Xóa</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>