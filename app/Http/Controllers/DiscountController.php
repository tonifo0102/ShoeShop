<?php

namespace App\Http\Controllers;

use App\Models\DiscountModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DateTime;


class DiscountController extends Controller
{
    public function discount_manager()
    {
        $discounts = DiscountModel::all();
        foreach ($discounts as $discount) {
            $discount->remaining = DiscountModel::getRemaining($discount);
            $discount->start_at = date('H:i:s d/m/Y', strtotime($discount->start_at));
            $discount->end_at = date('H:i:s d/m/Y', strtotime($discount->end_at));
            
            // Debug: Ensure remaining is set properly
            if(empty($discount->remaining)) {
                $discount->remaining = "N/A";
            }
        }
        $page = view('admin.discount_manager', [
            'DISCOUNTS' => $discounts,
            'MODAL_CREATE_DISCOUNT' => view('component.modal',[
                'MODAL' => 'modal-create-discount',
                'TITLE' => 'Thêm mã giảm giá',
                'BODY' => view('admin.create-discount'),
                'FOOTER' => [
                    [
                        'class' => 'btn btn-blue',
                        'text' => 'Tạo',
                        'attr' => 'id=btn-create-discount'
                    ],
                ]
            ]),
            'MODAL_UPDATE_DISCOUNT' => view('component.modal',[
                'MODAL' => 'modal-update-discount',
                'TITLE' => 'Cập nhật mã giảm giá',
                'BODY' => view('admin.update-discount'),
                'FOOTER' => [
                    [
                        'class' => 'btn btn-blue',
                        'text' => 'Cập nhật',
                        'attr' => 'id=btn-update-discount-modal'
                    ],
                ]
            ]),
        ]);
        return $this->loadLayoutUser($page,[], false);
    }

