@csrf
{!! $MODAL_CREATE_DISCOUNT !!}
{!! $MODAL_UPDATE_DISCOUNT !!}
<div class="box-dashboard">
    <div class="row">
        <div class="title">Khuyễn mãi</div>
        <button class="btn btn-green" id="btn-open-modal" data-modal="modal-create-discount">Thêm mã giảm giá</button>
    </div>
    <table id="discount-manager" class="table-datatables ui celled table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Mô tả</th>
                <th>Mã giảm giá</th>
                <th>Phần trăm</th>
                <th>Bắt đầu</th>
                <th>Kết thúc</th>
                <th>Còn lại</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($DISCOUNTS as $DISCOUNT)
                <tr>
                    <td>{{ $DISCOUNT->id }}</td>
                    <td>{{ $DISCOUNT->name }}</td>
                    <td>{{ $DISCOUNT->code }}</td>
                    <td>{{ $DISCOUNT->percent }}%</td>
                    <td>{{ $DISCOUNT->start_at }}</td>
                    <td>{{ $DISCOUNT->end_at }}</td>
                    <td>{{ $DISCOUNT->remaining }}</td>

                    <td>
                        <button id="btn-update-discount" class="btn btn-blue" data-id="{{ $DISCOUNT->id }}">Cập nhật</button>
                        <button id="btn-delete-discount" class="btn btn-red" data-id="{{ $DISCOUNT->id }}">Xóa</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>