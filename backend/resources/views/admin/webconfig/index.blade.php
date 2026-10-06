@extends('layouts.app')
@section('title', 'الانواع ')
@section('content')
    <!-- Modal -->
        <div class=" modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel1">ادراة الموقع</h4>
                </div>
                <div class="modal-body">
                    <form class="form form-vertical" action="/admin/webconfig" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-body">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="first-name-vertical">العنوان العربي</label>
                                        <input type="text" class="form-control @error("config('address_en')") is-invalid @enderror"
                                            name="config('address_ar')" placeholder="" value="{{ old("config('address_ar')") }}" required>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="first-name-vertical">العنوان انجيلزي</label>
                                        <input type="text" class="form-control @error("config('address_en')") is-invalid @enderror"
                                            name="config('address_en')" placeholder="" value="{{ old("config('address_en')") }}" required>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="first-name-vertical">لينك الفيس</label>
                                        <input type="text" class="form-control @error("config('facebook')") is-invalid @enderror"
                                            name="config('facebook')" placeholder="" value="{{ old("config('facebook')") }}" required>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="first-name-vertical"> لينك الانستجرام</label>
                                        <input type="text" class="form-control @error("config('instagram')") is-invalid @enderror"
                                            name="config('instagram')" placeholder="" value="{{ old("config('instagram')") }}" required>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="password-vertical">عننا عربي </label>
                                        <textarea class="form-control ckeditor" name="config('aboutus_ar')" required></textarea>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="password-vertical">عننا انجليزي </label>
                                        <textarea class="form-control ckeditor" name="config('aboutus_en')" required></textarea>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="password-vertical"> رؤيتنا عربي </label>
                                        <textarea class="form-control ckeditor" name="config('our_vision_ar')" required></textarea>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="password-vertical">رؤيتنا انجليزي </label>
                                        <textarea class="form-control ckeditor" name="config('our_vision_en')" required></textarea>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary mr-1 mb-1">تعديل</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
    </div>

@endsection