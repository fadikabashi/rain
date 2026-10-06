<form class="form form-vertical" action="/admin/order/{{ $order->id }}" method="POST" enctype="multipart/form-data">
    @method('PATCH')
    @csrf
    <input type="hidden" value="{{ $order->id }}" name="id">
    <div class="form-body">
        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <label for="first-name-vertical">اسم العميل</label>
                    <input type="text" class="form-control @error('costumer_name') is-invalid @enderror"
                        name="costumer_name" placeholder="اسم العميل" value="{{ old('costumer_name',$order->costumer_name) }}" required>
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="first-name-vertical">رقم العميل</label>
                    <input type="number" class="form-control @error('costumer_number') is-invalid @enderror"
                        name="costumer_number" placeholder="رقم العميل" value="{{ old('costumer_number',$order->costumer_number) }}" required>
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="first-name-vertical">العنوان</label>
                    <input type="text" class="form-control @error('address') is-invalid @enderror"
                        name="address" placeholder="الإسم" value="{{ old('address' ,$order->address) }}" required>
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="first-name-vertical">ملحوظة</label>
                    <input type="text" class="form-control @error('note') is-invalid @enderror"
                        name="note" placeholder="ملحوظة" value="{{ old('note' ,$order->note) }}" required>
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="first-name-vertical">المجموع</label>
                    <input type= "number"step="0.01" min="0" max="10" class="form-control @error('total') is-invalid @enderror"
                        name="total" placeholder="المجموع" value="{{ old('total' ,$order->total) }}" required>
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="email-id-vertical">حالة الطلب</label>
                    <select name="order_status" class="form-control" required>
                        <option value="0" @selected(old('order_status' ,$order->order_status) == 0)>جديد</option>
                        <option value="1" @selected(old('order_status' ,$order->order_status) == 1)>تحت المعالجة</option>
                        <option value="2" @selected(old('order_status',$order->order_status) == 2)>تم التوصيل</option>
                    </select>
                </div>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary mr-1 mb-1">إضافة</button>
            </div>
        </div>
    </div>
</form>