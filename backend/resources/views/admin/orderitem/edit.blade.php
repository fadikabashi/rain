<form class="form form-vertical" action="/admin/orderitem/{{ $orderitem->id }}" method="POST" enctype="multipart/form-data">
    @method('PATCH')
    @csrf
    <input type="hidden" value="{{ $orderitem->id }}" name="id">
    <div class="form-body">
        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <label for="email-id-vertical"> المنتج</label>
                    <select name="type_id" class="form-control" required>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}" @selected(old('product_id', $orderitem->product_id) )>
                                {{ $product->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="email-id-vertical"> رقم الطلب الرئيسي</label>
                    <select name="category_id" class="form-control" required>
                        @foreach ($orders as $order)
                            <option value="{{ $order->id }}" @selected(old('order_id' ,$orderitem->order_id))>
                                {{ $order->id }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="first-name-vertical">الكمية</label>
                    <input type="number" class="form-control @error('quantity') is-invalid @enderror"
                        name="quantity" placeholder="الكمية" value="{{ old('quantity' ,$orderitem->quantity) }}" required>
                </div>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary mr-1 mb-1">إضافة</button>
            </div>
        </div>
    </div>
</form>