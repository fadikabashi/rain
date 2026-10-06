<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVideo;
use Illuminate\Http\Request;

class ProductVideoController extends Controller
{
    /**
     * Store a newly created product video.
     *
     * @param Request $request
     * @param int $productId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, $productId)
    {
        $request->validate([
            'video_url' => 'required|string|max:500',
            'video_type' => 'required|in:youtube,vimeo,embed',
            'title_en' => 'nullable|string|max:255',
            'title_ar' => 'nullable|string|max:255',
            'description_en' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'thumbnail_url' => 'nullable|url|max:500',
            'is_featured' => 'boolean',
            'sort_order' => 'nullable|integer',
        ]);

        $product = Product::findOrFail($productId);

        // If this is set as featured, unset other featured videos
        if ($request->has('is_featured') && $request->is_featured) {
            ProductVideo::where('product_id', $productId)
                ->where('is_featured', true)
                ->update(['is_featured' => false]);
        }

        $maxSortOrder = ProductVideo::where('product_id', $productId)->max('sort_order') ?? 0;

        ProductVideo::create([
            'product_id' => $productId,
            'video_url' => $request->video_url,
            'video_type' => $request->video_type,
            'title_en' => $request->title_en,
            'title_ar' => $request->title_ar,
            'description_en' => $request->description_en,
            'description_ar' => $request->description_ar,
            'thumbnail_url' => $request->thumbnail_url,
            'is_featured' => $request->has('is_featured') && $request->is_featured,
            'sort_order' => $request->sort_order ?? $maxSortOrder + 1,
        ]);

        toastr()->success('تم إضافة الفيديو بنجاح');
        return back();
    }

    /**
     * Update the specified product video.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $video = ProductVideo::findOrFail($id);

        $request->validate([
            'video_url' => 'required|string|max:500',
            'video_type' => 'required|in:youtube,vimeo,embed',
            'title_en' => 'nullable|string|max:255',
            'title_ar' => 'nullable|string|max:255',
            'description_en' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'thumbnail_url' => 'nullable|url|max:500',
            'is_featured' => 'boolean',
            'sort_order' => 'nullable|integer',
        ]);

        // Handle featured video
        if ($request->has('is_featured') && $request->is_featured && !$video->is_featured) {
            ProductVideo::where('product_id', $video->product_id)
                ->where('is_featured', true)
                ->update(['is_featured' => false]);
        }

        $video->update([
            'video_url' => $request->video_url,
            'video_type' => $request->video_type,
            'title_en' => $request->title_en,
            'title_ar' => $request->title_ar,
            'description_en' => $request->description_en,
            'description_ar' => $request->description_ar,
            'thumbnail_url' => $request->thumbnail_url,
            'is_featured' => $request->has('is_featured') && $request->is_featured,
            'sort_order' => $request->sort_order ?? $video->sort_order,
        ]);

        toastr()->success('تم تحديث الفيديو بنجاح');
        return back();
    }

    /**
     * Remove the specified product video.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $video = ProductVideo::findOrFail($id);
        $video->delete();

        toastr()->success('تم حذف الفيديو بنجاح');
        return back();
    }
}
