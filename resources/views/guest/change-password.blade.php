<div class="box-dashboard">
    <form class="form">
        @csrf
        <div class="form-group">
            <label for="password-old">Mật khẩu cũ <i class="text-red">*</i></label>
            <input type="password" name="password-old" id="password-old" placeholder="">
        </div>
        <div class="form-group">
            <label for="password-new">Mật khẩu mới <i class="text-red">*</i></label>
            <input type="password" name="password-new" id="password-new" placeholder="">
        </div>
        <div class="form-group">
            <label for="password-confirm">Xác nhận mật khẩu <i class="text-red">*</i></label>
            <input type="password" name="password-confirm" id="password-confirm" placeholder="">
        </div>
        <div class="center">
            <button class="btn btn-blue" id="btn-change-password">Cập nhật</button>
        </div>
    </form>
</div>