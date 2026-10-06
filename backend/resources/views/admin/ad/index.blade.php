@extends('layouts.app')
@section('title', 'الاعلانات ')
@section('content')
<div class="content-header row">
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-left mb-0">الاعلانات </h2>
            </div>
        </div>
    </div>
</div>

   
    <div class="content-body">
        <div class="col-12"> 
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">الاعلانات </h4>
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
                                        <th>1 555</th>
                                        <th>2 555</th>
                                        <th>1 350</th>
                                        <th>2 350</th>
                                        <th>3 350 </th>
                                        <th>1 1110</th>
                                        <th>Shop</th>
                                        <th></th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($ads as $ad)
                                        <tr>
                                            <td><a href="/admin/ad/{{$ad->id}}">{{$ad->id}}</a></td>
                                            <td>
                                                <div class="avatar mr-1 avatar-xl">
                                                    <img src="{{ asset($ad->ad_1_555) }}" alt="avtar img holder"
                                                        style="object-fit: cover">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="avatar mr-1 avatar-xl">
                                                    <img src="{{ asset($ad->ad_2_555) }}" alt="avtar img holder"
                                                        style="object-fit: cover">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="avatar mr-1 avatar-xl">
                                                    <img src="{{ asset($ad->ad_1_350) }}" alt="avtar img holder"
                                                        style="object-fit: cover">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="avatar mr-1 avatar-xl">
                                                    <img src="{{ asset($ad->ad_2_350) }}" alt="avtar img holder"
                                                        style="object-fit: cover">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="avatar mr-1 avatar-xl">
                                                    <img src="{{ asset($ad->ad_3_350) }}" alt="avtar img holder"
                                                        style="object-fit: cover">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="avatar mr-1 avatar-xl">
                                                    <img src="{{ asset($ad->ad_1_1110) }}" alt="avtar img holder"
                                                        style="object-fit: cover">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="avatar mr-1 avatar-xl">
                                                    <img src="{{ asset($ad->ad_1_shop) }}" alt="avtar img holder"
                                                        style="object-fit: cover">
                                                </div>
                                            </td>
                                               <td> <button class="btn btn-success" name="edit_button"
                                                    value="{{ $ad->id }}" data-toggle="modal"
                                                    data-target="#edit_modal"><i class="fa fa-edit"></i></button>
                                                <button class="btn btn-danger mr-2"
                                                    onclick="if(confirm('هل أنت متأكد ؟')){document.getElementById('delete-users_{{ $ad->id }}').submit();}else{
                                            event.preventDefault();}"><i
                                                        class="fa fa-trash"></i></button>
                                                <form id="delete-users_{{ $ad->id }}"
                                                    action="/admin/ad/{{ $ad->id }}" method="POST"
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
                    <form class="form form-vertical" action="/admin/ad" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-body">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="email-id-vertical">صورة العرض الاولى 555</label>
                                        <input type="file" class="form-control @error('ad_1_555') is-invalid @enderror"
                                            name="ad_1_555">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="email-id-vertical">صورة العرض الثانية 555</label>
                                        <input type="file" class="form-control @error('ad_2_555') is-invalid @enderror"
                                            name="ad_2_555">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="email-id-vertical">صورة العرض الاولى 350</label>
                                        <input type="file" class="form-control @error('ad_1_350') is-invalid @enderror"
                                            name="ad_1_350">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="email-id-vertical">صورة العرض الثانية 350</label>
                                        <input type="file" class="form-control @error('ad_2_350') is-invalid @enderror"
                                            name="ad_2_350">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="email-id-vertical">صورة العرض الثالثة 350</label>
                                        <input type="file" class="form-control @error('ad_3_350') is-invalid @enderror"
                                            name="ad_3_350">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="email-id-vertical">صورة العرض الاولى 1110</label>
                                        <input type="file" class="form-control @error('ad_1_1110') is-invalid @enderror"
                                            name="ad_1_1110">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="email-id-vertical">صورة العرض صفحة المتجر</label>
                                        <input type="file" class="form-control @error('ad_1_shop') is-invalid @enderror"
                                            name="ad_1_shop">
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
                $.get("/admin/ad/" + edit_val + "/edit", function(data, status) {
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