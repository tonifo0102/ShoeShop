<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CategoryModel;
use App\Models\ProductModel;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index()
    {
        $products = ProductModel::orderBy('created_at', 'desc')
            ->orderBy('id', 'desc')
            ->get();
        $products = ProductModel::formatPrice($products);

        $page = view('pages.category', [
            'PRODUCTS' => $products,
            'CATEGORY' => (object)[
                'name' => 'Tất cả sản phẩm',
                'slug' => '',
            ]
        ]);

        return $this->loadLayout($page);
    }

    public function search($slug)
    {
        $category = CategoryModel::where('slug', $slug)->first();
        $products = null;
        if (!$category) {
            $category = null;
        } else {
            $products = ProductModel::where('category_id', $category->id)
                ->orderBy('created_at', 'desc')
                ->orderBy('id', 'desc')
                ->get();
            $products = ProductModel::formatPrice($products);
        }

        $page = view('pages.category', [
            'PRODUCTS' => $products,
            'CATEGORY' => $category
        ]);

        return $this->loadLayout($page);
    }

    public function category_manager()
    {
        $categories = CategoryModel::all();
        foreach ($categories as $category) {
            $category->product_count = ProductModel::where('category_id', $category->id)->count();
        }
        $page = view('admin.category-manager', [
            'CATEGORIES' => $categories,
            'MODAL_CREATE_CATEGORY' => view('component.modal', [
                'MODAL' => 'modal-create-category',
                'TITLE' => 'Thêm danh mục',
                'BODY' => view('admin.create-category'),
                'FOOTER' => [
                    [
                        'class' => 'btn btn-green',
                        'text' => 'Tạo',
                        'attr' => 'id=btn-create-category'
                    ],
                ]
            ]),
            'MODAL_UPDATE_CATEGORY' => view('component.modal', [
                'MODAL' => 'modal-update-category',
                'TITLE' => 'Cập nhật danh mục',
                'BODY' => view('admin.update-category'),
                'FOOTER' => [
                    [
                        'class' => 'btn btn-blue',
                        'text' => 'Cập nhật',
                        'attr' => 'id=btn-update-category-modal'
                    ],
                ]
            ]),
        ]);
        return $this->loadLayoutUser($page, [], false);
    }

    public function API_create_category(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:categories,name|max:255',
            'slug' => 'required|unique:categories,slug|max:255',
        ], [
            'name.required' => 'Tên danh mục không được để trống',
            'name.unique' => 'Tên danh mục đã tồn tại',
            'name.max' => 'Tên danh mục không được quá 255 ký tự',
            'slug.required' => 'Slug không được để trống',
            'slug.unique' => 'Slug đã tồn tại',
            'slug.max' => 'Slug không được quá 255 ký tự',
        ]);

        if ($validator->fails()) {
            return $this->res(false, $validator->errors()->first());
        }

        $slug = $request->slug;

        if (!$this->isSlug($slug)) {
            return $this->res(false, 'Slug không hợp lệ');
        }

        $category = new CategoryModel();
        $category->name = $request->name;
        $category->slug = $request->slug;
        $category->save();
        return response()->json([
            'status' => true,
            'message' => 'Tạo danh mục thành công',
            'data' => [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
            ]
        ]);
    }

    public function API_delete_category(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:categories,id',
        ], [
            'id.required' => 'ID không được để trống',
            'id.exists' => 'Danh mục không tồn tại',
        ]);

        if ($validator->fails()) {
            return $this->res(false, $validator->errors()->first());
        }

        $product_count = ProductModel::where('category_id', $request->id)->count();
        if ($product_count > 0) {
            return $this->res(false, 'Danh mục này đang có sản phẩm, không thể xóa');
        }

        $category = CategoryModel::find($request->id);
        $category->delete();
        return $this->res(true, 'Xóa danh mục thành công');
    }

    public function API_get_category(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:categories,id',
        ], [
            'id.required' => 'ID không được để trống',
            'id.exists' => 'Danh mục không tồn tại',
        ]);

        if ($validator->fails()) {
            return $this->res(false, $validator->errors()->first());
        }

        $category = CategoryModel::find($request->id);

        return response()->json([
            'status' => true,
            'message' => 'Lấy danh mục thành công',
            'data' => [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
            ]
        ]);
    }

    public function API_update_category(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:categories,id',
            'name' => 'required|max:255',
            'slug' => 'required|max:255',
        ], [
            'id.required' => 'ID không được để trống',
            'id.exists' => 'Danh mục không tồn tại',
            'name.required' => 'Tên danh mục không được để trống',
            'name.max' => 'Tên danh mục không được quá 255 ký tự',
            'slug.required' => 'Slug không được để trống',
            'slug.max' => 'Slug không được quá 255 ký tự',
        ]);

        if ($validator->fails()) {
            return $this->res(false, $validator->errors()->first());
        }

        $slug = $request->slug;

        if (!$this->isSlug($slug)) {
            return $this->res(false, 'Slug không hợp lệ');
        }

        $category = CategoryModel::find($request->id);
        $category->name = $request->name;
        $category->slug = $request->slug;
        $category->updated_at = date('Y-m-d H:i:s');

        if ($category->save()) {
            return response()->json([
                'status' => true,
                'message' => 'Cập nhật danh mục thành công',
                'data' => [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                ]
            ]);
        }
        return $this->res(false, 'Cập nhật danh mục thất bại');
    }


    protected function isSlug($str)
    {
        // Kiểm tra nếu chuỗi không chứa ký tự in hoa
        if (preg_match('/[A-Z]/', $str)) {
            return false;
        }

        // Kiểm tra nếu chuỗi chứa ký tự không phải là chữ cái, chữ số, hoặc dấu gạch ngang
        if (preg_match('/[^a-z0-9-]/', $str)) {
            return false;
        }

        // Kiểm tra nếu chuỗi không cách nhau bởi dấu gạch ngang
        if (strpos($str, '--') !== false) {
            return false;
        }

        // Kiểm tra nếu chuỗi bắt đầu hoặc kết thúc bằng dấu gạch ngang
        if (substr($str, 0, 1) === '-' || substr($str, -1) === '-') {
            return false;
        }

        return true;
    }
}
