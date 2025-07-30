@csrf
{!! $MODAL_CREATE_ACCOUNT_SHIPPER !!}
<div class="box-dashboard">
    <div class="row">
        <div class="title">Khách hàng</div>
    </div>
    <table id="account-guest" class="table-datatables ui celled table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tài khoản</th>
                <th>Họ tên</th>
                <th>Số điện thoại</th>
                <th>Địa chỉ</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($GUESTS as $key => $GUEST)
                <tr>
                    <td>{{ $key }}</td>
                    <td>{{ $GUEST->username }}</td>
                    <td>{{ $GUEST->name }}</td>
                    <td>{{ $GUEST->phone }}</td>
                    <td>{{ $GUEST->address }}</td>
                    <td class="center">
                        @if ($GUEST->locked == 0)
                            <button id="btn-locked" class="btn" data-id="{{ $GUEST->id }}" data-status="0">Đang kích hoạt</button>
                        @else
                            <button id="btn-locked" class="btn" data-id="{{ $GUEST->id }}" data-status="1">Đã khóa</button>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="box-dashboard">
    <div class="row">
        <div class="title">Shipper</div>
        <button class="btn btn-green" id="btn-open-modal" data-modal="modal-create-account-shipper">Thêm tài khoản</button>
    </div>
    <table id="account-shipper" class="table-datatables ui celled table">
        @csrf
        <thead>
            <tr>
                <th>ID</th>
                <th>Tài khoản</th>
                <th>Họ tên</th>
                <th>Số điện thoại</th>
                <th>Địa chỉ</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($SHIPPERS as $key => $SHIPPERS)
                <tr>
                    <td>{{ $key }}</td>
                    <td>{{ $SHIPPERS->username }}</td>
                    <td>{{ $SHIPPERS->name }}</td>
                    <td>{{ $SHIPPERS->phone }}</td>
                    <td>{{ $SHIPPERS->address }}</td>
                    <td>
                        @if ($SHIPPERS->locked == 0)
                            <button id="btn-locked" class="btn" data-id="{{ $SHIPPERS->id }}" data-status="0">Đang kích hoạt</button>
                        @else
                            <button id="btn-locked" class="btn" data-id="{{ $SHIPPERS->id }}" data-status="1">Đã khóa</button>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>