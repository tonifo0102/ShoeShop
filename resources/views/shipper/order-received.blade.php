@csrf
{!! $MODAL_ORDER_DETAIL !!}
<div class="box-dashboard" data-shipper="true">
    <div class="row">
        <div class="title">Đơn hàng đã nhận</div>
    </div>
    
    <table id="order-received" class="table-datatables ui celled table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Số lượng</th>
                <th>Tổng giá</th>
                <th>Thời gian</th>
                <th>Địa chỉ giao hàng</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($ORDERS as $ORDER)
                <tr>
                    <td>{{ $ORDER->id }}</td>
                    <td>{{ $ORDER->count }}</td>
                    <td>{{ number_format($ORDER->total_discount) }}đ</td>
                    <td>{{ $ORDER->updated_at }}</td>
                    <td>{{ \Illuminate\Support\Str::limit($ORDER->shipping_address ?? 'Không có thông tin', 30) }}</td>
                    <td>
                        <button class="btn btn-gray" id="btn-order-detail-shipper" data-id="{{ $ORDER->id }}">Xem chi tiết</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Khởi tạo DataTable
    $('#order-received').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Vietnamese.json"
        },
        "order": [[3, "desc"]] // Sắp xếp theo cột thời gian mặc định
    });

    // Xử lý sự kiện xem chi tiết đơn hàng
    $(document).on('click', '#btn-order-detail-shipper', function() {
        const orderId = $(this).data('id');
        console.log('Đang gửi yêu cầu xem chi tiết đơn hàng ID:', orderId);
        
        // Hiển thị loading
        const $button = $(this);
        $button.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Đang tải...');
        
        // Gọi API lấy chi tiết đơn hàng
        $.ajax({
            url: '{{ route("shipper.order_received.get-order-detail") }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                order_id: orderId
            },
            success: function(response) {
                console.log('Phản hồi từ server:', response);
                
                if (response.status) {
                    // Tạo nội dung cho modal
                    let html = `
                        <div class="order-detail">
                            <h4>Thông tin giao hàng</h4>
                            <p><strong>Địa chỉ:</strong> ${response.data.shipping_address || 'Không có thông tin'}</p>
                            <p><strong>Số điện thoại:</strong> ${response.data.shipping_phone || 'Không có thông tin'}</p>
                            
                            <h4>Chi tiết đơn hàng</h4>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Sản phẩm</th>
                                        <th>Số lượng</th>
                                        <th>Đơn giá</th>
                                        <th>Thành tiền</th>
                                    </tr>
                                </thead>
                                <tbody>`;
                    
                    // Thêm các sản phẩm vào bảng
                    response.data.products.forEach(product => {
                        html += `
                            <tr>
                                <td>${product.name || 'Không có tên'}</td>
                                <td>${product.quantity || 0}</td>
                                <td>${formatCurrency(product.price || 0)}</td>
                                <td>${formatCurrency(product.total_discount || (product.price * product.quantity) || 0)}</td>
                            </tr>`;
                    });
                    
                    html += `
                                </tbody>
                            </table>
                        </div>`;
                    
                    // Cập nhật nội dung modal và hiển thị
                    $('.modal-order-detail .modal-body').html(html);
                    $('.modal-order-detail').modal('show');
                } else {
                    alert('Lỗi: ' + (response.message || 'Không thể tải chi tiết đơn hàng'));
                }
            },
            error: function(xhr, status, error) {
                console.error('Lỗi AJAX:', {xhr, status, error});
                alert('Có lỗi xảy ra khi tải chi tiết đơn hàng. Vui lòng thử lại sau.');
            },
            complete: function() {
                $button.prop('disabled', false).html('Xem chi tiết');
            }
        });
    });
    
    // Hàm định dạng tiền tệ
    function formatCurrency(amount) {
        return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' })
            .format(amount)
            .replace('₫', 'đ')
            .trim();
    }
});
</script>
@endpush
