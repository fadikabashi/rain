@extends('layouts.app')
@section('title', 'المنتجات ')
@section('content')
<div class="content-header row">
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-left mb-0">المنتجات </h2>
            </div>
        </div>
    </div>
</div>
<div class="row">
<div class="col-xl-2 col-md-4 col-sm-6">
    <div class="card text-center">
        <div class="card-body">
            <div class="avatar bg-info p-50 mb-1">
                <div class="avatar-content">
                    <i data-feather="eye" class="font-medium-5 fa fa-shopping-bag"></i>
                </div>
            </div>
            <h2 class="font-weight-bolder">{{$products_counter}}</h2>
            <p class="card-text">المنتجات</p>
        </div>
    </div>
</div>
<div class="col-xl-2 col-md-4 col-sm-6">
    <div class="card text-center">
        <div class="card-body">
            <div class="avatar bg-warning p-50 mb-1">
                <div class="avatar-content">
                    <i data-feather="message-square" class="font-medium-5 fa fa-check"></i>
                </div>
            </div>
            <h2 class="font-weight-bolder">{{$in_stack}}</h2>
            <p class="card-text">متوفرة</p>
        </div>
    </div>
</div>
<div class="col-xl-2 col-md-4 col-sm-6">
    <div class="card text-center">
        <div class="card-body">
            <div class="avatar bg-danger p-50 mb-1">
                <div class="avatar-content">
                    <i data-feather="shopping-bag" class="font-medium-5 fa fa-remove"></i>
                </div>
            </div>
            <h2 class="font-weight-bolder">{{$out_off_stack}}</h2>
            <p class="card-text">غير متوفرة </p>
        </div>
    </div>
</div>

</div>
   
    <div class="content-body">
        <div class="col-12"> 
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">المنتجات </h4>
                    <a href="" class="btn btn-primary" data-toggle="modal" data-target="#default">إضافة </a>
                </div>

                <div class="card-content">
                    <div class="card-body card-dashboard">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="table-responsive">
                            <table class="table zero-configuration">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th></th>
                                        <th>الاسم العربي</th>
                                        <th>الاسم الانجليزي</th>
                                        <th>الكمية</th>
                                        <th>متوفر </th>
                                        <th></th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($products as $product)
                                        <tr>
                                            <td><a href="/admin/product/{{$product->id}}">{{$product->id}}</a></td>
                                            <td>
                                                <div class="avatar mr-1 avatar-xl">
                                                    <img src="{{ asset($product->photo) }}" alt="avtar img holder"
                                                        style="object-fit: cover">
                                                </div>
                                            </td>
                                            <td>{{ $product->name_ar }}</td>
                                            <td>{{ $product->name_en }}</td>
                                            <td>{{ $product->quantity }}</td>
                                            <td>
                                                @if ($product->is_available == 1)
                                                    <span class="badge badge-success">متوفر</span>
                                                @else
                                                    <span class="badge badge-danger">غير متوفر</span>
                                                @endif
                                            </td>
                                               <td> <button class="btn btn-success" name="edit_button"
                                                    value="{{ $product->id }}" data-toggle="modal"
                                                    data-target="#edit_modal"><i class="fa fa-edit"></i></button>
                                                <button class="btn btn-danger mr-2"
                                                    onclick="if(confirm('هل أنت متأكد ؟')){document.getElementById('delete-users_{{ $product->id }}').submit();}else{
                                            event.preventDefault();}"><i
                                                        class="fa fa-trash"></i></button>
                                                <form id="delete-users_{{ $product->id }}"
                                                    action="/admin/product/{{ $product->id }}" method="POST"
                                                    class="d-none">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>

                            </table>
                        </div>
                        <div class="mt-2 d-flex justify-content-center">
                            {{ $products->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade text-left" id="default" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel1">إضافة </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="form form-vertical" action="/admin/product" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-body">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="first-name-vertical">الاسم الانجليزي</label>
                                        <input type="text" class="form-control @error('name_en') is-invalid @enderror"
                                            name="name_en" placeholder="الاسم الانجليزي" value="{{ old('name_en') }}" required>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="first-name-vertical">الاسم العربي</label>
                                        <input type="text" class="form-control @error('name_ar') is-invalid @enderror"
                                            name="name_ar" placeholder="الاسم العربي" value="{{ old('name_ar') }}" required>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="email-id-vertical">نوع المنتج</label>
                                        <select name="type_id" class="form-control" required>
                                            @foreach ($types as $type)
                                                <option value="{{ $type->id }}" @selected(old('type_id') )>
                                                    {{ $type->name_ar }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="email-id-vertical">فئة المنتج</label>
                                        <select name="category_id" class="form-control" required>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}" @selected(old('category_id'))>
                                                    {{ $category->name_ar }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="email-id-vertical">فئة المنتج</label>
                                        <select name="manfacturer_id" class="form-control" required>
                                            @foreach ($manfacturers as $manfacturer)
                                                <option value="{{ $manfacturer->id }}" @selected(old('manfacturer_id') )>
                                                    {{ $manfacturer->name_ar }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="first-name-vertical">الوصف العربي</label>
                                        <input type="textarea" class="form-control @error('description_ar') is-invalid @enderror"
                                            name="description_ar" placeholder="الوصف العربي" value="{{ old('description_ar') }}" required>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="first-name-vertical">الوصف الانجليزي</label>
                                        <input type="textarea" class="form-control @error('description_en') is-invalid @enderror"
                                            name="description_en" placeholder="الوصف الانجليزي" value="{{ old('description_en') }}" required>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="first-name-vertical">صورة المنتج</label>
                                        <input type="file" class="form-control @error('photo') is-invalid @enderror"
                                            name="photo" placeholder="صورة المنتج" value="{{ old('photo') }}" required>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="first-name-vertical">الكمية</label>
                                        <input type="number" class="form-control @error('quantity') is-invalid @enderror"
                                            name="quantity" placeholder="الكمية" value="{{ old('quantity') }}" required>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="first-name-vertical">السعر</label>
                                        <input type= "number"step="0.01" min="0" max="1000" class="form-control @error('price') is-invalid @enderror"
                                            name="price" placeholder="السعر" value="{{ old('price') }}" required>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="first-name-vertical">التخفيض</label>
                                        <input type= "number"step="0.01" min="0" max="1000" class="form-control @error('discount') is-invalid @enderror"
                                            name="discount" placeholder="التخفيض" value="{{ old('discount') }}" required>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="email-id-vertical">حاله المنتج</label>
                                        <select name="is_available" class="form-control" required>
                                            <option value="1" @selected(old('is_available') == 1)>متوفر</option>
                                            <option value="0" @selected(old('is_available') == 0)>غير متوفر</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary mr-1 mb-1">إضافة</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">أغلاق</button>
                </div>
            </div>
        </div>
    </div>

    <!-- edit Modal -->
    <div class="modal fade text-left" id="edit_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel1">تعديل </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-section">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">أغلاق</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scriptjs')
    <script>
        $(document).ready(function() {
            $("button[name='edit_button']").click(function() {

                var edit_val = this.value;

                $(".form-section").html(" ");
                $(".form-section").append(
                    "<center><img src='{{ asset('loader.gif') }}'  width='300px'/></center>"
                );
                $.get("/admin/product/" + edit_val + "/edit", function(data, status) {
                    $(".form-section").html(data);
                }).fail(function() {
                    $(".form-section").html(" ");
                    $(".form-section").append(
                        "<div class='alert alert-danger' role='alert'>عذراً , حصل خطأ ما !!</div>"
                    );
                });
            });
        });
    </script>
@endsection