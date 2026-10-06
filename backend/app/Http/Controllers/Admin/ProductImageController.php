<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductImageController extends Controller
{
    /**
     * Store a newly created product image.
     *
     * @param Request $request
     * @param int $productId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, $productId)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
            'alt_text' => 'nullable|string|max:255',
            'is_primary' => 'boolean',
        ]);

        $product = Product::findOrFail($productId);

        // Handle image upload
        $imagePath = $request->file('image')->store('products/images', 'public');

        // If this is set as primary, unset other primary images
        if ($request->has('is_primary') && $request->is_primary) {
            ProductImage::where('product_id', $productId)
                ->where('is_primary', true)
                ->update(['is_primary' => false]);
        }

        // Get max sort order
        $maxSortOrder = ProductImage::where('product_id', $productId)->max('sort_order') ?? 0;

        ProductImage::create([
            'product_id' => $productId,
            'image_path' => $imagePath,
            'alt_text' => $request->alt_text,
            'is_primary' => $request->has('is_primary') && $request->is_primary,
            'sort_order' => $maxSortOrder + 1,
        ]);

        toastr()->success('تم إضافة الصورة بنجاح');
        return back();
    }

    /**
     * Update the specified product image.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $image = ProductImage::findOrFail($id);

        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'alt_text' => 'nullable|string|max:255',
            'is_primary' => 'boolean',
            'sort_order' => 'nullable|integer',
        ]);

        // Handle image update
        if ($request->hasFile('image')) {
            // Delete old image
            if ($image->image_path && Storage::disk('public')->exists($image->image_path)) {
                Storage::disk('public')->delete($image->image_path);
            }
            $imagePath = $request->file('image')->store('products/images', 'public');
            $image->image_path = $imagePath;
        }

        // Handle primary image
        if ($request->has('is_primary') && $request->is_primary && !$image->is_primary) {
            ProductImage::where('product_id', $image->product_id)
                ->where('is_primary', true)
                ->update(['is_primary' => false]);
            $image->is_primary = true;
        } elseif ($request->has('is_primary') && !$request->is_primary) {
            $image->is_primary = false;
        }

        if ($request->has('alt_text')) {
            $image->alt_text = $request->alt_text;
        }

        if ($request->has('sort_order')) {
            $image->sort_order = $request->sort_order;
        }

        $image->save();

        toastr()->success('تم تحديث الصورة بنجاح');
        return back();
    }

    /**
     * Remove the specified product image.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $image = ProductImage::findOrFail($id);
        $productId = $image->product_id;

        // Delete image file
        if ($image->image_path && Storage::disk('public')->exists($image->image_path)) {
            Storage::disk('public')->delete($image->image_path);
        }

        $image->delete();

        toastr()->success('تم حذف الصورة بنجاح');
        return back();
    }

    /**
     * Update sort order for multiple images.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateSortOrder(Request $request)
    {
        $request->validate([
            'images' => 'required|array',
            'images.*.id' => 'required|exists:product_images,id',
            'images.*.sort_order' => 'required|integer',
        ]);

        foreach ($request->images as $imageData) {
            ProductImage::where('id', $imageData['id'])
                ->update(['sort_order' => $imageData['sort_order']]);
        }

        return response()->json(['success' => true]);
    }
}
