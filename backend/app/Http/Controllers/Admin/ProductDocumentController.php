<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductDocumentController extends Controller
{
    /**
     * Store a newly created product document.
     *
     * @param Request $request
     * @param int $productId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, $productId)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf,doc,docx|max:10240', // 10MB max
            'title_en' => 'required|string|max:255',
            'title_ar' => 'nullable|string|max:255',
            'description_en' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'document_type' => 'required|in:manual,datasheet,installation_guide,wiring_diagram',
            'sort_order' => 'nullable|integer',
        ]);

        $product = Product::findOrFail($productId);

        // Handle file upload
        $file = $request->file('file');
        $filePath = $file->store('products/documents', 'public');
        $fileName = $file->getClientOriginalName();
        $fileSize = $file->getSize();
        $fileType = $file->getClientOriginalExtension();

        $maxSortOrder = ProductDocument::where('product_id', $productId)->max('sort_order') ?? 0;

        ProductDocument::create([
            'product_id' => $productId,
            'file_path' => $filePath,
            'file_name' => $fileName,
            'file_type' => $fileType,
            'file_size' => $fileSize,
            'document_type' => $request->document_type,
            'title_en' => $request->title_en,
            'title_ar' => $request->title_ar,
            'description_en' => $request->description_en,
            'description_ar' => $request->description_ar,
            'sort_order' => $request->sort_order ?? $maxSortOrder + 1,
        ]);

        toastr()->success('تم إضافة المستند بنجاح');
        return back();
    }

    /**
     * Update the specified product document.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $document = ProductDocument::findOrFail($id);

        $request->validate([
            'file' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'title_en' => 'required|string|max:255',
            'title_ar' => 'nullable|string|max:255',
            'description_en' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'document_type' => 'required|in:manual,datasheet,installation_guide,wiring_diagram',
            'sort_order' => 'nullable|integer',
        ]);

        // Handle file update
        if ($request->hasFile('file')) {
            // Delete old file
            if ($document->file_path && Storage::disk('public')->exists($document->file_path)) {
                Storage::disk('public')->delete($document->file_path);
            }

            $file = $request->file('file');
            $filePath = $file->store('products/documents', 'public');
            $fileName = $file->getClientOriginalName();
            $fileSize = $file->getSize();
            $fileType = $file->getClientOriginalExtension();

            $document->file_path = $filePath;
            $document->file_name = $fileName;
            $document->file_size = $fileSize;
            $document->file_type = $fileType;
        }

        $document->update([
            'title_en' => $request->title_en,
            'title_ar' => $request->title_ar,
            'description_en' => $request->description_en,
            'description_ar' => $request->description_ar,
            'document_type' => $request->document_type,
            'sort_order' => $request->sort_order ?? $document->sort_order,
        ]);

        toastr()->success('تم تحديث المستند بنجاح');
        return back();
    }

    /**
     * Remove the specified product document.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $document = ProductDocument::findOrFail($id);

        // Delete file
        if ($document->file_path && Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }

        $document->delete();

        toastr()->success('تم حذف المستند بنجاح');
        return back();
    }
}
