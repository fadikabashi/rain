@extends((Session::get('locale') ==='ar'? 'layouts.main-rtl' : 'layouts.main'))
@section('content')
<div class="breadcrumb-area bg-grey">
    <div class="container">
        <div class="ho-breadcrumb">
            <ul>
                <li><a href="/">{{__('frontend.home')}}</a></li>
                <li>{{ Session::get('locale') === 'ar' ? 'تسجيل الدخول' : 'Login' }}</li>
            </ul>
        </div>
    </div>
</div>

<div class="login-area bg-white ptb-30">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                <div class="login-form">
                    <h2 class="login-title">{{ Session::get('locale') === 'ar' ? 'تسجيل الدخول' : 'Login' }}</h2>
                    
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                    @endif

                    <form method="POST" action="{{ route('customer.login') }}">
                        @csrf

                        <div class="form-group">
                            <label for="email">{{ Session::get('locale') === 'ar' ? 'البريد الإلكتروني' : 'Email Address' }}</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email') }}" required autofocus>
                            @error('email')
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
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                <label class="form-check-label" for="remember">
                                    {{ Session::get('locale') === 'ar' ? 'تذكرني' : 'Remember Me' }}
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-block">
                                {{ Session::get('locale') === 'ar' ? 'تسجيل الدخول' : 'Login' }}
                            </button>
                        </div>

                        <div class="form-group text-center">
                            <p>
                                {{ Session::get('locale') === 'ar' ? 'ليس لديك حساب؟' : "Don't have an account?" }}
                                <a href="{{ route('customer.register') }}">{{ Session::get('locale') === 'ar' ? 'سجل الآن' : 'Register Now' }}</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
