<?php

namespace App\Http\Controllers\Admin;

use App\Constants\OrderStatus;
use App\DTOs\OrderDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    public function __construct(
        protected OrderService $orderService
    ) {}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = $this->orderService->getPaginated(15);
        $statistics = $this->orderService->getStatistics();
        $order_count = $statistics['total'];
        $new_order = $statistics['new'];
        $received_order = $statistics['received'];
        $delivered_order = $statistics['delivered'];


        return view('admin.order.index')
        ->with('orders',$orders)
        ->with('order_count',$order_count)
        ->with('new_order',$new_order)
        ->with('received_order',$received_order)
        ->with('delivered_order',$delivered_order)
        ;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.order.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreOrderRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreOrderRequest $request)
    {
        try {
            $dto = OrderDTO::fromArray([
                'costumer_name' => $request->costumer_name,
                'costumer_number' => $request->costumer_number,
                'address' => $request->address,
                'total' => $request->total,
                'order_status' => $request->order_status ?? OrderStatus::PENDING,
                'note' => $request->note,
                'coupon_id' => $request->coupon_id,
            ]);

            $this->orderService->create($dto, []); // Empty cart items for admin-created orders
            
            toastr()->success('تم حفظ بيانات الطلب بنجاح !!');
            return back();
        } catch (\Exception $e) {
            toastr()->error('حدث خطأ أثناء حفظ الطلب');
            return back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id Order ID
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show($id)
    {
        try {
            $order = $this->orderService->getById($id);
            $orderitems = $order->orderitems;

            return view('admin.order.show')
                ->with('order', $order)
                ->with('orderitems', $orderitems);
        } catch (\Exception $e) {
            toastr()->error('الطلب غير موجود');
            return redirect()->route('order.index');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id Order ID
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit($id)
    {
        try {
            $order = $this->orderService->getById($id);
            return view('admin.order.edit')->with('order', $order);
        } catch (\Exception $e) {
            toastr()->error('الطلب غير موجود');
            return redirect()->route('order.index');
        }
    }

    /**
     * Update only the order status (fires OrderStatusChanged when applicable).
     */
    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'order_status' => ['required', 'integer', Rule::in(OrderStatus::all())],
        ]);

        try {
            $this->orderService->updateOrderStatus($order->id, (int) $validated['order_status']);
            toastr()->success('تم تحديث حالة الطلب بنجاح !!');
        } catch (\Exception $e) {
            toastr()->error('حدث خطأ أثناء تحديث حالة الطلب');
        }

        return back();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateOrderRequest $request
     * @param int $id Order ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateOrderRequest $request, $id)
    {
        try {
            $dto = OrderDTO::fromArray([
                'id' => $id,
                'costumer_name' => $request->costumer_name,
                'costumer_number' => $request->costumer_number,
                'address' => $request->address,
                'total' => $request->total,
                'order_status' => $request->order_status,
                'note' => $request->note,
                'coupon_id' => $request->coupon_id,
            ]);

            $this->orderService->update($id, $dto);
            
            toastr()->success('تم حفظ بيانات الطلب بنجاح !!');
            return back();
        } catch (\Exception $e) {
            toastr()->error('حدث خطأ أثناء تحديث الطلب');
            return back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id Order ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        try {
            $this->orderService->delete($id);
            toastr()->success('تم حذف بيانات الطلب بنجاح !!');
            return back();
        } catch (\Exception $e) {
           
            toastr()->error('حدث خطأ أثناء حذف الطلب');
            return back();
        }
    }
}
