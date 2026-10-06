@extends((Session::get('locale') ==='ar'? 'layouts.main-rtl' : 'layouts.main'))
@section('content')
<div class="breadcrumb-area bg-grey">
    <div class="container">
        <div class="ho-breadcrumb">
            <ul>
                <li><a href="/">{{__('frontend.home')}}</a></li>
                <li><a href="{{ route('customer.account.dashboard') }}">{{ Session::get('locale') === 'ar' ? 'لوحة التحكم' : 'Dashboard' }}</a></li>
                <li>{{ Session::get('locale') === 'ar' ? 'الملف الشخصي' : 'Profile' }}</li>
            </ul>
        </div>
    </div>
</div>

<div class="account-profile-area bg-white ptb-30">
    <div class="container">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-3 col-md-4">
                @include('customer.partials.sidebar')
            </div>

            <!-- Main Content -->
            <div class="col-lg-9 col-md-8">
                <div class="account-profile-content">
                    <h2 class="account-title">{{ Session::get('locale') === 'ar' ? 'الملف الشخصي' : 'My Profile' }}</h2>

                    @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                    @endif

                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form method="POST" action="{{ route('customer.account.profile.update') }}" class="profile-form mt-30">
                        @csrf

                        <div class="form-group">
                            <label for="name">{{ Session::get('locale') === 'ar' ? 'الاسم الكامل' : 'Full Name' }}</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="email">{{ Session::get('locale') === 'ar' ? 'البريد الإلكتروني' : 'Email Address' }}</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="phone">{{ Session::get('locale') === 'ar' ? 'رقم الهاتف' : 'Phone Number' }}</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" name="phone" value="{{ old('phone', $user->phone) }}" required>
                            @error('phone')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                {{ Session::get('locale') === 'ar' ? 'حفظ التغييرات' : 'Save Changes' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.profile-form {
    background: #f8f9fa;
    padding: 30px;
    border-radius: 8px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #333;
}

.form-control {
    width: 100%;
    padding: 10px 15px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
}

.form-control:focus {
    border-color: #007bff;
    outline: none;
    box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
}
</style>
@endsection
