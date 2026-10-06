<form class="form form-vertical" action="/admin/manfacturer/{{ $manfacturer->id }}" method="POST" enctype="multipart/form-data">
    @method('PATCH')
    @csrf
    <input type="hidden" value="{{ $manfacturer->id }}" name="id">
    <div class="form-body">
        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <label for="first-name-vertical">الاسم العربي</label>
                    <input type="text" class="form-control @error('name_ar') is-invalid @enderror"
                        name="name_ar" placeholder="الاسم العربي" value="{{ old('name_ar' , $manfacturer->name_ar) }}" required>
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="first-name-vertical">الاسم الانجليزي</label>
                    <input type="text" class="form-control @error('name_en') is-invalid @enderror"
                        name="name_en" placeholder="الاسم الانجليزي" value="{{ old('name_en', $manfacturer->name_en) }}" required>
                </div>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-success mr-1 mb-1">تعديل</button>
            </div>
        </div>
    </div>
</form>
