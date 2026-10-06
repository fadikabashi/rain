<form class="form form-vertical" action="/admin/coupon/{{ $coupon->id }}" method="POST" enctype="multipart/form-data">
    @method('PATCH')
    @csrf
    <input type="hidden" value="{{ $coupon->id }}" name="id">
    <div class="form-body">
        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <label for="first-name-vertical">ID</label>
                    <input type="text"  class="form-control @error('id') is-invalid @enderror"
                        name="id" placeholder="id" value="{{ old('id' , $coupon->id) }}" required>
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="first-name-vertical">القيمة</label>
                    <input type="number" step="0.01" min="0" class="form-control @error('amount') is-invalid @enderror" name="amount"
                        placeholder="القيمة" value="{{ old('amount', $coupon->amount) }}" required>
                </div>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-success mr-1 mb-1">تعديل</button>
            </div>
        </div>
    </div>
</form>
