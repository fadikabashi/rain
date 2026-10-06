<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreManfacturerRequest;
use App\Http\Requests\UpdateManfacturerRequest;
use App\Models\Manfacturer;
use Illuminate\Http\Request;

class ManfacturerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $manfacturers = Manfacturer::orderBy('id','Desc')->get();

        return view('admin.manfacturer.index')
        ->with('manfacturers',$manfacturers)
        ;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.manfacturer.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreManfacturerRequest $request)
    {
        $manfacturer=new Manfacturer();
        $manfacturer->name_en = $request->name_en;
        $manfacturer->name_ar = $request->name_ar;
        $manfacturer ->save();
        toastr()->success('تم حفظ بيانات المصنع بنجاح !!');
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
        $manfacturer = Manfacturer::find($id);
        return view('admin.manfacturer.show')->with('manfacturer', $manfacturer);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $manfacturer = manfacturer::find($id);
        return view('admin.manfacturer.edit')->with('manfacturer', $manfacturer);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateManfacturerRequest $request, $id)
    {
        $manfacturer=manfacturer::find($id);
        $manfacturer->name_en = $request->name_en;
        $manfacturer->name_ar = $request->name_ar;
        $manfacturer ->update();
        toastr()->success('تم حفظ بيانات المصنع بنجاح !!');
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
            $manfacturer =  Manfacturer::find($id)->delete();
            toastr()->success('تم حذف بيانات المصنع بنجاح !!');
            return back();
    }
}
