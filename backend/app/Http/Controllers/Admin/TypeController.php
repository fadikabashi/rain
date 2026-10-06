<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTypeRequest;
use App\Http\Requests\UpdateTypeRequest;
use App\Models\Product;
use App\Models\Type;
use Illuminate\Http\Request;

class TypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $types = Type::orderBy('id','Desc')->get();

        return view('admin.type.index')
        ->with('types',$types)
        ;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.type.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTypeRequest $request)
    {
        $type=new Type();
        $type->name_ar = $request->name_ar;
        $type->description_ar = $request->description_ar;
        $type->name_en = $request->name_en;
        $type->description_en = $request->description_en;
        $type ->save();
        toastr()->success('تم حفظ بيانات النوع بنجاح !!');
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
        $type = Type::find($id);
        return view('admin.type.show')->with('type', $type);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $type = type::find($id);
        return view('admin.type.edit')->with('type', $type);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTypeRequest $request, $id)
    {
        $type=type::find($id);
        $type->name_ar = $request->name_ar;
        $type->description_ar = $request->description_ar;
        $type->name_en = $request->name_en;
        $type->description_en = $request->description_en;
        $type ->update();
        toastr()->success('تم حفظ بيانات النوع بنجاح !!');
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
        $type = Type::find($id);

        if (!$type) {
            toastr()->error('النوع غير موجود.');
            return back();
        }

        

        $type->delete();
        toastr()->success('تم حذف بيانات النوع بنجاح !!');
        return back();
    }
}
