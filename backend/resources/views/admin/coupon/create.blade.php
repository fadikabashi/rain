@extends('layouts.app')
@section('title', 'إضافة كابون')
@section('content')
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-left mb-0">إضافة كابون</h2>
                </div>
            </div>
        </div>
        <div class="content-header-right text-md-right col-md-3 col-12 d-md-block d-none">
            <a href="{{ route('coupon.index') }}" class="btn btn-primary">
                <i class="fa fa-arrow-left"></i> رجوع
            </a>
        </div>
    </div>
    <div class="content-body">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">إضافة كابون جديد</h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form class="form form-vertical" action="{{ route('coupon.store') }}" method="POST">
                            @csrf
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="coupon-id">رمز الكابون (ID) <span class="text-danger">*</span></label>
                                            <input type="text" 
                                                id="coupon-id"
                                                class="form-control @error('id') is-invalid @enderror"
                                                name="id" 
                                                placeholder="أدخل رمز الكابون (حد أقصى 8 أحرف)" 
                                                value="{{ old('id') }}" 
                                                required
                                                maxlength="8">
                                            @error('id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted">رمز فريد للكابون (حد أقصى 8 أحرف)</small>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="coupon-amount">مبلغ الخصم (KWD) <span class="text-danger">*</span></label>
                                            <input type="number" 
                                                id="coupon-amount"
                                                step="0.01" 
                                                min="0" 
                                                class="form-control @error('amount') is-invalid @enderror"
                                                name="amount" 
                                                placeholder="أدخل مبلغ الخصم" 
                                                value="{{ old('amount') }}" 
                                                required>
                                            @error('amount')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted">مبلغ الخصم بالدينار الكويتي</small>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary mr-1 mb-1">
                                            <i class="fa fa-save"></i> حفظ
                                        </button>
                                        <a href="{{ route('coupon.index') }}" class="btn btn-secondary mr-1 mb-1">
                                            <i class="fa fa-times"></i> إلغاء
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
