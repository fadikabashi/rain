@extends('layouts.app')
@section('title', 'الزبائن ')
@section('content')
<div class="content-header row">
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-left mb-0"> الزبائن </h2>
            </div>
        </div>
    </div>
</div>
    
    <div class="content-body">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">كل الطلبات </h4>
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
                                        
                                        <th>اسم العميل</th>
                                        <th>رقم العميل</th>
                                        <th>حاله الطلب</th>
                                        <th>المجموع</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orders as $order)
                                        <tr>
                                            <td><a href="/admin/order/{{$order->id}}">{{$order->id}}</a></td>
                                            <td>{{ $order->costumer_name }}</td>
                                            <td>{{ $order->costumer_number }}</td>
                                            <td>
                                                @if ($order->order_status == 0)
                                                    <span class="badge badge-danger">جديد</span>
                                                @elseif ($order->order_status == 1)
                                                    <span class="badge badge-warning">تحت المعالجة</span>
                                                @else
                                                    <span class="badge badge-success"> تم التوصيل</span>
                                                @endif
                                            </td>
                                            <td>{{ $order->total }}</td>
                                               <td> <button class="btn btn-success" name="edit_button"
                                                    value="{{ $order->id }}" data-toggle="modal"
                                                    data-target="#edit_modal"><i class="fa fa-edit"></i></button>
                                                <button class="btn btn-danger mr-2"
                                                    onclick="if(confirm('هل أنت متأكد ؟')){document.getElementById('delete-users_{{ $order->id }}').submit();}else{
                                            event.preventDefault();}"><i
                                                        class="fa fa-trash"></i></button>
                                                <form id="delete-users_{{ $order->id }}"
                                                    action="/admin/order/{{ $order->id }}" method="POST"
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
                    <form class="form form-vertical" action="/admin/order" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-body">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="first-name-vertical">اسم العميل</label>
                                        <input type="text" class="form-control @error('costumer_name') is-invalid @enderror"
                                            name="costumer_name" placeholder="اسم العميل" value="{{ old('costumer_name') }}" required>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="first-name-vertical">رقم العميل</label>
                                        <input type="number" class="form-control @error('costumer_number') is-invalid @enderror"
                                            name="costumer_number" placeholder="رقم العميل" value="{{ old('costumer_number') }}" required>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="first-name-vertical">العنوان</label>
                                        <input type="text" class="form-control @error('address') is-invalid @enderror"
                                            name="address" placeholder="العنوان" value="{{ old('address') }}" required>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="first-name-vertical">ملحوظة</label>
                                        <input type="textarea" class="form-control @error('note') is-invalid @enderror"
                                            name="note" placeholder="ملحوظة" value="{{ old('note') }}" required>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="first-name-vertical">المجموع</label>
                                        <input type= "number"step="0.01" min="0" max="10" class="form-control @error('total') is-invalid @enderror"
                                            name="total" placeholder="المجموع" value="{{ old('total') }}" required>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="email-id-vertical">حالة الطلب</label>
                                        <select name="order_status" class="form-control" required>
                                            <option value="1" @selected(old('order_status') == 0)>جديد</option>
                                            <option value="0" @selected(old('order_status') == 1)>تحت المعالجة</option>
                                            <option value="0" @selected(old('order_status') == 2)>تم التوصيل</option>
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
                $.get("/admin/order/" + edit_val + "/edit", function(data, status) {
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