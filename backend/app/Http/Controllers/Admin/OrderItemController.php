<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderitemRequest;
use App\Models\Order;
use App\Models\Orderitem;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orderitems = Orderitem::orderBy('id','Desc')->get();
        $products= Product::all();
        $orders=Order::all();


        return view('admin.orderitem.index')
        ->with('orderitems',$orderitems)
        ->with('products',$products)
        ->with('orders',$orders)
        ;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.orderitem.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreOrderitemRequest $request)
    {
        $orderitem=new Orderitem();
        $orderitem->product_id = $request->product_id;
        $orderitem->order_id = $request->order_id;
        $orderitem->quantity = $request->quantity;
        $orderitem ->save();
        toastr()->success('تم حفظ بيانات الطلب بنجاح !!');
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
        $orderitem = Orderitem::find($id);
        $product = Product::find($orderitem->product_id);
        return view('admin.orderitem.edit')->with('orderitem', $orderitem)
        ->with('product', $product);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $orderitem = Orderitem::find($id);
        $products = Product::all();
        $orders = Order::all();
        return view('admin.orderitem.edit')->with('orderitem', $orderitem)
        ->with('products', $products)
        ->with('orders', $orders);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreOrderitemRequest $request, $id)
    {
        $orderitem = Orderitem::find($id);
        $orderitem->product_id = $request->product_id;
        $orderitem->order_id = $request->order_id;
        $orderitem->quantity = $request->quantity;
        $orderitem->save();
        toastr()->success('تم حفظ بيانات الطلب بنجاح !!');
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
        $orderitem = Orderitem::find($id);
        if ($orderitem) {
            $orderitem->delete();
        }
        toastr()->success('تم حذف بيانات الطلب بنجاح !!');
        return back();
    }
}