    public function API_create_discount(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3|max:255',
            'code' => 'required|unique:discounts,code|min:3|max:255',
            'start_at' => 'required',
            'end_at' => 'required',
            'percent' => 'required|numeric|min:1|max:100',
        ], [
            'name.required' => 'Mô tả không được để trống',
            'name.min' => 'Mô tả phải có ít nhất 3 ký tự',
            'name.max' => 'Mô tả không được quá 255 ký tự',
            'code.required' => 'Mã giảm giá không được để trống',
            'code.unique' => 'Mã giảm giá đã tồn tại',
            'code.min' => 'Mã giảm giá phải có ít nhất 3 ký tự',
            'code.max' => 'Mã giảm giá không được quá 255 ký tự',
            'start_at.required' => 'Ngày bắt đầu không được để trống',
            'end_at.required' => 'Ngày kết thúc không được để trống',
            'percent.required' => 'Phần trăm giảm giá không được để trống',
            'percent.numeric' => 'Phần trăm giảm giá phải là số',
            'percent.min' => 'Phần trăm giảm giá phải lớn hơn 0',
            'percent.max' => 'Phần trăm giảm giá phải nhỏ hơn 100',
        ]);

        if ($validator->fails()) {
            return $this->res(false, $validator->errors()->first());
        }

        if ($this->isValidDateFormat($request->start_at) == false) {
            return $this->res(false, 'Ngày bắt đầu không hợp lệ');
        }

        if ($this->isValidDateFormat($request->end_at) == false) {
            return $this->res(false, 'Ngày kết thúc không hợp lệ');
        }

        if ($this->isValidDate($request->start_at, $request->end_at) == false) {
            return $this->res(false, 'Ngày bắt đầu phải nhỏ hơn ngày kết thúc');
        }

        $discount = new DiscountModel();
        $discount->name = $request->name;
        $discount->code = $request->code;
        $discount->start_at = $request->start_at;
        $discount->end_at = $this->convertDateFormat($request->end_at);
        $discount->percent = $request->percent;
        
        if ($discount->save()) {
            $discount = DiscountModel::find($discount->id);
            $discount->start_at = date('Y-m-d\TH:i', strtotime($discount->start_at));
            $discount->end_at = date('Y-m-d\TH:i', strtotime($discount->end_at));

            return $this->res(true, 'Tạo mã giảm giá thành công',[
                'id' => $discount->id,
                'name' => $discount->name,
                'code' => $discount->code,
                'start_at' => $discount->start_at,
                'end_at' => $discount->end_at,
                'percent' => $discount->percent . '%',
                'remaining' => $discount->remaining,
            ]);
        } else {
            return $this->res(false, 'Tạo mã giảm giá thất bại');
        }
    }

    public function API_update_discount(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric|exists:discounts,id',
            'name' => 'required|min:3|max:255',
            'code' => 'required|min:3|max:255',
            'start_at' => 'required',
            'end_at' => 'required',
            'percent' => 'required|numeric|min:1|max:100',
        ], [
            'id.required' => 'ID không được để trống',
            'id.numeric' => 'ID phải là số',
            'id.exists' => 'Mã giảm giá không tồn tại',
            'name.required' => 'Mô tả không được để trống',
            'name.min' => 'Mô tả phải có ít nhất 3 ký tự',
            'name.max' => 'Mô tả không được quá 255 ký tự',
            'code.required' => 'Mã giảm giá không được để trống',
            'code.min' => 'Mã giảm giá phải có ít nhất 3 ký tự',
            'code.max' => 'Mã giảm giá không được quá 255 ký tự',
            'start_at.required' => 'Ngày bắt đầu không được để trống',
            'end_at.required' => 'Ngày kết thúc không được để trống',
            'percent.required' => 'Phần trăm giảm giá không được để trống',
            'percent.numeric' => 'Phần trăm giảm giá phải là số',
            'percent.min' => 'Phần trăm giảm giá phải lớn hơn 0',
            'percent.max' => 'Phần trăm giảm giá phải nhỏ hơn 100',
        ]);

        if ($validator->fails()) {
            return $this->res(false, $validator->errors()->first());
        }

        if ($this->isValidDateFormat($request->start_at) == false) {
            return $this->res(false, 'Ngày bắt đầu không hợp lệ');
        }

        if ($this->isValidDateFormat($request->end_at) == false) {
            return $this->res(false, 'Ngày kết thúc không hợp lệ');
        }

        if ($this->isValidDate($request->start_at, $request->end_at) == false) {
            return $this->res(false, 'Ngày bắt đầu phải nhỏ hơn ngày kết thúc');
        }

        $discount = DiscountModel::find($request->id);
        $discount->name = $request->name;
        $discount->code = $request->code;
        $discount->start_at = $request->start_at;
        $discount->end_at = $this->convertDateFormat($request->end_at);
        $discount->percent = $request->percent;
        $discount->updated_at = date('Y-m-d H:i:s');

        if ($discount->save()) {
            $discount->remaining = DiscountModel::getRemaining($discount);
            $discount->start_at = date('H:i:s d/m/Y', strtotime($discount->start_at));
            $discount->end_at = date('H:i:s d/m/Y', strtotime($discount->end_at));

            return $this->res(true, 'Cập nhật mã giảm giá thành công', [
                'id' => $discount->id,
                'name' => $discount->name,
                'code' => $discount->code,
                'start_at' => $discount->start_at,
                'end_at' => $discount->end_at,
                'percent' => $discount->percent . '%',
                'remaining' => $discount->remaining,
            ]);
        } else {
            return $this->res(false, 'Cập nhật mã giảm giá thất bại');
        }
    }

    public function API_get_discount(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric|exists:discounts,id',
        ], [
            'id.required' => 'ID không được để trống',
            'id.numeric' => 'ID phải là số',
            'id.exists' => 'Mã giảm giá không tồn tại',
        ]);

        if ($validator->fails()) {
            return $this->res(false, $validator->errors()->first());
        }

        $discount = DiscountModel::find($request->id);
        $discount->start_at = date('Y-m-d\TH:i', strtotime($discount->start_at));
        $discount->end_at = date('Y-m-d\TH:i', strtotime($discount->end_at));

        return $this->res(true, 'Lấy thông tin mã giảm giá thành công', [
            'discount' => $discount,
        ]);
    }

    public function API_delete_discount(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric|exists:discounts,id',
        ], [
            'id.required' => 'ID không được để trống',
            'id.numeric' => 'ID phải là số',
            'id.exists' => 'Mã giảm giá không tồn tại',
        ]);

        if ($validator->fails()) {
            return $this->res(false, $validator->errors()->first());
        }

        $discount = DiscountModel::find($request->id);
        if ($discount->delete()) {
            return $this->res(true, 'Xóa mã giảm giá thành công');
        } else {
            return $this->res(false, 'Xóa mã giảm giá thất bại');
        }
    }

    protected function isValidDateFormat($dateString) {
        $format = 'Y-m-d\TH:i';
        $dateTime = DateTime::createFromFormat($format, $dateString);
        return $dateTime && $dateTime->format($format) === $dateString;
    }

    protected function convertDateFormat($dateString) {
        $format = 'Y-m-d\TH:i';
        $dateTime = DateTime::createFromFormat($format, $dateString);
        return $dateTime->format('Y-m-d H:i:59');
    }

    protected function isValidDate($start_at, $end_at) {
        $start_at = strtotime($start_at);
        $end_at = strtotime($end_at);
        if ($start_at >= $end_at) {
            return false;
        }
        return true;
    }
}
