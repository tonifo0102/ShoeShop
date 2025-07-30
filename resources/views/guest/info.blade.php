<div class="box-dashboard">
    <form class="form">
        @csrf
        <div class="form-group">
            <label for="username">Tài khoản</label>
            <input type="text" name="username" id="username" placeholder="" disabled value="{{ $USER->username }}">
        </div>
        <div class="form-group">
            <label for="name">Họ tên <i class="text-red">*</i></label>
            <input type="text" name="name" id="name" placeholder="" value="{{ $USER->name }}">
        </div>
        <div class="form-group">
            <label for="phone">Số điện thoại <i class="text-red">*</i></label>
            <input type="text" name="phone" id="phone" placeholder="" value="{{ $USER->phone }}">
        </div>
        <div class="form-group">
            <label for="address">Địa chỉ <i class="text-red">*</i></label>
            <input type="text" name="address" id="address" placeholder="" value="{{ $USER->address }}">
        </div>
        <div class="center">
            <button class="btn btn-blue" id="btn-update-info">Cập nhật</button>
        </div>
    </form>
</div>