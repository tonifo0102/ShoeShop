<footer>
    @if ($USE_BANNER)
        <div class="box-shipper">
            <div class="content">
                <h3>Giao hàng miễn phí</h3>
                <p>Miễn phí vận chuyển, nhanh tay đặt hàng thôi bạn ơi!</p>
            </div>
            <img src="{{ asset('Images/shipper.png') }}" alt="">
        </div>
    @endif
    <div class="about">
        <div class="container">
            <div class="row">
                <div class="title">Về chúng tôi</div>
                <div class="content">
                    <p>{{ $TITLE }}</p>
                    <p>Địa chỉ: {{ $ADDRESS }}</p>
                    <p>Số điện thoại: {{ $PHONE }}</p>
                    <p>Email: {{ $EMAIL }}</p>
                </div>
            </div>
            <div class="row">
                <div class="title">Mạng xã hội</div>
                <div class="content">
                    <a href="#"><i class="fa fa-facebook"></i><span>Facebook</span></a>
                    <a href="#"><i class="fa fa-instagram"></i><span>Instagram</span></a>
                    <a href="#"><i class="fa fa-twitter"></i><span>Twitter</span></a>
                </div>
            </div>
        </div>
        <div class="copyright">
            Copyright &copy; 2025{{ $TITLE }}
        </div>
    </div>
</footer>
