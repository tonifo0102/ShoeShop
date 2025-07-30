@csrf
{!! $MODAL_CREATE_PRODUCT !!}
{!! $MODAL_UPDATE_PRODUCT !!}
<div class="box-dashboard">
    <div class="row">
        <div class="title">Sản phẩm</div>
        <button class="btn btn-green" id="btn-open-modal" data-modal="modal-create-product">Thêm sản phẩm</button>
    </div>
    <table id="product-manager" class="table-datatables ui celled table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Hình ảnh</th>
                <th>Tên sản phẩm</th>
                <th>Danh mục</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($PRODUCTS as $PRODUCT)
                <tr>
                    <td>{{ $PRODUCT->id }}</td>
                    <td><img src="{{ asset( 'Images/' . $PRODUCT->avatar) }}" alt=""></td>
                    <td>{{ $PRODUCT->name }}</td>
                    <td>{{ $PRODUCT->category_name }}</td>
                    <td>
                        <button id="btn-update-product" class="btn btn-blue" data-id="{{ $PRODUCT->id }}">Cập nhật</button>
                        <button id="btn-delete-product" class="btn btn-red" data-id="{{ $PRODUCT->id }}">Xóa</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>