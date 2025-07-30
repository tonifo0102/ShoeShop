<?php

namespace App\Http\Controllers;

use App\Models\CategoryModel;
use App\Models\OrderModel;
use App\Models\OrderProductModel;
use Illuminate\Http\Request;
use App\Models\ProductModel;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function search()
    {
        $keyword = request()->get('keyword') ?? '';
        $keyword = trim($keyword);
        $keyword = strtolower($keyword);

        // Lấy tham số lọc giá từ request
        $minPrice = request()->get('min_price');
        $maxPrice = request()->get('max_price');

        // Validate dữ liệu đầu vào
        $validator = Validator::make(request()->all(), [
            'min_price' => ['nullable', 'numeric', 'min:0'],
            'max_price' => ['nullable', 'numeric', 'min:0', function($attribute, $value, $fail) use ($minPrice) {
                if (!empty($minPrice) && $value <= $minPrice) {
                    $fail('Giá tối đa phải lớn hơn giá tối thiểu');
                }
            }],
        ], [
            'min_price.numeric' => 'Giá tối thiểu phải là số',
            'min_price.min' => 'Giá tối thiểu không được nhỏ hơn 0',
            'max_price.numeric' => 'Giá tối đa phải là số',
            'max_price.min' => 'Giá tối đa không được nhỏ hơn 0',
        ]);

        // Nếu validation thất bại, chuyển hướng lại với thông báo lỗi
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Chuyển đổi sang số nếu có giá trị
        $minPrice = $minPrice ? (int)$minPrice : null;
        $maxPrice = $maxPrice ? (int)$maxPrice : null;

        $products = ProductModel::search($keyword, $minPrice, $maxPrice);

        foreach ($products as $key => $product) {
            $reviews = OrderProductModel::where('product_id', $product->id)
                                        ->where('vote', '!=', null)
                                        ->get();
            $total = $reviews->count();
            if ($total != 0) {
                $sum = 0;
                foreach ($reviews as $review) {
                    $sum += $review->vote;
                }
                $ratio = $sum / $total;

                $product->ratio = $ratio;
                $product->total = $total;
            } else {
                $product->ratio = 0;
                $product->total = 0;
            }
        }

        $page = view('pages/search', [
            'PRODUCTS' => $products,
            'KEYWORD' => $keyword,
        ]);

        return $this->loadLayout($page);
    }

    public function detail($slug)
    {
        $product = ProductModel::where('slug', $slug)->first();
        
        if (!$product) {
            return redirect()->route('home');
        }
        
        // Format price
        $product->priceFormatted = number_format($product->price, 0, ',', '.');
        
        // Get reviews with eager loading of relationships
        $reviews = OrderProductModel::where('product_id', $product->id)
                                    ->where('vote', '!=', null)
                                    ->with(['order.account']) // Eager load order and account
                                    ->get();
        
        $total = $reviews->count();
        if ($total != 0) {
            $sum = 0;
            foreach ($reviews as $review) {
                $sum += $review->vote;
            }
            $ratio = $sum / $total;

            $product->ratio = $ratio;
            $product->total = $total;
        } else {
            $product->ratio = 0;
            $product->total = 0;
        }
        
        // Get related products from same category
        $relatedProducts = ProductModel::where('category_id', $product->category_id)
                                      ->where('id', '!=', $product->id)
                                      ->limit(4)
                                      ->get();
        
        foreach ($relatedProducts as $relatedProduct) {
            $relatedProduct->priceFormatted = number_format($relatedProduct->price, 0, ',', '.');
            
            $reviewsRelated = OrderProductModel::where('product_id', $relatedProduct->id)
                                             ->where('vote', '!=', null)
                                             ->get();
            
            $totalRelated = $reviewsRelated->count();
            if ($totalRelated != 0) {
                $sumRelated = 0;
                foreach ($reviewsRelated as $reviewRelated) {
                    $sumRelated += $reviewRelated->vote;
                }
                $ratioRelated = $sumRelated / $totalRelated;

                $relatedProduct->ratio = $ratioRelated;
                $relatedProduct->total = $totalRelated;
            } else {
                $relatedProduct->ratio = 0;
                $relatedProduct->total = 0;
            }
        }
        
        // Get category name
        $category = CategoryModel::find($product->category_id);
        
        // Check if there are reviews that really have note data
        $hasReviews = $reviews->filter(function($review) {
            return !empty($review->order) && !empty($review->order->account) && (!empty($review->note) || !empty($review->vote));
        })->count() > 0;
        
        $page = view('pages/product-detail', [
            'PRODUCT' => $product,
            'CATEGORY' => $category,
            'REVIEWS' => $reviews,
            'HAS_REVIEWS' => $hasReviews,
            'RELATED_PRODUCTS' => $relatedProducts,
        ]);

        return $this->loadLayout($page);
    }

    public function product_manager()
    {
        $products = ProductModel::join('categories', 'products.category_id', '=', 'categories.id')
            ->select('products.*', 'categories.name as category_name')
            ->get();

        $page = view('admin.product-manager', [
            'PRODUCTS' => $products,
            'MODAL_CREATE_PRODUCT' => view('component.modal',[
                'MODAL' => 'modal-create-product',
                'TITLE' => 'Thêm sản phẩm',
                'BODY' => view('admin.create-product', [
                    'CATEGORIES' => CategoryModel::all(),
                ]),
                'FOOTER' => [
                    [
                        'class' => 'btn btn-blue',
                        'text' => 'Tạo',
                        'attr' => 'id=btn-create-product'
                    ],
                ]
            ]),
            'MODAL_UPDATE_PRODUCT' => view('component.modal',[
                'MODAL' => 'modal-update-product',
                'TITLE' => 'Cập nhật sản phẩm',
                'BODY' => view('admin.update-product', [
                    'CATEGORIES' => CategoryModel::all(),
                ]),
                'FOOTER' => [
                    [
                        'class' => 'btn btn-blue',
                        'text' => 'Cập nhật',
                        'attr' => 'id=btn-update-product-modal'
                    ],
                ]
            ]),
        ]);
        return $this->loadLayoutUser($page);
    }

    public function API_create_product(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3|max:255',
            'price' => 'required|numeric|min:1',
            'description' => 'max:5000',
            'category' => 'required|numeric|exists:categories,id',
            'slug' => 'required|unique:products,slug|min:3|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:20480',
        ], [
            'name.required' => 'Tên sản phẩm không được để trống',
            'name.min' => 'Tên sản phẩm phải có ít nhất 3 ký tự',
            'name.max' => 'Tên sản phẩm không được vượt quá 255 ký tự',
            'price.required' => 'Giá sản phẩm không được để trống',
            'price.numeric' => 'Giá sản phẩm phải là số',
            'price.min' => 'Giá sản phẩm phải lớn hơn 0',
            'description.max' => 'Mô tả không được vượt quá 5000 ký tự',
            'category.required' => 'Danh mục không được để trống',
            'category.numeric' => 'Danh mục không hợp lệ',
            'category.exists' => 'Danh mục không tồn tại',
            'slug.required' => 'Slug không được để trống',
            'slug.unique' => 'Slug đã tồn tại',
            'slug.min' => 'Slug phải có ít nhất 3 ký tự',
            'slug.max' => 'Slug không được vượt quá 255 ký tự',
            'image.required' => 'Ảnh không được để trống',
            'image.image' => 'Ảnh không hợp lệ',
            'image.mimes' => 'Ảnh không hợp lệ',
            'image.max' => 'Ảnh không được vượt quá 20MB',
        ]);

        if ($validator->fails()) {
            return $this->res(false, $validator->errors()->first());
        }

        $slug = $request->slug;

        if (!$this->isSlug($slug)) {
            return $this->res(false, 'Slug không hợp lệ');
        }

        $image = $request->file('image');
        $imageName = md5($slug) . '_' . md5(time()) . '_' . time() . '.' . $image->extension();

        if (!$image->move(public_path('Images\Shoe'), $imageName)) {
            return $this->res(false, 'Lỗi khi tải ảnh lên');
        }

        $imageName = 'Shoe/' . $imageName;

        $product = new ProductModel();
        $product->name = $request->name;
        $product->price = $request->price;
        $product->description = $request->description ?? '';
        $product->category_id = $request->category;
        $product->slug = $slug;
        $product->avatar = $imageName;

        if (!$product->save()) {
            return $this->res(false, 'Lỗi khi tạo sản phẩm');
        }

        $category = CategoryModel::find($product->category_id);

        return $this->res(true, 'Tạo sản phẩm thành công', [
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->price,
            'category' => $category->name,
            'avatar' => $product->avatar,
        ]);
    }

    public function API_get_product(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric|exists:products,id',
        ], [
            'id.required' => 'ID không được để trống',
            'id.numeric' => 'ID không hợp lệ',
            'id.exists' => 'Không tìm thấy sản phẩm',
        ]);

        if ($validator->fails()) {
            return $this->res(false, $validator->errors()->first());
        }

        $product = ProductModel::find($request->id);

        return $this->res(true, 'Lấy sản phẩm thành công', [
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->price,
            'description' => $product->description,
            'category' => $product->category_id,
            'avatar' => $product->avatar,
            'slug' => $product->slug,
        ]);
    }

    public function API_update_product(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'  => 'required|numeric|exists:products,id',
            'name' => 'required|min:3|max:255',
            'price' => 'required|numeric|min:1',
            'description' => 'max:5000',
            'category' => 'required|numeric|exists:categories,id',
            'slug' => 'required|min:3|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:20480',
        ], [
            'id.required' => 'ID không được để trống',
            'id.numeric' => 'ID không hợp lệ',
            'id.exists' => 'Không tìm thấy sản phẩm',
            'name.required' => 'Tên sản phẩm không được để trống',
            'name.min' => 'Tên sản phẩm phải có ít nhất 3 ký tự',
            'name.max' => 'Tên sản phẩm không được vượt quá 255 ký tự',
            'price.required' => 'Giá sản phẩm không được để trống',
            'price.numeric' => 'Giá sản phẩm phải là số',
            'price.min' => 'Giá sản phẩm phải lớn hơn 0',
            'description.max' => 'Mô tả không được vượt quá 5000 ký tự',
            'category.required' => 'Danh mục không được để trống',
            'category.numeric' => 'Danh mục không hợp lệ',
            'category.exists' => 'Danh mục không tồn tại',
            'slug.required' => 'Slug không được để trống',
            'slug.min' => 'Slug phải có ít nhất 3 ký tự',
            'slug.max' => 'Slug không được vượt quá 255 ký tự',
            'image.image' => 'Ảnh không hợp lệ',
            'image.mimes' => 'Ảnh không hợp lệ',
            'image.max' => 'Ảnh không được vượt quá 20MB',
        ]);

        if ($validator->fails()) {
            return $this->res(false, $validator->errors()->first());
        }

        $slug = $request->slug;

        if (!$this->isSlug($slug)) {
            return $this->res(false, 'Slug không hợp lệ');
        }

        $checkSlug = ProductModel::where('slug', $slug)->where('id', '!=', $request->id)->first();

        if ($checkSlug) {
            return $this->res(false, 'Slug đã tồn tại');
        }

        $image = $request->file('image') ?? '';
        $imageName = '';

        $product = ProductModel::find($request->id);
        $product->name = $request->name;
        $product->price = $request->price;
        $product->description = $request->description ?? '';
        $product->category_id = $request->category;
        $product->slug = $slug;
        $product->updated_at = date('Y-m-d H:i:s');

        if ($image != '') {
            $imageName = md5($slug) . '_' . md5(time()) . '_' . time() . '.' . $image->extension();

            if (!$image->move(public_path('Images\Shoe'), $imageName)) {
                return $this->res(false, 'Lỗi khi tải ảnh lên');
            }

            $imageName = 'Shoe/' . $imageName;

            if (file_exists(public_path('Images/' . $product->avatar))) {
                unlink(public_path('Images/' .$product->avatar));
            }
        }
        else {
            $imageName = $product->avatar;
        }

        $product->avatar = $imageName;

        if (!$product->save()) {
            return $this->res(false, 'Lỗi khi cập nhật sản phẩm');
        }

        $category = CategoryModel::find($product->category_id);

        return $this->res(true, 'Cập nhật sản phẩm thành công', [
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->price,
            'category' => $category->name,
            'avatar' => $product->avatar,
        ]);
    }

    public function API_delete_product(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'  => 'required|numeric|exists:products,id',
        ], [
            'id.required' => 'ID không được để trống',
            'id.numeric' => 'ID không hợp lệ',
            'id.exists' => 'Không tìm thấy sản phẩm',
        ]);

        if ($validator->fails()) {
            return $this->res(false, $validator->errors()->first());
        }

        // Lấy tất cả sản phẩm trong order_product
        $orderProducts = OrderProductModel::where('product_id', $request->id)->get();

        foreach ($orderProducts as $orderProduct) {
            // Lấy order của order_product
            $order = OrderModel::find($orderProduct->order_id);

            // Nếu order không phải cart thì không cho xóa sản phẩm mà chỉ cho product_id = null
            if ($order->status != 'cart') {
                $orderProduct->product_id = null;
                $orderProduct->save();
            }
            else {
                // Nếu order là cart thì xóa order_product
                $orderProduct->delete();

                // Nếu order không còn order_product nào thì xóa order
                if (OrderProductModel::where('order_id', $order->id)->count() == 0) {
                    $order->delete();
                }
            }
        }

        $product = ProductModel::find($request->id);
        $product->delete();

        return $this->res(true, 'Xóa sản phẩm thành công');
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
