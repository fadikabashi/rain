<form class="form form-vertical" action="/admin/category/{{ $category->id }}" method="POST" enctype="multipart/form-data">
    @method('PATCH')
    @csrf
    <input type="hidden" value="{{ $category->id }}" name="id">
    <div class="form-body">
        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <label for="first-name-vertical">الاسم الانجليزي</label>
                    <input type="text" class="form-control @error('name_en') is-invalid @enderror" name="name_en"
                        placeholder="الاسم الانجليزي" value="{{ old('name_en', $category->name_en) }}" required>
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="first-name-vertical">الاسم العربي</label>
                    <input type="text" class="form-control @error('name_ar') is-invalid @enderror" name="name_ar"
                        placeholder="الاسم العربي" value="{{ old('name_ar', $category->name_ar) }}" required>
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="first-name-vertical">ايقونة القسم</label>
                    <input type="file" class="form-control" name="image" required>
                </div>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-success mr-1 mb-1">تعديل</button>
            </div>
        </div>
    </div>
</form>
