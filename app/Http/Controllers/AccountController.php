<?php

namespace App\Http\Controllers;

use App\Models\AccountModel;
use App\Models\User;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;


class AccountController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function login()
    {
        $page = view('pages.login');
        return $this->loadLayout($page,[], false);
    }

    public function register()
    {
        $page = view('pages.register');
        return $this->loadLayout($page,[], false);
    }

    public function forgot_password()
    {
        $page = view('pages.forgot_password');
        return $this->loadLayout($page,[], false);
    }

    public function info()
    {
        $page = view('guest.info', [
            'USER' => Auth::user(),
        ]);
        return $this->loadLayoutUser($page,[], false);
    }
    
    public function change_password()
    {
        $page = view('guest.change-password');
        return $this->loadLayoutUser($page,[], false);
    }

    public function account_manager()
    {
        $page = view('admin.account-manager', [
            'GUESTS' => AccountModel::all()->where('role', 'guest'),
            'SHIPPERS' => AccountModel::all()->where('role', 'shipper'),
            'MODAL_CREATE_ACCOUNT_SHIPPER' => view('component.modal', [
                'MODAL' => 'modal-create-account-shipper',
                'TITLE' => 'Tạo tài khoản shipper',
                'BODY' => view('admin.create-account-shipper'),
                'FOOTER' => [
                    [
                        'class' => 'btn btn-green',
                        'text' => 'Tạo',
                        'attr' => 'id=btn-create-account-shipper'
                    ],
                ]
            ]),
        ]);
        return $this->loadLayoutUser($page);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('home');
    }

    public function API_login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|min:4',
            'password' => 'required|min:6',
        ], [
            'username.required' => 'Vui lòng nhập tài khoản',
            'username.min' => 'Tài khoản phải có ít nhất 5 ký tự',
            'password.required' => 'Vui lòng nhập mật khẩu',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự',
        ]);

        if ($validator->fails()) {
            return $this->res(false, $validator->errors()->first());
        }

        if (Auth::attempt(['username' => strtolower($request->username), 'password' => $request->password, 'locked' => '1'])) {
            Auth::logout();
            return $this->res(false, 'Tài khoản của bạn đã bị khóa');
        } else if (Auth::attempt(['username' => strtolower($request->username), 'password' => $request->password])) {
            return $this->res(true, 'Đăng nhập thành công');
        } else {
            return $this->res(false, 'Tài khoản hoặc mật khẩu không đúng');
        }
    }

    public function API_register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|min:4|alpha_num|unique:accounts,username',
            'password' => 'required|min:6',
            'name' => 'required|regex:/^[a-zA-ZÀ-ỹ\s]+$/',
            'phone' => 'required|digits_between:10,15|numeric',
        ], [
            'username.required' => 'Vui lòng nhập tài khoản',
            'username.min' => 'Tài khoản phải có ít nhất 5 ký tự',
            'username.unique' => 'Tài khoản đã tồn tại',
            'username.alpha_num' => 'Tài khoản chỉ được chứa chữ và số',
            'password.required' => 'Vui lòng nhập mật khẩu',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự',
            'name.required' => 'Vui lòng nhập họ tên',
            'name.regex' => 'Họ tên chỉ được chứa chữ cái, không được chứa số và ký tự đặc biệt',
            'phone.required' => 'Vui lòng nhập số điện thoại',
            'phone.digits_between' => 'Số điện thoại phải có từ 10 đến 15 số',
            'phone.numeric' => 'Số điện thoại chỉ được chứa số'
        ]);

        if ($validator->fails()) {
            return $this->res(false, $validator->errors()->first());
        }

        $user = new User();
        $user->username = strtolower($request->username);
        $user->password = bcrypt($request->password);
        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->token = bcrypt($user->username . $user->password . time() . rand(0, 1000));

        if ($user->save()) {
            return $this->res(true, 'Đăng ký thành công');
        } else {
            return $this->res(false, 'Đăng ký thất bại');
        }
    }

    public function API_forgot_password(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|min:4|alpha_num',
            'phone' => 'required|digits_between:10,15|numeric',
        ], [
            'username.required' => 'Vui lòng nhập tài khoản',
            'username.min' => 'Tài khoản phải có ít nhất 5 ký tự',
            'username.alpha_num' => 'Tài khoản chỉ được chứa chữ và số',
            'phone.required' => 'Vui lòng nhập số điện thoại',
            'phone.digits_between' => 'Số điện thoại phải có từ 10 đến 15 số',
            'phone.numeric' => 'Số điện thoại chỉ được chứa số'
        ]);

        if ($validator->fails()) {
            return $this->res(false, $validator->errors()->first());
        }

        $user = User::where('username', strtolower($request->username))
                    ->where('phone', $request->phone)
                    ->first();

        if ($user) {
            $new_password = rand(100000, 999999);
            $user->password = bcrypt($new_password);
            $user->updated_at = date('Y-m-d H:i:s');
            $user->save();

            return $this->res(true, 'Mật khẩu mới của bạn là: ' . $new_password);
        } else {
            return $this->res(false, 'Tài khoản hoặc số điện thoại không đúng');
        }
    }

    public function API_change_info(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|regex:/^[a-zA-ZÀ-ỹ\s]+$/',
            'phone' => 'required|digits_between:10,15|numeric',
            'address' => 'required|min:5|max:255',
        ], [
            'name.required' => 'Vui lòng nhập họ tên',
            'name.regex' => 'Họ tên chỉ được chứa chữ cái, không được chứa số và ký tự đặc biệt',
            'phone.required' => 'Vui lòng nhập số điện thoại',
            'phone.digits_between' => 'Số điện thoại phải có từ 10 đến 15 số',
            'phone.numeric' => 'Số điện thoại chỉ được chứa số',
            'address.required' => 'Vui lòng nhập địa chỉ',
            'address.min' => 'Địa chỉ phải có ít nhất 5 ký tự',
            'address.max' => 'Địa chỉ không được quá 255 ký tự',
        ]);

        if ($validator->fails()) {
            return $this->res(false, $validator->errors()->first());
        }

        $user = User::find(Auth::user()->id);
        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->updated_at = date('Y-m-d H:i:s');

        if ($user->save()) {
            return $this->res(true, 'Cập nhật thông tin thành công');
        } else {
            return $this->res(false, 'Cập nhật thông tin thất bại');
        }
    }

    public function API_change_password(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required|min:6',
            'new_password' => 'required|min:6',
        ], [
            'old_password.required' => 'Vui lòng nhập mật khẩu cũ',
            'old_password.min' => 'Mật khẩu cũ phải có ít nhất 6 ký tự',
            'new_password.required' => 'Vui lòng nhập mật khẩu mới',
            'new_password.min' => 'Mật khẩu mới phải có ít nhất 6 ký tự',
        ]);

        if ($validator->fails()) {
            return $this->res(false, $validator->errors()->first());
        }

        $user = User::find(Auth::user()->id);

        if (Hash::check($request->old_password, $user->password)) {
            $user->password = bcrypt($request->new_password);
            $user->updated_at = date('Y-m-d H:i:s');

            if ($user->save()) {
                return $this->res(true, 'Đổi mật khẩu thành công');
            } else {
                return $this->res(false, 'Đổi mật khẩu thất bại');
            }
        } else {
            return $this->res(false, 'Mật khẩu cũ không đúng');
        }
    }

    public function API_change_status_locked(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric|exists:accounts,id',
            'status' => 'required|numeric|in:0,1',
        ], [
            'id.required' => 'Vui lòng nhập ID',
            'id.numeric' => 'ID phải là số',
            'id.exists' => 'Tài khoản không tồn tại',
            'status.required' => 'Vui lòng nhập trạng thái',
            'status.numeric' => 'Trạng thái phải là số',
            'status.in' => 'Trạng thái không hợp lệ',
        ]);

        if ($validator->fails()) {
            return $this->res(false, $validator->errors()->first());
        }

        $user = User::find($request->id);
        $user->locked = $request->status ? "0" : "1";
        $user->updated_at = date('Y-m-d H:i:s');

        if ($user->save()) {
            return $this->res(true, 'Cập nhật trạng thái thành công');
        } else {
            return $this->res(false, 'Cập nhật trạng thái thất bại');
        }
    }

    public function API_create_account_shipper(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|min:4|max:255|unique:accounts,username',
            'password' => 'required|min:6|max:255',
            'name' => 'required|regex:/^[a-zA-ZÀ-ỹ\s]+$/',
            'phone' => 'required|digits_between:10,15|numeric',
        ], [
            'username.required' => 'Vui lòng nhập tên tài khoản',
            'username.min' => 'Tên tài khoản phải có ít nhất 5 ký tự',
            'username.max' => 'Tên tài khoản không được quá 255 ký tự',
            'username.unique' => 'Tên tài khoản đã tồn tại',
            'password.required' => 'Vui lòng nhập mật khẩu',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự',
            'password.max' => 'Mật khẩu không được quá 255 ký tự',
            'name.required' => 'Vui lòng nhập họ tên',
            'name.regex' => 'Họ tên chỉ được chứa chữ cái, không được chứa số và ký tự đặc biệt',
            'phone.required' => 'Vui lòng nhập số điện thoại',
            'phone.digits_between' => 'Số điện thoại phải có từ 10 đến 15 số',
            'phone.numeric' => 'Số điện thoại chỉ được chứa số',
        ]);

        if ($validator->fails()) {
            return $this->res(false, $validator->errors()->first());
        }

        $user = new User();
        $user->username = $request->username;
        $user->password = bcrypt($request->password);
        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->role = "shipper";
        $user->token = bcrypt($user->username . $user->password . time() . rand(0, 1000));

        if ($user->save()) {
            return $this->res(true, 'Tạo tài khoản thành công', [
                'id' => $user->id,
                'username' => $user->username,
                'name' => $user->name,
                'phone' => $user->phone,
            ]);
        } else {
            return $this->res(false, 'Tạo tài khoản thất bại');
        }
    }
}