<?php

namespace App\Http\Controllers;

use App\Models\Slider;
use App\Http\Requests\StoreSliderRequest;
use App\Http\Requests\UpdateSliderRequest;
use App\Services\Helpers\CacheHelper;
use Illuminate\Support\Facades\Storage;

class SliderController extends Controller
{
    /**
     * Store an uploaded slider image on the slider disk (storage/app/public/slider).
     * Returns the path for asset() (e.g. "storage/slider/users/xyz.jpg") or null on failure.
     */
    private function storeSliderImage($file): ?string
    {
        try {
            Storage::disk('slider')->makeDirectory('users');
            $path = $file->store('users', 'slider');
            return $path ? 'storage/slider/' . $path : null;
        } catch (\Throwable $e) {
            report($e);
            return null;
        }
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sliders = slider::orderBy('id','Asc')->get();
        return view('admin.slider.index')->with('sliders', $sliders);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.slider.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreSliderRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSliderRequest $request)
    {
        $slider = new slider();
        $slider->heading = $request->heading;
        $slider->sub_heading = $request->sub_heading;
        $slider->description = $request->description;
        if ($request->hasFile('ad_825') && $request->file('ad_825')->isValid()) {
            $path = $this->storeSliderImage($request->file('ad_825'));
            if ($path === null) {
                toastr()->error('فشل رفع صورة العرض. تحقق من صلاحيات مجلد storage.');
                return back();
            }
            $slider->ad_825 = $path;
        }
        $slider->save();
        CacheHelper::clearSlidersCache();
        toastr()->success('تم حفظ بيانات العميل بنجاح !!');
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Slider  $slider
     * @return \Illuminate\Http\Response
     */
    public function show(Slider $slider)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Slider  $slider
     * @return \Illuminate\Http\Response
     */
    public function edit( $id)
    {
        $slider = slider::find($id);
        return view('admin.slider.edit')->with('slider', $slider);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateSliderRequest  $request
     * @param  \App\Models\Slider  $slider
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSliderRequest $request,  $id)
    {
        $slider=slider::find($id);
        $slider->heading = $request->heading;
        $slider->sub_heading = $request->sub_heading;
        $slider->description = $request->description;
        if ($request->hasFile('ad_825') && $request->file('ad_825')->isValid()) {
            $path = $this->storeSliderImage($request->file('ad_825'));
            if ($path === null) {
                toastr()->error('فشل رفع صورة العرض. تحقق من صلاحيات مجلد storage.');
                return back();
            }
            $slider->ad_825 = $path;
        }
        $slider->save();
        CacheHelper::clearSlidersCache();
        toastr()->success('تم حفظ بيانات العميل بنجاح !!');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Slider  $slider
     * @return \Illuminate\Http\Response
     */
    public function destroy( $id)
    {
        Slider::find($id)?->delete();
        CacheHelper::clearSlidersCache();
        toastr()->success('تم حذف بيانات العميل بنجاح !!');
        return back();
    }
}
