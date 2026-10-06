@extends((Session::get('locale') ==='ar'? 'layouts.main-rtl' : 'layouts.main'))
@section('content')
<div class="breadcrumb-area bg-grey">
    <div class="container">
        <div class="ho-breadcrumb">
            <ul>
                <li><a href="/">{{__('frontend.home')}}</a></li>
                <li>{{ Session::get('locale') === 'ar' ? 'التسجيل' : 'Register' }}</li>
            </ul>
        </div>
    </div>
</div>

<div class="register-area bg-white ptb-30">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                <div class="register-form">
                    <h2 class="register-title">{{ Session::get('locale') === 'ar' ? 'إنشاء حساب جديد' : 'Create New Account' }}</h2>
                    
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form method="POST" action="{{ route('customer.register') }}">
                        @csrf

                        <div class="form-group">
                            <label for="name">{{ Session::get('locale') === 'ar' ? 'الاسم' : 'Full Name' }}</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" required autofocus>
                            @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="email">{{ Session::get('locale') === 'ar' ? 'البريد الإلكتروني' : 'Email Address' }}</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email') }}" required>
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="phone">{{ Session::get('locale') === 'ar' ? 'رقم الهاتف' : 'Phone Number' }}</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" name="phone" value="{{ old('phone') }}" required>
                            @error('phone')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password">{{ Session::get('locale') === 'ar' ? 'كلمة المرور' : 'Password' }}</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   id="password" name="password" required>
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation">{{ Session::get('locale') === 'ar' ? 'تأكيد كلمة المرور' : 'Confirm Password' }}</label>
                            <input type="password" class="form-control" 
                                   id="password_confirmation" name="password_confirmation" required>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-block">
                                {{ Session::get('locale') === 'ar' ? 'التسجيل' : 'Register' }}
                            </button>
                        </div>

                        <div class="form-group text-center">
                            <p>
                                {{ Session::get('locale') === 'ar' ? 'لديك حساب بالفعل؟' : 'Already have an account?' }}
                                <a href="{{ route('customer.login') }}">{{ Session::get('locale') === 'ar' ? 'تسجيل الدخول' : 'Login' }}</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
