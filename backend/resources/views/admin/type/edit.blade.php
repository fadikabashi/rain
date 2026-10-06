<form class="form form-vertical" action="/admin/type/{{ $type->id }}" method="POST" enctype="multipart/form-data">
    @method('PATCH')
    @csrf
    <input type="hidden" value="{{ $type->id }}" name="id">
    <div class="form-body">
        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <label for="first-name-vertical">الإسم</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                        placeholder="الإسم" value="{{ old('name', $type->name) }}" required>
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="first-name-vertical">الوصف</label>
                    <input type="text" class="form-control @error('description') is-invalid @enderror" name="description"
                        placeholder="الوصف" value="{{ old('description', $type->description) }}" required>
                </div>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-success mr-1 mb-1">تعديل</button>
            </div>
        </div>
    </div>
</form>
