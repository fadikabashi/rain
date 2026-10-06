<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCostumerRequest;
use App\Http\Requests\UpdateCostumerRequest;
use App\Models\Costumer;
use Illuminate\Http\Request;

class CostumerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $costumers = costumer::orderBy('id','Desc')->get();
        return view('admin.costumer.index')->with('costumers', $costumers);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.costumer.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCostumerRequest $request)
    {
        $costumer = new Costumer();
        $costumer->name = $request->name;
        $costumer->subject = $request->subject;
        $costumer->phone = $request->phone;
        $costumer->message = $request->message;
        $costumer ->save();
        toastr()->success('تم حفظ بيانات العميل بنجاح !!');
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
        $costumer = costumer::find($id);
        return view('admin.costumer.show')->with('costumer', $costumer);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $costumer = costumer::find($id);
        return view('admin.costumer.edit')->with('costumer', $costumer);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCostumerRequest $request, $id)
    {
        $costumer=costumer::find($id);
        $costumer->name = $request->name;
        $costumer->subject = $request->subject;
        $costumer->phone = $request->phone;
        $costumer->message = $request->message;
        $costumer ->save();
        toastr()->success('تم حفظ بيانات العميل بنجاح !!');
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
        $costumer =  costumer::find($id)->delete();
            toastr()->success('تم حذف بيانات العميل بنجاح !!');
            return back();
    }
}
