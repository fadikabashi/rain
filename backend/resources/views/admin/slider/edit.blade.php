<form class="form form-vertical" action="/admin/slider/{{ $slider->id }}" method="POST" enctype="multipart/form-data">
    @method('PATCH')
    @csrf
    <input type="hidden" value="{{ $slider->id }}" name="id">
    <div class="form-body">
        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <label for="email-id-vertical">صورة العرض 825</label>
                    <input type="file" class="form-control @error('ad_825') is-invalid @enderror"
                        name="ad_825">
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="first-name-vertical">العنوان</label>
                    <input type="text" class="form-control @error('heading') is-invalid @enderror"
                        name="heading" placeholder="العنوان" value="{{ old('heading' , $slider->heading) }}" required>
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="first-name-vertical">العنوان الثانوي</label>
                    <input type="text" class="form-control @error('sub_heading') is-invalid @enderror"
                        name="sub_heading" placeholder="العنوان الثانوي" value="{{ old('sub_heading', $slider->sub_heading) }}" required>
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="first-name-vertical">الوصف</label>
                    <input type="text" class="form-control @error('description') is-invalid @enderror"
                        name="description" placeholder="الوصف" value="{{ old('description', $slider->description) }}" required>
                </div>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-success mr-1 mb-1">تعديل</button>
            </div>
        </div>
    </div>
</form>
