<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="rtl">
<!-- BEGIN: Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="author" content="Laravel">
    <title>إستعادة كلمة السر</title>
    <link rel="apple-touch-icon" href="{{ asset('/app-assets/images/ico/apple-icon-120.png') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('/app-assets/images/ico/favicon.ico') }}">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600" rel="stylesheet">

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('/app-assets/vendors/css/vendors-rtl.min.css') }}">
    <!-- END: Vendor CSS-->

    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('/app-assets/css-rtl/bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/app-assets/css-rtl/bootstrap-extended.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/app-assets/css-rtl/colors.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/app-assets/css-rtl/components.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/app-assets/css-rtl/themes/dark-layout.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/app-assets/css-rtl/themes/semi-dark-layout.css') }}">

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css"
        href="{{ asset('/app-assets/css-rtl/core/menu/menu-types/vertical-menu.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('/app-assets/css-rtl/core/colors/palette-gradient.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/app-assets/css-rtl/pages/authentication.css') }}">
    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('/app-assets/css-rtl/custom-rtl.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/assets/css/style-rtl.css') }}">
    <!-- END: Custom CSS-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@200;300;400;500;700&display=swap"
        rel="stylesheet">

</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body
    class="vertical-layout vertical-menu-modern 1-column  navbar-floating footer-static bg-full-screen-image  blank-page blank-page"
    data-open="click" data-menu="vertical-menu-modern" data-col="1-column" style="font-family: 'Tajawal', sans-serif;">
    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
            </div>
            <div class="content-body">
                <section class="row flexbox-container">
                    <div class="col-xl-7 col-10 d-flex justify-content-center">
                        <div class="card bg-authentication rounded-0 mb-0 w-100">
                            <div class="row m-0">
                                <div class="col-lg-6 d-lg-block d-none text-center align-self-center p-0">
                                    <img src="../../../app-assets/images/pages/reset-password.png" alt="branding logo">
                                </div>
                                <div class="col-lg-6 col-12 p-0">
                                    <div class="card rounded-0 mb-0 px-2">
                                        <div class="card-header pb-1">
                                            <div class="card-title">
                                                <h4 class="mb-0">إستعادة كلمة السر</h4>
                                            </div>
                                        </div>
                                        <p class="px-2">إالرجاء إدخال كلمة السر</p>
                                        <div class="card-content">
                                            <div class="card-body pt-1">
                                                @if ($errors->any())
                                                    <div class="alert alert-danger mb-2">
                                                        <ul>
                                                            @foreach ($errors->all() as $error)
                                                                <li>{{ $error }}</li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @endif
                                                <form method="POST" action="{{ route('password.update') }}">
                                                    @csrf
                                                    <!-- Password Reset Token -->
                                                    <input type="hidden" name="token"
                                                        value="{{ $request->route('token') }}">
                                                    <fieldset class="form-label-group">
                                                        <input type="text"
                                                            class="form-control @error('email') is-invalid @enderror"
                                                            id="user-email" placeholder="البريد الإلكتروني" required
                                                            name="email"
                                                            value="{{ old('email', $request->email) }}" autofocus
                                                            readonly>
                                                        <label for="user-email">البريد الإلكتروني</label>
                                                    </fieldset>

                                                    <fieldset class="form-label-group">
                                                        <input type="password" class="form-control"
                                                            id="user-password" placeholder="كلمة السر"
                                                            name="password" required>
                                                        <label for="user-password">كلمة السر</label>
                                                    </fieldset>

                                                    <fieldset class="form-label-group">
                                                        <input type="password" class="form-control"
                                                            id="user-confirm-password" placeholder="تأكيد كلمة السر"
                                                            required name="password_confirmation">
                                                        <label for="user-confirm-password">تأكيد كلمة السر</label>
                                                    </fieldset>
                                                    <div class="row pt-2">
                                                        <div class="col-12 col-md-6 mb-1">
                                                            <a href="/admin/login"
                                                                class="btn btn-outline-primary btn-block px-75">الرجوع
                                                                لتسجيل الدخول</a>
                                                        </div>
                                                        <div class="col-12 col-md-6 mb-1">
                                                            <button class="btn btn-primary btn-block px-75">إسترجاع
                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

            </div>
        </div>
    </div>
    <!-- END: Content-->


    <!-- BEGIN: Vendor JS-->
    <script src="../../../app-assets/vendors/js/vendors.min.js"></script>
    <!-- BEGIN Vendor JS-->

    <!-- BEGIN: Page Vendor JS-->
    <!-- END: Page Vendor JS-->

    <!-- BEGIN: Theme JS-->
    <script src="../../../app-assets/js/core/app-menu.js"></script>
    <script src="../../../app-assets/js/core/app.js"></script>
    <script src="../../../app-assets/js/scripts/components.js"></script>
    <!-- END: Theme JS-->

    <!-- BEGIN: Page JS-->
    <!-- END: Page JS-->

</body>
<!-- END: Body-->

</html>
