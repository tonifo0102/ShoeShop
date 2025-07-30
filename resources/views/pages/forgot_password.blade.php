<div class="page login">
    <form action="" method="post">
        @csrf
        <h1>Quên mật khẩu</h1>
        <p>Lưu ý: Sau khi xác nhận thành công, mật khẩu sẽ được đổi thành ngẫu nhiên và hiện ở thông báo.</p>
        <div class="group">
            <label for="username">Tài khoản</label>
            <input type="text" name="username" id="username" required minlength="5" title="Tài khoản phải có ít nhất 5 ký tự">
        </div>
        <div class="group">
            <label for="phone">Số điện thoại</label>
            <input type="tel" name="phone" id="phone" required pattern="[0-9]{10,15}" title="Số điện thoại phải có từ 10 đến 15 số">
        </div>
        <div class="group">
            <button class="btn btn-blue" type="submit" id="btn-forgot-password">Xác nhận</button>
        </div>

        <div class="link">
            <label for="">Bạn đã có tài khoản ? </label>
            <a href="{{ route('login') }}">Đăng nhập</a>
        </div>
        <div class="link">
            <label for="">Bạn chưa có tài khoản ? </label>
            <a href="{{ route('register') }}">Đăng ký</a>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Prevent non-numeric input for phone field
    document.getElementById('phone').addEventListener('input', function(e) {
        this.value = this.value.replace(/\D/g, '');
        if (this.value.length > 15) {
            this.value = this.value.substring(0, 15);
        }
    });
});
</script>