<div class="box-dashboard">
    <div class="row">
        <div class="col card purple">
            <div class="card-header">Số lượng sản phẩm</div>
            <div class="card-body">{{ $PRODUCTS->count() }}</div>
        </div>
        <div class="col card red">
            <div class="card-header">Số lượng danh mục</div>
            <div class="card-body">{{ $CATEGORIES->count() }}</div>
        </div>
        <div class="col card blue">
            <div class="card-header">Số lượng shipper</div>
            <div class="card-body">{{ $SHIPPER->count() }}</div>
        </div>
        <div class="col card green">
            <div class="card-header">Số lượng khách hàng</div>
            <div class="card-body">{{ $ACCOUNTS->count() }}</div>
        </div>
    </div>
    <div class="row">
        <div class="col card yellow">
            <div class="card-header">Số đơn hoàn thành</div>
            <div class="card-body">{{ $ORDERS->count() }}</div>
        </div>
        <div class="col card orange">
            <div class="card-header">Tổng doanh thu</div>
            <div class="card-body">{{ number_format($TOTAL_REVENUE) }}đ</div>
        </div>
        <div class="col card pink">
            <div class="card-header">Số đơn hoàn thành tháng này</div>
            <div class="card-body">{{ $ORDERS_THIS_MONTH->count() }}</div>
        </div>
        <div class="col card indigo">
            <div class="card-header">Doanh thu tháng này</div>
            <div class="card-body">{{ number_format($TOTAL_REVENUE_THIS_MONTH) }}đ</div>
        </div>
    </div>
</div>
