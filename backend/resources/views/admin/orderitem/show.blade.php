@extends('layouts.app')
@section('title', 'المنتجات ')
@section('content')
<section class="app-ecommerce-details">
    <div class="card">
        <!-- Product Details starts -->
        <div class="card-body">
            <div class="row my-2">
                <div class="col-12 col-md-5 d-flex align-items-center justify-content-center mb-2 mb-md-0">
                    <div class="d-flex align-items-center justify-content-center">
                        <img src="{{ asset($product->photo) }}" class="img-fluid product-img" alt="product image" />
                    </div>
                </div>
                <div class="col-12 col-md-7">
                    <h4>{{$product->name}}</h4>
                    <span class="card-text item-company">بواسطة <a href="javascript:void(0)" class="company-name">{{$manfacturer->name}}</a></span>
                    <div class="ecommerce-details-price d-flex flex-wrap mt-1">
                        <h4 class="item-price mr-1">السعر - {{$product->price}}</h4>
                        <h4 class="item-price mr-1">الكمية - {{$product->quantity}}</h4>
                        
                    </div>
                    <p class="card-text">التوفر - <span >@if ($product->is_available == 1)
                        <span class="badge badge-success">متوفر</span>
                    @else
                        <span class="badge badge-danger">غير متوفر</span>
                    @endif</span></p>
                    <p class="card-text">
                        {{$type->name}}
                    </p>
                    <p class="card-text">
                        {{$category->name}}
                    </p>
                    <p class="card-text">
                        {{$product->description}}
                    </p>
                    <hr />
                    
                    <hr />
                    <div class="d-flex flex-column flex-sm-row pt-1">
                        <button class="btn btn-success" name="edit_button"
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
                    </div>
                </div>
            </div>
        </div>
        <!-- Product Details ends -->
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