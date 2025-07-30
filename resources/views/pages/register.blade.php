<div class="page login">
    <form action="" method="post">
        @csrf
        <h1>Đăng ký</h1>
        <div class="group">
            <label for="name">Họ tên</label>
            <input type="text" name="name" id="name" required pattern="[a-zA-ZÀ-ỹ\s]+" title="Họ tên chỉ được chứa chữ cái, không chứa số và ký tự đặc biệt">
        </div>
        <div class="group">
            <label for="username">Tài khoản</label>
            <input type="text" name="username" id="username" required minlength="5" title="Tài khoản phải có ít nhất 5 ký tự">
        </div>
        <div class="group">
            <label for="phone">Số điện thoại</label>
            <input type="tel" name="phone" id="phone" required pattern="[0-9]{10,15}" title="Số điện thoại phải có từ 10 đến 15 số">
        </div>
        <div class="group">
            <label for="password">Mật khẩu</label>
            <input type="password" name="password" id="password" required minlength="6" title="Mật khẩu phải có ít nhất 6 ký tự">
        </div>
        <div class="group">
            <label for="password">Nhập lại mật khẩu</label>
            <input type="password" name="confirm-password" id="confirm-password" required minlength="6" title="Mật khẩu phải có ít nhất 6 ký tự">
        </div>
        <div class="group">
            <button class="btn btn-blue" type="submit" id="btn-register">Đăng ký</button>
        </div>

        <div class="link">
            <label for="">Bạn đã có tài khoản ? </label>
            <a href="{{ route('login') }}">Đăng nhập</a>
        </div>
        <div class="link">
            <a href="{{ route('forgot_password') }}">Quên mật khẩu ?</a>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Prevent non-alphabetic input for name field
    document.getElementById('name').addEventListener('input', function(e) {
        this.value = this.value.replace(/[^a-zA-ZÀ-ỹ\s]/g, '');
    });
    
    // Prevent non-numeric input for phone field
    document.getElementById('phone').addEventListener('input', function(e) {
        this.value = this.value.replace(/\D/g, '');
        if (this.value.length > 15) {
            this.value = this.value.substring(0, 15);
        }
    });
    
    // Check password match
    document.querySelector('form').addEventListener('submit', function(e) {
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirm-password').value;
        
        if (password !== confirmPassword) {
            e.preventDefault();
            alert('Mật khẩu nhập lại không khớp!');
        }
    });
});
</script>