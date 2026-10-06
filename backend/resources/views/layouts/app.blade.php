<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="rtl">
<!-- BEGIN: Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description"
        content="Vuexy admin is super flexible, powerful, clean &amp; modern responsive bootstrap 4 admin template with unlimited possibilities.">
    <meta name="keywords"
        content="admin template, Vuexy admin template, dashboard template, flat admin template, responsive admin template, web app">
    <meta name="author" content="PIXINVENT">
    <title>لوحة التحكم - @yield('title')</title>
    <link rel="apple-touch-icon" href="{{ asset('/app-assets/images/ico/apple-icon-120.png') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('/app-assets/images/logo/Rain-tech.ico') }}">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600" rel="stylesheet">

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('/app-assets/vendors/css/vendors-rtl.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/app-assets/vendors/css/ui/prism.min.css') }}">
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
    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('/assets/css/style-rtl.css') }}">
    <!-- END: Custom CSS-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@200;300;400;500;700&display=swap"
        rel="stylesheet">
</head>
<!-- END: Head-->

<!-- BEGIN: Body-->
<style>
    a,
    h1,
    h2,
    h3,
    h4,
    h5,
    h6,
    span {
        font-family: 'Tajawal', sans-serif
    }
</style>

<body class="vertical-layout vertical-menu-modern 2-columns  navbar-sticky footer-static  " data-open="click"
    data-menu="vertical-menu-modern" data-col="2-columns" style="font-family: 'Tajawal', sans-serif;">

    <!-- BEGIN: Header-->
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <nav class="header-navbar navbar-expand-lg navbar navbar-with-menu fixed-top navbar-light navbar-shadow">
        <div class="navbar-wrapper">
            <div class="navbar-container content">
                <div class="navbar-collapse" id="navbar-mobile">
                    <div class="navbar-container d-flex content">
                        <div class="bookmark-wrapper d-flex align-items-center">
                            <ul class="nav navbar-nav d-xl-none">
                                <li class="nav-item"><a class="nav-link menu-toggle" href="javascript:void(0);"><i class="feather icon-menu" data-feather="menu"></i></a></li>
                            </ul>
                        </div>
                    </div>

                    <ul class="nav navbar-nav float-left ml-auto">
                        @php
                            $currentLocale = session('locale', 'ar');
                            $isArabic = $currentLocale === 'ar';
                        @endphp
                        <li class="dropdown dropdown-language nav-item">
                            <a class="dropdown-toggle nav-link" id="dropdown-flag" href="#" data-toggle="dropdown" 
                               aria-haspopup="true" aria-expanded="false">
                                <i class="flag-icon {{ $isArabic ? 'flag-icon-kw' : 'flag-icon-us' }}"></i>
                                <span class="selected-language">{{ $isArabic ? 'العربية' : 'English' }}</span>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="dropdown-flag">
                                <a class="dropdown-item {{ !$isArabic ? 'selected' : '' }}" 
                                   href="{{ route('change_language', 'en') }}" 
                                   data-language="en">
                                    <i class="flag-icon flag-icon-us"></i> English
                                </a>
                                <a class="dropdown-item {{ $isArabic ? 'selected' : '' }}" 
                                   href="{{ route('change_language', 'ar') }}" 
                                   data-language="ar">
                                    <i class="flag-icon flag-icon-kw"></i> العربية
                                </a>
                            </div>
                        </li>
                        <li class="nav-item d-none d-lg-block"><a class="nav-link nav-link-style"><i class="ficon" data-feather="moon"></i></a></li>
                        <li class="nav-item d-none d-lg-block"><a class="nav-link nav-link-expand"><i
                                    class="ficon feather icon-maximize"></i></a></li>

                        <li class="dropdown dropdown-user nav-item"><a
                                class="dropdown-toggle nav-link dropdown-user-link" href="#"
                                data-toggle="dropdown">
                                <div class="user-nav d-sm-flex d-none"><span
                                        class="user-name text-bold-600">{{ Auth::user()->name }}</span><span
                                        class="user-status">متوفر</span></div><span><img class="round"
                                        src="{{ asset(Auth::user()->profile_pic) }}" alt="avatar" height="40"
                                        width="40"></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right"><a class="dropdown-item"
                                    href="/admin/edit_profile"><i class="feather icon-user"></i> تعديل البروفايل</a>
                                <div class="dropdown-divider"></div><a class="dropdown-item"
                                    href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();"><i
                                        class="feather icon-power"></i> تسجيل
                                    الخروج</a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                    style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    <!-- END: Header-->


    <!-- BEGIN: Main Menu-->
    <div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true">
        <div class="navbar-header">
            <ul class="nav navbar-nav flex-row">
                <li class="nav-item mr-auto"><a class="navbar-brand"
                        href="/admin/dashboard/">
                        <div class="brand-logo"></div>
                        <h2 class="brand-text mb-0">لوحة التحكم</h2>
                    </a></li>
                <li class="nav-item nav-toggle"><a class="nav-link modern-nav-toggle pr-0" data-toggle="collapse"><i
                            class="feather icon-x d-block d-xl-none font-medium-4 primary toggle-icon"></i><i
                            class="toggle-icon feather icon-disc font-medium-4 d-none d-xl-block primary"
                            data-ticon="icon-disc"></i></a></li>
            </ul>
        </div>
        <div class="shadow-bottom"></div>
        <div class="main-menu-content">
            <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
                <li class="nav-item  {{ request()->is('admin/dashboard') ? 'active' : '' }}"><a
                        href="/admin/dashboard"><i class="feather icon-home"></i><span class="menu-title"
                            data-i18n="Dashboard">الرئيسية</span></a>
                </li>
                <li class=" navigation-header"><span>ادارة صفحة المتجر</span>
                <li class="nav-item {{ request()->is('admin/ad') ? 'active' : '' }}"><a href="/admin/ad/"><i class="feather icon-monitor"></i><span class="menu-title"
                        data-i18n="Starter kit">  الاعلانات</span></a>

                </li>
                <li class="nav-item {{ request()->is('admin/slider') ? 'active' : '' }}"><a href="/admin/slider/"><i class="feather icon-sidebar"></i><span class="menu-title"
                    data-i18n="Starter kit"> السلايدر </span></a>

                </li>
                <li class=" navigation-header"><span>ادارة الطلبات</span>
                </li>
                <li class="nav-item {{ request()->is('admin/orderitem') ? 'active' : '' }}"><a href="/admin/orderitem/"><i class="feather icon-gift"></i><span class="menu-title"
                    data-i18n="Starter kit"> وحدات الطلب</span></a>

              </li>
                <li class="nav-item {{ request()->is('admin/order') ? 'active' : '' }}"><a href="/admin/order/"><i class="feather icon-shopping-cart"></i><span class="menu-title"
                            data-i18n="Starter kit"> الطلبات</span></a>

                </li>
                <li class="nav-item {{ request()->is('admin/coupon*') ? 'active' : '' }}"><a href="{{ route('coupon.index') }}"><i class="feather icon-tag"></i><span class="menu-title"
                            data-i18n="Coupons"> الكابونات</span></a>

                </li>
                <li class=" navigation-header"><span>ادارة المنتجات</span>
                </li>
                <li class="nav-item {{ request()->is('admin/product') ? 'active' : '' }}"><a href="/admin/product/"><i class="feather icon-package"></i><span class="menu-title"
                    data-i18n="Starter kit"> المنتجات</span></a>

                </li>
                <li class="nav-item {{ request()->is('admin/category') ? 'active' : '' }}"><a href="/admin/category/"><i class="feather icon-shopping-bag"></i><span class="menu-title"
                            data-i18n="Starter kit"> الاقسام </span></a>

                </li>
                <li class="nav-item {{ request()->is('admin/type') ? 'active' : '' }}"><a href="/admin/type/"><i class="feather icon-type"></i><span class="menu-title"
                            data-i18n="Starter kit"> منتج وخدمة </span></a>

                </li><li class="nav-item {{ request()->is('admin/manfacturer') ? 'active' : '' }}"><a href="/admin/manfacturer/"><i class="feather icon-truck"></i><span class="menu-title"
                            data-i18n="Starter kit">  العلامات التجارية  </span></a>

                </li>
                <li class=" navigation-header"><span>ادارة رسائل العملاء</span>
                </li>
                <li class="nav-item {{ request()->is('admin/reviews*') ? 'active' : '' }}"><a href="{{ route('admin.reviews.index') }}"><i class="feather icon-star"></i><span class="menu-title"
                    data-i18n="Reviews">التقييمات</span></a>
                </li>
                <li class="nav-item {{ request()->is('admin/quote-requests*') ? 'active' : '' }}"><a href="{{ route('admin.quote-requests.index') }}"><i class="feather icon-file-text"></i><span class="menu-title"
                    data-i18n="Quote Requests">طلبات الأسعار</span></a>
                </li>
            {{-- </li><li class="nav-item {{ request()->is('admin/costumer') ? 'active' : '' }}"><a href="/admin/costumer/"><i class="feather icon-user"></i><span class="menu-title"
                data-i18n="Starter kit"> العملاء</span></a> --}}

                </li>

                <li class=" navigation-header"><span>الإعدادات</span>
                </li>
                <li class="nav-item {{ request()->is('admin/webconfig') ? 'active' : '' }}"><a href="/admin/webconfig"><i
                    class="feather icon-settings "></i><span class="menu-title" data-i18n="Email">
                    محتوى الموقع </span></a>
        </li>

                <li class="nav-item {{ request()->is('admin/users') ? 'active' : '' }}"><a href="/admin/users"><i
                            class="fa fa-lock"></i><span class="menu-title" data-i18n="Email">مدراء
                            النظام</span></a>
                </li>
            </ul>
        </div>
    </div>
    <!-- END: Main Menu-->

    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            @yield('content')
        </div>
    </div>
    <!-- END: Content-->

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    <!-- BEGIN: Footer-->
    <footer class="footer footer-static footer-light">
        <p class="clearfix blue-grey lighten-2 mb-0"><span class="float-md-left d-block d-md-inline-block mt-25">حقوق
                الطبع {{ date('Y') }}<a class="text-bold-800 grey darken-2" href="#"
                    target="_blank">Laravel,</a>كل الحقوق محفوظة</span><span
                class="float-md-right d-none d-md-block">Hand-crafted & Made with<i
                    class="feather icon-heart pink"></i></span>
            <button class="btn btn-primary btn-icon scroll-top" type="button"><i
                    class="feather icon-arrow-up"></i></button>
        </p>
    </footer>
    <!-- END: Footer-->


    <!-- BEGIN: Vendor JS-->
    <script src="{{ asset('/app-assets/vendors/js/vendors.min.js') }}"></script>
    <script src="{{ asset('/app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
    <!-- BEGIN Vendor JS-->

    <!-- BEGIN: Page Vendor JS-->
    <script src="{{ asset('/app-assets/vendors/js/ui/prism.min.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/charts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js') }}"></script>
    <!-- END: Page Vendor JS-->

    <!-- BEGIN: Theme JS-->
    <script src="{{ asset('/app-assets/js/core/app-menu.js') }}"></script>
    <script src="{{ asset('/app-assets/js/core/app.js') }}"></script>
    <!-- END: Theme JS-->
    
    <!-- Language Switch Fix -->
    <script>
        // Override the default language dropdown behavior to allow backend route calls
        $(document).ready(function() {
            $(".dropdown-language .dropdown-item").off('click').on("click", function (e) {
                var $this = $(this);
                var href = $this.attr('href');
                
                // If the link has a valid href (not just #), allow default behavior
                if (href && href !== '#' && href !== 'javascript:void(0)') {
                    // Let the link work normally - it will call the backend route
                    return true;
                }
                
                // Otherwise, use the old behavior for backward compatibility
                e.preventDefault();
                $this.siblings(".selected").removeClass("selected");
                $this.addClass("selected");
                var selectedLang = $this.text();
                var selectedFlag = $this.find(".flag-icon").attr("class");
                $("#dropdown-flag .selected-language").text(selectedLang);
                $("#dropdown-flag .flag-icon")
                    .removeClass()
                    .addClass(selectedFlag);
                var currentLanguage = $this.data("language");
                if (typeof i18next !== 'undefined') {
                    i18next.changeLanguage(currentLanguage, function (err, t) {
                        if (typeof $ !== 'undefined' && $.fn.localize) {
                            $(".main-menu, .horizontal-menu-wrapper").localize();
                        }
                    });
                }
            });
        });
    </script>
    
    @yield('scriptjs')
    <!-- BEGIN: Page JS-->
    <!-- END: Page JS-->

    <script src="{{ asset('/app-assets/js/scripts/charts/chart-apex.js') }}"></script>
    <script src="{{ asset('/app-assets/js/scripts/cards/card-statistics.js') }}"></script>

</body>
<!-- END: Body-->

</html>
