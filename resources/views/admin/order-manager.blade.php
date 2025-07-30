@csrf
{!! $MODAL_ORDER_DETAIL !!}
<div class="box-dashboard">
    <div class="row">
        <div class="title">Đơn hàng</div>
    </div>
    <table id="order-manager" class="table-datatables ui celled table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Số lượng</th>
                <th>Tổng giá</th>
                <th>Trạng thái</th>
                <th>Thời gian</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($ORDERS as $ORDER)
                <tr>
                    <td>{{ $ORDER->id }}</td>
                    <td>{{ $ORDER->count }}</td>
                    <td>{{ number_format($ORDER->total_discount) }}đ</td>
                    <td>
                        @if($ORDER->status === 'wait')
                            <span class="status-waiting">Chờ duyệt</span>
                        @elseif($ORDER->status === 'shipping')
                            <span class="status-shipping">Đang giao hàng</span>
                        @elseif($ORDER->status === 'complete')
                            <span class="status-complete">Đã hoàn thành</span>
                        @elseif($ORDER->status === 'cancel')
                            <span class="status-cancel">Đã hủy</span>
                        @else
                            <span class="status-other">{{ $ORDER->status }}</span>
                        @endif
                    </td>
                    <td>{{ $ORDER->updated_at->format('H:i d/m/Y') }}</td>
                    <td>
                        <button class="btn btn-gray" id="btn-order-detail" data-id="{{ $ORDER->id }}">Xem chi tiết</button>
                        @if($ORDER->status === 'wait')
                            <button class="btn btn-green" id="btn-order-browsing" data-id="{{ $ORDER->id }}">Duyệt đơn</button>
                            <button class="btn btn-red" id="btn-order-cancel-admin" data-id="{{ $ORDER->id }}">Hủy</button>
                        @elseif($ORDER->status === 'shipping')
                            <button class="btn btn-blue">Shipper đang nhận và giao hàng</button>
                        @elseif($ORDER->status === 'complete')
                            <button class="btn btn-green">Đã hoàn thành</button>
                        @elseif($ORDER->status === 'cancel')
                            <button class="btn btn-red">Đã hủy</button>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>