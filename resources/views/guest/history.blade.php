{!! $MODAL_ORDER_DETAIL !!}
{!! $MODAL_ORDER_REVIEW !!}
<div class="box-dashboard">
    @csrf
    <table class="table-datatables ui celled table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Số lượng</th>
                <th>Tổng giá</th>
                <th>Thời gian</th>
                <th>Trạng thái</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($ORDERS as $ORDER)
                <tr>
                    <td>{{ $ORDER->id }}</td>
                    <td>{{ $ORDER->count }}</td>
                    <td>{{ number_format($ORDER->total_discount) }}đ</td>
                    <td>{{ $ORDER->created_at }}</td>
                    <td>
                        @if ($ORDER->status == 'Đang giao hàng')
                            <ion-icon name="alarm-outline"></ion-icon>
                            @if ($ORDER->delivery_time != 0)
                                Dự kiến giao hàng sau {{ $ORDER->delivery_time }} phút nữa
                            @else
                                Dự kiến giao hàng trong ít phút nữa
                            @endif
                        @else
                            {{ $ORDER->status }}
                        @endif
                    </td>
                    <td>
                        <button class="btn btn-gray" id="btn-order-detail-guest" data-id="{{ $ORDER->id }}" >Xem chi tiết</button>
                        @if ($ORDER->status == 'Đã hoàn thành')
                            <button class="btn btn-blue" id="btn-order-review-guest" data-id="{{ $ORDER->id }}" >Đánh giá</button>
                        @endif
                        @if ($ORDER->status == 'Chờ xử lý')
                            <button class="btn btn-red" id="btn-order-cancel-guest" data-id="{{ $ORDER->id }}" >Hủy</button>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>