<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use App\Services\Helpers\PhotoHandler;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::orderBy('id', 'Desc')->get();

        return view('admin.category.index')
            ->with('categories', $categories);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.category.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCategoryRequest $request)
    {
        $photoPath = PhotoHandler::store($request->file('image'));
        $category = new category();
        $category->name_ar = $request->name_ar;
        $category->name_en = $request->name_en;
        $category->image = $photoPath;
        $category->save();
        toastr()->success('تم حفظ بيانات الفئة بنجاح !!');
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = Category::find($id);
        return view('admin.category.show')->with('category', $category);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $category = category::find($id);
        return view('admin.category.edit')->with('category', $category);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCategoryRequest $request, $id)
    {
        $category = category::find($id);
        $image = '';
        if ($request->has('image') && !empty($request->file('image'))) {
            $newPhotoPath = PhotoHandler::replace($category->image, $request->file('image'));
            if ($newPhotoPath) {
                $image = $newPhotoPath;
            }
        } else {
            // Keep existing photo by removing it from update data
            $image = $category->image;
        }

        $category->name_ar = $request->name_ar;
        $category->name_en = $request->name_en;
        $category->image = $image;
        $category->update();
        toastr()->success('تم حفظ بيانات الفئة بنجاح !!');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::find($id);
        PhotoHandler::delete($category->image);
        $category->delete();
        toastr()->success('تم حذف بيانات الفئة بنجاح !!');
        return back();
    }
}
