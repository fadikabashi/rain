<?php

namespace App\Http\Controllers\Admin;

use App\Models\Type;
use App\DTOs\ProductDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Manfacturer;
use App\Services\ProductService;

class ProductController extends Controller
{
    public function __construct(
        protected ProductService $productService
    ) {}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Use pagination for better performance with large datasets
        $products = $this->productService->getPaginated(15);

        // Get statistics efficiently (can be cached)
        $statistics = cache()->remember(
            \App\Services\Helpers\CacheHelper::PRODUCT_STATISTICS,
            \App\Services\Helpers\CacheHelper::TTL_SHORT,
            function () {
                $allProducts = $this->productService->getAll();
                return [
                    'total' => $allProducts->count(),
                    'out_of_stock' => $allProducts->where('is_available', false)->count(),
                    'in_stock' => $allProducts->where('is_available', true)->count(),
                ];
            }
        );

        // Cache static reference data
        $types = cache()->remember(
            \App\Services\Helpers\CacheHelper::TYPES,
            \App\Services\Helpers\CacheHelper::TTL_LONG,
            fn() => Type::all()
        );
        $categories = cache()->remember(
            \App\Services\Helpers\CacheHelper::CATEGORIES,
            \App\Services\Helpers\CacheHelper::TTL_LONG,
            fn() => Category::all()
        );
        $manfacturers = cache()->remember(
            \App\Services\Helpers\CacheHelper::MANFACTURERS,
            \App\Services\Helpers\CacheHelper::TTL_LONG,
            fn() => Manfacturer::all()
        );

        return view('admin.product.index')
            ->with('products', $products)
            ->with('products_counter', $statistics['total'])
            ->with('out_off_stack', $statistics['out_of_stock'])
            ->with('in_stack', $statistics['in_stock'])
            ->with('types', $types)
            ->with('categories', $categories)
            ->with('manfacturers', $manfacturers);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.product.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreProductRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreProductRequest $request)
    {
        try {
            $dto = ProductDTO::fromArray([
                'name_en' => $request->name_en,
                'name_ar' => $request->name_ar,
                'description_en' => $request->description_en,
                'description_ar' => $request->description_ar,
                'price' => $request->price,
                'quantity' => $request->quantity,
                'discount' => $request->discount,
                'is_available' => $request->is_available ?? false,
                'category_id' => $request->category_id,
                'type_id' => $request->type_id,
                'manfacturer_id' => $request->manfacturer_id,
                
            ]);

            $this->productService->create($dto, $request->file('photo'));

            toastr()->success('تم حفظ بيانات المنتج بنجاح !!');
            return back();
        } catch (\Exception $e) {
            toastr()->error('حدث خطأ أثناء حفظ المنتج');
            return back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id Product ID
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show($id)
    {
        try {
            $product = $this->productService->getById($id);
            $type = $product->type;
            $category = $product->category;
            $manfacturer = $product->manfacturer;

            return view('admin.product.show')
                ->with('product', $product)
                ->with('type', $type)
                ->with('category', $category)
                ->with('manfacturer', $manfacturer);
        } catch (\App\Exceptions\ProductNotFoundException $e) {
            toastr()->error('المنتج غير موجود');
            return redirect()->route('product.index');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id Product ID
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit($id)
    {
        try {
            // Load product with all relationships for editing
            $product = $this->productService->getById($id, [
                'category',
                'type',
                'manfacturer',
                'images',
                'specifications',
                'videos',
                'documents'
            ]);

            // Use cached reference data
            $types = cache()->remember(
                \App\Services\Helpers\CacheHelper::TYPES,
                \App\Services\Helpers\CacheHelper::TTL_LONG,
                fn() => Type::all()
            );
            $categories = cache()->remember(
                \App\Services\Helpers\CacheHelper::CATEGORIES,
                \App\Services\Helpers\CacheHelper::TTL_LONG,
                fn() => Category::all()
            );
            $manfacturers = cache()->remember(
                \App\Services\Helpers\CacheHelper::MANFACTURERS,
                \App\Services\Helpers\CacheHelper::TTL_LONG,
                fn() => Manfacturer::all()
            );

            return view('admin.product.edit')
                ->with('product', $product)
                ->with('types', $types)
                ->with('categories', $categories)
                ->with('manfacturers', $manfacturers);
        } catch (\App\Exceptions\ProductNotFoundException $e) {
            toastr()->error('المنتج غير موجود');
            return redirect()->route('product.index');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateProductRequest $request
     * @param int $id Product ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateProductRequest $request, $id)
    {
        try {
            $dto = ProductDTO::fromArray([
                'id' => $id,
                'name_en' => $request->name_en,
                'name_ar' => $request->name_ar,
                'description_en' => $request->description_en,
                'description_ar' => $request->description_ar,
                'price' => $request->price ?? 0,
                'quantity' => $request->quantity,
                'discount' => $request->discount,
                'is_available' => $request->is_available ?? false,
                'category_id' => $request->category_id,
                'type_id' => $request->type_id,
                'manfacturer_id' => $request->manfacturer_id,
            ]);
    
            if($request->hasFile('photo')){
                 $this->productService->update($id, $dto, $request->file('photo'));
            }else {
                 $this->productService->update($id, $dto);
            }
           

            toastr()->success('تم حفظ بيانات المنتج بنجاح !!');
            return back();
        } catch (\App\Exceptions\ProductNotFoundException $e) {
            toastr()->error('المنتج غير موجود');
            return back();
        } catch (\Exception $e) {
            toastr()->error('حدث خطأ أثناء تحديث المنتج');
            return back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id Product ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        try {
            $this->productService->delete($id);
            toastr()->success('تم حذف بيانات المنتج بنجاح !!');
            return back();
        } catch (\App\Exceptions\ProductNotFoundException $e) {
            toastr()->error('المنتج غير موجود');
            return back();
        } catch (\Exception $e) {
            // Show specific error message if product has orders
            $errorMessage = $e->getMessage();
            if (str_contains($errorMessage, 'order')) {
                toastr()->error('لا يمكن حذف المنتج لأنه موجود في طلبات. يرجى حذف الطلبات المرتبطة أولاً.');
            } else {
                toastr()->error('حدث خطأ أثناء حذف المنتج: ' . $errorMessage);
            }
            return back();
        }
    }
}
