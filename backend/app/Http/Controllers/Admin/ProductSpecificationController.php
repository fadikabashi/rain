<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductSpecification;
use Illuminate\Http\Request;

class ProductSpecificationController extends Controller
{
    /**
     * Store a newly created product specification.
     *
     * @param Request $request
     * @param int $productId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, $productId)
    {
        $request->validate([
            'spec_key_en' => 'required|string|max:255',
            'spec_key_ar' => 'nullable|string|max:255',
            'spec_value_en' => 'required|string',
            'spec_value_ar' => 'nullable|string',
            'group' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer',
        ]);

        $product = Product::findOrFail($productId);

        $maxSortOrder = ProductSpecification::where('product_id', $productId)->max('sort_order') ?? 0;

        ProductSpecification::create([
            'product_id' => $productId,
            'spec_key_en' => $request->spec_key_en,
            'spec_key_ar' => $request->spec_key_ar,
            'spec_value_en' => $request->spec_value_en,
            'spec_value_ar' => $request->spec_value_ar,
            'group' => $request->group,
            'sort_order' => $request->sort_order ?? $maxSortOrder + 1,
        ]);

        toastr()->success('تم إضافة المواصفة بنجاح');
        return back();
    }

    /**
     * Update the specified product specification.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $spec = ProductSpecification::findOrFail($id);

        $request->validate([
            'spec_key_en' => 'required|string|max:255',
            'spec_key_ar' => 'nullable|string|max:255',
            'spec_value_en' => 'required|string',
            'spec_value_ar' => 'nullable|string',
            'group' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer',
        ]);

        $spec->update([
            'spec_key_en' => $request->spec_key_en,
            'spec_key_ar' => $request->spec_key_ar,
            'spec_value_en' => $request->spec_value_en,
            'spec_value_ar' => $request->spec_value_ar,
            'group' => $request->group,
            'sort_order' => $request->sort_order ?? $spec->sort_order,
        ]);

        toastr()->success('تم تحديث المواصفة بنجاح');
        return back();
    }

    /**
     * Remove the specified product specification.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $spec = ProductSpecification::findOrFail($id);
        $spec->delete();

        toastr()->success('تم حذف المواصفة بنجاح');
        return back();
    }
}
