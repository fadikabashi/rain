<form class="form form-vertical" action="/admin/product/{{ $product->id }}" method="POST" enctype="multipart/form-data">
    @method('PATCH')
    @csrf
    <input type="hidden" value="{{ $product->id }}" name="id">
    <div class="form-body">
        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <label for="first-name-vertical">الاسم الانجليزي</label>
                    <input type="text" class="form-control @error('name_en') is-invalid @enderror"
                        name="name_en" placeholder="الاسم الانجليزي" value="{{ old('name_en' ,$product->name_en) }}" required>
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="first-name-vertical">الاسم العربي</label>
                    <input type="text" class="form-control @error('name_ar') is-invalid @enderror"
                        name="name_ar" placeholder="الاسم العربي" value="{{ old('name_ar' , $product->name_ar) }}" required>
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="email-id-vertical">نوع المنتج</label>
                    <select name="type_id" class="form-control" required>
                        @foreach ($types as $type)
                            <option value="{{ $type->id }}" @selected(old('type_id', $product->type_id)) >
                                {{ $type->name_ar }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="email-id-vertical"> الاقسام </label>
                    <select name="category_id" class="form-control" required>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id )>
                                {{ $category->name_ar }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="email-id-vertical"> العلامة التجارية </label>
                    <select name="manfacturer_id" class="form-control" required>
                        @foreach ($manfacturers as $manfacturer)
                            <option value="{{ $manfacturer->id }}" @selected(old('manfacturer_id', $product->manfacturer_id) == $manfacturer->id)>
                                {{ $manfacturer->name_ar }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-12">
                <div class="form-group">
                    <label for="first-name-vertical">الوصف العربي</label>
                    <input type="textarea" class="form-control @error('description_ar') is-invalid @enderror"
                        name="description_ar" placeholder="الوصف العربي" value="{{ old('description_ar', $product->description_ar) }}" required>
                </div>
            </div>
            <div class="col-12">
                <div class="form-group">
                    <label for="first-name-vertical">الوصف الانجليزي</label>
                    <input type="textarea" class="form-control @error('description_en') is-invalid @enderror"
                        name="description_en" placeholder="الوصف الانجليزي" value="{{ old('description_en', $product->description_en) }}" required>
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                <label for="photo">صورة المنتج</label>
            
                <input type="file"
                       class="form-control @error('photo') is-invalid @enderror"
                       name="photo"
                       id="photo"
                       accept="image/*">
            
                @error('photo')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="first-name-vertical">الكمية</label>
                    <input type="number" class="form-control @error('quantity') is-invalid @enderror"
                        name="quantity" placeholder="الكمية" value="{{ old('quantity', $product->quantity) }}" required>
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="first-name-vertical">السعر</label>
                    <input type= "number"step="0.01" min="0" max="10000" class="form-control @error('price') is-invalid @enderror"
                        name="price" placeholder="السعر" value="{{ old('price', $product->price) }}" required>
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="first-name-vertical">التخفيض</label>
                    <input type= "number"step="0.01" min="0" max="1000" class="form-control @error('discount') is-invalid @enderror"
                        name="discount" placeholder="التخفيض" value="{{ old('discount', $product->discount) }}" required>
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="email-id-vertical">حاله المنتج</label>
                    <select name="is_available" class="form-control" required>
                        <option value="1" @selected(old('is_available', $product->is_available) == 1)>متوفر</option>
                        <option value="0" @selected(old('is_available', $product->is_available) == 0)>غير متوفر</option>
                    </select>
                </div>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-success mr-1 mb-1">تعديل</button>
            </div>
        </div>
    </div>
</form>

<!-- Product Images Management -->
<div class="card mt-3">
    <div class="card-header">
        <h4 class="card-title">إدارة صور المنتج</h4>
    </div>
    <div class="card-body">
        <!-- Add New Image Form -->
        <form action="{{ route('product.images.store', $product->id) }}" method="POST" enctype="multipart/form-data" class="mb-3">
            @csrf
            <div class="row">
                <div class="col-md-4">
                    <input type="file" name="image" class="form-control" accept="image/*" required>
                </div>
                <div class="col-md-3">
                    <input type="text" name="alt_text" class="form-control" placeholder="نص بديل للصورة">
                </div>
                <div class="col-md-2">
                    <label class="form-check-label">
                        <input type="checkbox" name="is_primary" value="1"> صورة رئيسية
                    </label>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary">إضافة صورة</button>
                </div>
            </div>
        </form>

        <!-- Existing Images -->
        @if($product->images && $product->images->count() > 0)
        <div class="row">
            @foreach($product->images as $image)
            <div class="col-md-3 mb-3">
                <div class="card">
                    <img src="{{ asset('storage/' . $image->image_path) }}" class="card-img-top" alt="{{ $image->alt_text }}" style="height: 150px; object-fit: cover;">
                    <div class="card-body">
                        @if($image->is_primary)
                        <span class="badge badge-success">صورة رئيسية</span>
                        @endif
                        <form action="{{ route('product.images.destroy', $image->id) }}" method="POST" class="mt-2">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('هل أنت متأكد من حذف هذه الصورة؟')">حذف</button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <p class="text-muted">لا توجد صور مضافة</p>
        @endif
    </div>
</div>

<!-- Product Specifications Management -->
<div class="card mt-3">
    <div class="card-header">
        <h4 class="card-title">إدارة مواصفات المنتج</h4>
    </div>
    <div class="card-body">
        <!-- Add New Specification Form -->
        <form action="{{ route('product.specifications.store', $product->id) }}" method="POST" class="mb-3">
            @csrf
            <div class="row">
                <div class="col-md-2">
                    <input type="text" name="spec_key_en" class="form-control" placeholder="المفتاح (إنجليزي)" required>
                </div>
                <div class="col-md-2">
                    <input type="text" name="spec_key_ar" class="form-control" placeholder="المفتاح (عربي)">
                </div>
                <div class="col-md-2">
                    <input type="text" name="spec_value_en" class="form-control" placeholder="القيمة (إنجليزي)" required>
                </div>
                <div class="col-md-2">
                    <input type="text" name="spec_value_ar" class="form-control" placeholder="القيمة (عربي)">
                </div>
                <div class="col-md-2">
                    <input type="text" name="group" class="form-control" placeholder="المجموعة (مثل: General)">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary">إضافة مواصفة</button>
                </div>
            </div>
        </form>

        <!-- Existing Specifications -->
        @if($product->specifications && $product->specifications->count() > 0)
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>المفتاح (إنجليزي)</th>
                    <th>المفتاح (عربي)</th>
                    <th>القيمة (إنجليزي)</th>
                    <th>القيمة (عربي)</th>
                    <th>المجموعة</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($product->specifications as $spec)
                <tr>
                    <td>{{ $spec->spec_key_en }}</td>
                    <td>{{ $spec->spec_key_ar }}</td>
                    <td>{{ $spec->spec_value_en }}</td>
                    <td>{{ $spec->spec_value_ar }}</td>
                    <td>{{ $spec->group }}</td>
                    <td>
                        <form action="{{ route('product.specifications.destroy', $spec->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('هل أنت متأكد؟')">حذف</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p class="text-muted">لا توجد مواصفات مضافة</p>
        @endif
    </div>
</div>

<!-- Product Videos Management -->
<div class="card mt-3">
    <div class="card-header">
        <h4 class="card-title">إدارة فيديوهات المنتج</h4>
    </div>
    <div class="card-body">
        <!-- Add New Video Form -->
        <form action="{{ route('product.videos.store', $product->id) }}" method="POST" class="mb-3">
            @csrf
            <div class="row">
                <div class="col-md-3">
                    <input type="url" name="video_url" class="form-control" placeholder="رابط الفيديو (YouTube/Vimeo)" required>
                </div>
                <div class="col-md-2">
                    <select name="video_type" class="form-control" required>
                        <option value="youtube">YouTube</option>
                        <option value="vimeo">Vimeo</option>
                        <option value="embed">Embed Code</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="text" name="title_en" class="form-control" placeholder="العنوان (إنجليزي)">
                </div>
                <div class="col-md-2">
                    <input type="text" name="title_ar" class="form-control" placeholder="العنوان (عربي)">
                </div>
                <div class="col-md-1">
                    <label class="form-check-label">
                        <input type="checkbox" name="is_featured" value="1"> مميز
                    </label>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary">إضافة فيديو</button>
                </div>
            </div>
        </form>

        <!-- Existing Videos -->
        @if($product->videos && $product->videos->count() > 0)
        <div class="row">
            @foreach($product->videos as $video)
            <div class="col-md-6 mb-3">
                <div class="card">
                    <div class="card-body">
                        <h6>{{ $video->title_en ?: $video->title_ar ?: 'فيديو بدون عنوان' }}</h6>
                        @if($video->is_featured)
                        <span class="badge badge-success">مميز</span>
                        @endif
                        <p class="text-muted small">{{ $video->video_type }} - {{ $video->video_url }}</p>
                        <form action="{{ route('product.videos.destroy', $video->id) }}" method="POST" class="mt-2">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('هل أنت متأكد؟')">حذف</button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <p class="text-muted">لا توجد فيديوهات مضافة</p>
        @endif
    </div>
</div>

<!-- Product Documents Management -->
<div class="card mt-3">
    <div class="card-header">
        <h4 class="card-title">إدارة مستندات المنتج</h4>
    </div>
    <div class="card-body">
        <!-- Add New Document Form -->
        <form action="{{ route('product.documents.store', $product->id) }}" method="POST" enctype="multipart/form-data" class="mb-3">
            @csrf
            <div class="row">
                <div class="col-md-3">
                    <input type="file" name="file" class="form-control" accept=".pdf,.doc,.docx" required>
                </div>
                <div class="col-md-2">
                    <input type="text" name="title_en" class="form-control" placeholder="العنوان (إنجليزي)" required>
                </div>
                <div class="col-md-2">
                    <input type="text" name="title_ar" class="form-control" placeholder="العنوان (عربي)">
                </div>
                <div class="col-md-2">
                    <select name="document_type" class="form-control" required>
                        <option value="manual">Manual</option>
                        <option value="datasheet">Datasheet</option>
                        <option value="installation_guide">Installation Guide</option>
                        <option value="wiring_diagram">Wiring Diagram</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary">إضافة مستند</button>
                </div>
            </div>
        </form>

        <!-- Existing Documents -->
        @if($product->documents && $product->documents->count() > 0)
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>العنوان</th>
                    <th>النوع</th>
                    <th>اسم الملف</th>
                    <th>الحجم</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($product->documents as $document)
                <tr>
                    <td>{{ $document->title_en ?: $document->title_ar }}</td>
                    <td><span class="badge badge-secondary">{{ $document->document_type }}</span></td>
                    <td>{{ $document->file_name }}</td>
                    <td>{{ $document->formatted_file_size }}</td>
                    <td>
                        <a href="{{ asset('storage/' . $document->file_path) }}" download class="btn btn-sm btn-info">تحميل</a>
                        <form action="{{ route('product.documents.destroy', $document->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('هل أنت متأكد؟')">حذف</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p class="text-muted">لا توجد مستندات مضافة</p>
        @endif
    </div>
</div>
