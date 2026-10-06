<!doctype html>
<html class="no-js" lang="zxx">

<head>
	<meta charset="utf-8">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="csrf-token" content="{{ csrf_token() }}">

	@php
		$pageTitle = $pageTitle ?? 'Rain Technology';
		$pageDescription = $pageDescription ?? 'We are a company for the retail sale of electrical and electronic devices, smart home control systems, security systems, surveillance cameras, and related technologies in Kuwait.';
		$pageImage = $pageImage ?? asset('/app-assets/images/ico/apple-icon-120.png');
		$canonicalUrl = $canonicalUrl ?? url()->current();
	@endphp

	@include('meta::manager', [
		'title'         => $pageTitle,
		'description'   => $pageDescription,
		'image'         => $pageImage,
		'url'           => $canonicalUrl,
	])

	<link rel="canonical" href="{{ $canonicalUrl }}">

	<!-- Favicon -->
	<link rel="apple-touch-icon" href="{{ asset('/app-assets/images/ico/apple-icon-120.png') }}">
	<link rel="shortcut icon" type="image/x-icon" href="{{ asset('/app-assets/images/logo/Rain-tech.ico') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('/app-assets/vendors/css/vendors-rtl.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/app-assets/vendors/css/ui/prism.min.css') }}">
	<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
    crossorigin=""/>

	<!-- Google font (font-family: 'Roboto', sans-serif;) -->
	<link href="https://fonts.googleapis.com/css?family=Roboto:300,400,400i,500,700" rel="stylesheet">

	<!-- Plugins -->
	<link rel="stylesheet" href="{{ asset('/frontend/ltr/css/bootstrap.min.css') }}">
	<link rel="stylesheet" href="{{ asset('/frontend/ltr/css/plugins.css') }}">
	<link rel="stylesheet" href="{{ asset('/frontend/vendor/owlcarousel/owl.carousel.min.css') }}">
	<link rel="stylesheet" href="{{ asset('/frontend/vendor/owlcarousel/owl.theme.default.min.css') }}">

	<!-- Style Css -->
	<link rel="stylesheet" href="{{ asset('/frontend/ltr/css/style.css')}}">

	<!-- Custom Styles -->
	<link rel="stylesheet" href="{{ asset('/frontend/ltr/css/custom.css')}}">
	<!-- Rain Technology UI Enhancements -->
	<link rel="stylesheet" href="{{ asset('/frontend/ltr/css/rain-enhancements.css')}}">

	<!-- Font Awesome for Social Media Icons -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

	<!-- Livewire Styles -->
	@livewireStyles

	@stack('styles')
	@stack('structured-data')
</head>

<body>
	<!--[if lte IE 9]>
    	<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
  	<![endif]-->

	<!-- Add your site or application content here -->

	<!-- Wrapper -->
	<div id="wrapper" class="wrapper">

		<!-- Header -->
		<header class="header header-2">

			<!-- Header Top Area -->
			<div class="header-top bg-theme">
				<div class="container">
					<div class="row align-items-center">
						<div class="col-lg-7 col-md-7 col-sm-7 col-12">
							<p class="header-welcomemsg">Welcome to <span>Rain Technology</span> Shopping Store !</p>
						</div>
						<div class="col-lg-5 col-md-5 col-sm-5 col-12">
							<div class="header-langcurr">
								<div class="select-currency">
									<button class="select-currency-current">KWD د.ك</button>
									<ul class="select-currency-list dropdown-list">
										<li><a href="#">KWD د.ك</a></li>
									</ul>
								</div>
								<div class="select-language">
									@php
										$currentLocale = session('locale', 'en');
										$isArabic = $currentLocale === 'ar';
									@endphp
									<a class="select-language-current dropdown-toggle nav-link"
										id="dropdown-flag" href="#" data-toggle="dropdown" aria-haspopup="true"
										aria-expanded="false">
										<i class="flag-icon {{ $isArabic ? 'flag-icon-kw' : 'flag-icon-us' }}"></i>
										<span class="selected-language">{{ $isArabic ? 'Arabic' : 'English' }}</span>
									</a>
									<ul class="select-language-list dropdown-list">
										<li><a class="dropdown-item"
											href="{{ route('change_language', 'en') }}" data-language="en">
											<i class="flag-icon flag-icon-us"></i> English</a></li>
										<li><a class="dropdown-item" href="{{ route('change_language', 'ar') }}" data-language="ar">
											<i class="flag-icon flag-icon-kw"></i> Arabic</a></li>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!--// Header Top Area -->

			<!-- Header Middle Area -->
			<div class="header-middle bg-white">
				<div class="container">
					<div class="row align-items-center">
						<div class="col-lg-3 col-md-6 col-sm-6 order-1 order-lg-1">
							<a href="/" class="header-logo " style="width: 50%" >
								<img src="{{ asset('/app-assets/images/logo/Rain-En.ico') }}"  alt="logo">
							</a>
						</div>
						<div class="col-lg-6 col-12 order-3 order-lg-2">
							<form action="{{ route('search') }}" method="GET" class="header-searchbox">
								@csrf
								<select name="category" class="select-searchcategory">
									<option value="0">{{__('frontend.all_categories')}}</option>
									@php
										$headerCategories = cache()->remember(
											\App\Services\Helpers\CacheHelper::CATEGORIES,
											\App\Services\Helpers\CacheHelper::TTL_LONG,
											fn() => \App\Models\Category::all()
										);
									@endphp
									@foreach($headerCategories as $category)
									<option value="{{ $category->id }}">
										{{ Session::get('locale') === 'ar' ? $category->name_ar : $category->name_en }}
									</option>
									@endforeach
								</select>
								<input type="text" name="q" placeholder="{{__('frontend.search_placeholder')}}" required>
								<button type="submit"><i class="lnr lnr-magnifier"></i></button>
							</form>
						</div>
						<div class="col-lg-3 col-md-6 col-sm-6 order-2 order-lg-3">
							<div class="header-icons">
								@auth
								<div class="header-account">
									<a href="{{ route('customer.account.dashboard') }}" class="header-account-link" title="{{ Session::get('locale') === 'ar' ? 'حسابي' : 'My Account' }}">
										<i class="lnr lnr-user"></i>
										<span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
									</a>
								</div>
								@endauth
								@guest
								<div class="header-account">
									<a href="{{ route('customer.login') }}" class="header-account-link" title="{{ Session::get('locale') === 'ar' ? 'تسجيل الدخول' : 'Login' }}">
										<i class="lnr lnr-user"></i>
										<span class="d-none d-md-inline">{{ Session::get('locale') === 'ar' ? 'تسجيل الدخول' : 'Login' }}</span>
									</a>
								</div>
								@endguest
								<div class="header-comparison">
									<a href="{{ route('comparison.index') }}"
									   class="header-comparison-link"
									   title="{{ __('frontend.product_comparison') }}">
										<i class="lnr lnr-layers"></i>
										<span class="comparison-count">0</span>
									</a>
								</div>
								<div class="header-wishlist">
									<a href="{{ auth()->check() ? route('customer.account.wishlist') : '#' }}"
									   class="header-wishlist-link"
									   title="{{ Session::get('locale') === 'ar' ? 'قائمة الأمنيات' : 'Wishlist' }}"
									   @if(!auth()->check()) onclick="alert('{{ Session::get('locale') === 'ar' ? 'يرجى تسجيل الدخول' : 'Please login first' }}'); return false;" @endif>
										<i class="lnr lnr-heart"></i>
										<span class="wishlist-count" id="wishlistCount">0</span>
									</a>
								</div>
								<div class="header-cart">
									@livewire('cart-counter')
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!--// Header Middle Area -->

			<!-- Header Bottom Area -->
			<div class="header-bottom bg-white">
				<div class="container">
					<div class="row align-items-center">
						<div class="col-lg-10 col-md-5 col-sm-5">

							<!-- Category Menu -->
							<!--// Category Menu -->

							<!-- Navigation -->
							<nav class="ho-navigation ho-navigation-2">
								<ul>
									<li class="active ">
										<a href="/">Home</a>
									</li>
									<li class="dropdown-holder">
										<a href="/">Shop</a>
										<ul class="hodropdown">
											<li><a href="/products">All products</a>
											</li>
											<li><a href="/cart">Cart</a></li>
										</ul>
									</li>
                                    
									<li>
										<a href="{{ route('quote-request.create') }}">{{ __('frontend.request_quote') }}</a>
									</li>
									<li>
										<a href="/aboutus">About Us</a>
									</li>
									<li>
										<a href="/contactus"> Contact</a>
									</li>
									<li class="dropdown-holder">
                                    <a href="/">Categories</a>
                                    @php
										$categories =  \App\Models\Category::all();
									@endphp
                                    @foreach ($categories as $category)
                                    
                                    <ul class="hodropdown">
                                    @if (Session::get('locale') === 'en')
                                        <li><a href="/products">{{ $category->name_en }}</a></li>
                                    @else
                                        <li><a href="/products">{{ $category->name_ar }}</a></li>
                                    @endif
                                    @endforeach
                                    </ul>
                                    </li>
								</ul>
							</nav>
							<!--// Navigation -->

						</div>
						<div class="col-lg-2 col-md-5 col-sm-5">
							<div class="header-contactinfo">
								<i class="flaticon-support"></i>
								<span>Call Us Now:</span>
								<a href="tel:+(965) 699 08320">+(965) 699 08320</a>
							</div>
						</div>
						<div class="col-12 d-block d-lg-none">
							<div class="mobile-menu clearfix"></div>
						</div>
					</div>
				</div>
			</div>
			<!--// Header Bottom Area -->

			<!-- Header Hot Tags -->
			<!--// Header Hot Tags -->

		</header>
		<!--// Header -->
    </div>
    <main>
        @yield('content')
    </main>
    <footer class="footer bg-white">

        <!-- Footer Top Area -->
        <div class="footer-toparea">
            <div class="container">
                <div class="row">

                    <div class="col-lg-3 col-12">
                        <div class="footer-widget widget-info">
                            <h5 class="footer-widget-title">Contact Info</h5>
                            <p>We are a company for the retail sale of electrical and electronic devices.</p>
                            <ul>
                                <li><i class="ion ion-ios-pin"></i> {{__('frontend.location')}}</li>
                                <li><i class="ion ion-ios-call"></i> Call us: +(965) 699 08320</li>

                                <li><i class="ion ion-ios-mail"></i> Email us: <a href="#">info@rain-techkw.com</a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="col-lg-2 col-md-4">
                        <div class="footer-widget widget-links">
                            <h5 class="footer-widget-title">Products</h5>
                            <ul>

                                <li><a href="/">Best sales</a></li>
								<li><a href="/">New Arrival</a></li>
								<li><a href="/">Our Products</a></li>

                            </ul>
                        </div>
                    </div>

                    <div class="col-lg-2 col-md-4">
                        <div class="footer-widget widget-links">
                            <h5 class="footer-widget-title">Our Company</h5>
                            <ul>
                                <li><a href="/aboutus">About us</a></li>
								<li><a href="/contactus">Contact us</a></li>
                            </ul>
                        </div>
                    </div>
					<div class="col-lg-2 col-md-4">
                        <div class="footer-widget widget-links">
                            <h5 class="footer-widget-title">Our accounts</h5>
                            <ul>
                                <ul>
									<li><a href="https://www.facebook.com/profile.php?id=100093507100843&mibextid=ZbWKwL"><i class="ion ion-logo-facebook"></i>  facebook</a></li>
									<li><a href="https://instagram.com/raintechnology_kw?igshid=MzNlNGNkZWQ4Mg=="><i class="ion ion-logo-instagram"></i>  instagram</a></li>
								</ul>
                            </ul>
                        </div>
                    </div>


                    <div class="col-lg-3 col-12">
                        <div class="footer-widget widget-newsletter">
                            <h5 class="footer-widget-title">{{ __('frontend.newsletter') }}</h5>
                            <p>{{ __('frontend.subscribe_to_newsletter') }}</p>
                            <form id="newsletterForm" class="newsletter-form">
                                @csrf
                                <div class="input-group">
                                    <input type="email" name="email" class="form-control"
                                           placeholder="{{ __('frontend.your_email') }}" required>
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="lnr lnr-envelope"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="newsletter-message mt-2"></div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--// Footer Top Area -->

        <!-- Footer Bottom -->
        <div class="footer-bottomarea">
            <div class="container">
                <div class="footer-copyright">
                    <p class="copyright">Copyright &copy; <a href="/">Rain Technology</a> . All Rights Reserved</p>
                </div>
            </div>
        </div>
        <!--// Footer Bottom -->

        <!-- Quickview Modal -->
        <!--// Quickview Modal -->

    </footer>
    <!--// Footer -->

</div>
<!--// Wrapper -->

<!-- Js Files -->
<script src="{{ asset('frontend/ltr/js/vendor/modernizr-3.6.0.min.js') }}"></script>
<script src="{{ asset('frontend/ltr/js/vendor/jquery-3.3.1.min.js') }}"></script>
<script src="{{ asset('/frontend/vendor/owlcarousel/owl.carousel.min.js') }}"></script>
<script src="{{ asset('frontend/ltr/js/popper.min.js') }}"></script>
<script src="{{ asset('frontend/ltr/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('frontend/ltr/js/plugins.js') }}"></script>
<script src="{{ asset('frontend/ltr/js/main.js') }}"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
   integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
   crossorigin=""></script>
   <script>
   // Only initialize map if the container element exists
   if (document.getElementById('map')) {
       var map = L.map('map').setView([29.342274585850735, 48.01916934758884], 13);
       L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
           maxZoom: 19,
           attribution: '© OpenStreetMap'
       }).addTo(map);
       var marker = L.marker([29.342274585850735, 48.01916934758884]).addTo(map);
   }
</script>
<script src="{{ asset('/app-assets/js/core/app-menu.js') }}"></script>
<script src="{{ asset('/app-assets/vendors/js/vendors.min.js') }}"></script>
<script src="{{ asset('/app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
<script src="{{ asset('/app-assets/vendors/js/ui/prism.min.js') }}"></script>
<script>
// Fix for carousel error - create a safe wrapper before app.js loads
(function() {
    // Store original jQuery carousel if it exists
    var originalCarousel = $.fn.carousel;

    // Override carousel to check if it exists and elements are present
    $.fn.carousel = function(options) {
        if (typeof originalCarousel === 'function' && this.length > 0) {
            return originalCarousel.call(this, options);
        }
        return this; // Return jQuery object for chaining
    };
})();

// Ensure $.app object exists before app.js loads
if (typeof $.app === 'undefined') {
    $.app = {};
}
if (typeof $.app.menu === 'undefined') {
    $.app.menu = {
        init: function() {},
        change: function() {},
        hide: function() {},
        open: function() {},
        toggle: function() {},
        manualScroller: {
            updateHeight: function() {}
        }
    };
}
if (typeof $.app.nav === 'undefined') {
    $.app.nav = {
        initialized: false,
        init: function() {}
    };
}
</script>
<script src="{{ asset('/app-assets/js/core/app.js') }}"></script>
@stack('scripts')

<!-- Livewire Scripts -->
@livewireScripts

<!-- Alert Notification System -->
<div id="alert-notification"
     style="display:none; position:fixed; top:20px; right:20px; z-index:99999; width:340px; max-width:calc(100% - 40px);">
    <div class="rounded shadow p-3 text-white" id="alert-content" style="background:#17a2b8;">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <span id="alert-icon" class="text-white text-xl mr-2"></span>
                <p class="text-white font-medium" id="alert-message"></p>
            </div>
            <button id="alert-close-btn" class="text-white hover:text-gray-200 ml-4">
                <span class="text-xl">&times;</span>
            </button>
        </div>
    </div>
</div>

<script>
(function($) {
    let alertTimeout = null;

    function showAlert(type, message) {
        console.log('showAlert called with:', type, message);

        const $alertDiv = $('#alert-notification');
        const $alertContent = $('#alert-content');
        const $alertIcon = $('#alert-icon');
        const $alertMessage = $('#alert-message');

        if ($alertDiv.length === 0) {
            console.error('Alert elements not found');
            return;
        }

        // Set message
        $alertMessage.text(message);

        // Set icon and color based on type
        $alertIcon.text('');
        // Remove previous bootstrap bg colors
        $alertContent.removeClass('bg-success bg-danger bg-warning bg-info');

        switch(type) {
            case 'success':
                $alertIcon.text('✓');
                $alertContent.addClass('bg-success');
                break;
            case 'error':
                $alertIcon.text('✕');
                $alertContent.addClass('bg-danger');
                break;
            case 'warning':
                $alertIcon.text('⚠');
                $alertContent.addClass('bg-warning');
                break;
            case 'info':
                $alertIcon.text('ℹ');
                $alertContent.addClass('bg-info');
                break;
            default:
                $alertIcon.text('ℹ');
                $alertContent.addClass('bg-info');
        }

        // Show alert with animation
        $alertDiv.css({
            'display': 'block',
            'opacity': '0',
            'transform': 'translateY(-10px)',
            'transition': 'none'
        });

        setTimeout(function() {
            $alertDiv.css({
                'transition': 'opacity 0.3s ease-out, transform 0.3s ease-out',
                'opacity': '1',
                'transform': 'translateY(0)'
            });
        }, 10);

        // Clear existing timeout
        if (alertTimeout) {
            clearTimeout(alertTimeout);
        }

        // Auto-hide after 5 seconds
        alertTimeout = setTimeout(function() {
            closeAlert();
        }, 5000);
    }

    function closeAlert() {
        const $alertDiv = $('#alert-notification');
        if ($alertDiv.length > 0) {
            $alertDiv.css({
                'transition': 'opacity 0.2s ease-in, transform 0.2s ease-in',
                'opacity': '0',
                'transform': 'translateY(-10px)'
            });

            setTimeout(function() {
                $alertDiv.css('display', 'none');
            }, 200);
        }

        if (alertTimeout) {
            clearTimeout(alertTimeout);
            alertTimeout = null;
        }
    }

    // Close button click handler
    $(document).on('click', '#alert-close-btn', function() {
        closeAlert();
    });

    // Wait for DOM and Livewire to be ready
    $(document).ready(function() {
        console.log('jQuery alert system initialized');

        // Listen for Livewire browser events using both window and document
        $(window).on('show-alert', function(event) {
            console.log('Alert event received on window (jQuery):', event);
            handleAlertEvent(event);
        });

        // Also listen on document for Livewire events
        $(document).on('show-alert', function(event) {
            console.log('Alert event received on document (jQuery):', event);
            handleAlertEvent(event);
        });

        // Listen for native browser events (Livewire dispatches these)
        window.addEventListener('show-alert', function(event) {
            console.log('Alert event received via addEventListener:', event);
            handleAlertEvent(event);
        }, true); // Use capture phase

        // Also listen for Livewire's custom events
        Livewire.on('show-alert', function(data) {
            console.log('Alert event received via Livewire.on:', data);
            if (data && data.type && data.message) {
                showAlert(data.type, data.message);
            } else if (Array.isArray(data) && data.length > 0) {
                const alertData = data[0];
                if (alertData && alertData.type && alertData.message) {
                    showAlert(alertData.type, alertData.message);
                }
            }
        });
    });

    function handleAlertEvent(event) {
        console.log('Event detail:', event.detail);
        console.log('Event detail type:', typeof event.detail);
        console.log('Event detail is array:', Array.isArray(event.detail));

        let data = null;

        // Handle different event formats
        if (Array.isArray(event.detail) && event.detail.length > 0) {
            // Livewire sends data as an array
            data = event.detail[0];
            console.log('Extracted data from array:', data);
        } else if (event.detail && typeof event.detail === 'object' && !Array.isArray(event.detail)) {
            // Direct object format
            data = event.detail;
            console.log('Using direct object:', data);
        } else if (event.originalEvent && event.originalEvent.detail) {
            // jQuery wrapped event
            const originalDetail = event.originalEvent.detail;
            if (Array.isArray(originalDetail) && originalDetail.length > 0) {
                data = originalDetail[0];
            } else if (typeof originalDetail === 'object') {
                data = originalDetail;
            }
            console.log('Using originalEvent detail:', data);
        }

        console.log('Final alert data:', data);

        if (data && data.type && data.message) {
            console.log('Calling showAlert with:', data.type, data.message);
            showAlert(data.type, data.message);
        } else {
            console.warn('Invalid alert data format. Data:', data, 'Event:', event);
        }
    }

    // Make showAlert available globally for debugging
    window.showAlert = showAlert;
    window.closeAlert = closeAlert;

})(jQuery);
</script>

<style>
[x-cloak] { display: none !important; }
</style>

<!-- Quick View Modal -->
@include('frontend.quickview')

<script>
// Load wishlist count on page load
document.addEventListener('DOMContentLoaded', function() {
    // Fade-in on scroll — reveal content before it enters viewport so nothing feels "jammed"
    var fadeTargets = document.querySelectorAll('.ho-section, .about-area, .contact-us-area');
    function makeVisible(el) { el.classList.add('is-visible'); }
    if (typeof IntersectionObserver !== 'undefined') {
        fadeTargets.forEach(function(el) {
            var rect = el.getBoundingClientRect();
            if (rect.top < window.innerHeight + 120) makeVisible(el);
        });
        var observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) makeVisible(entry.target);
            });
        }, { rootMargin: '0px 0px 120px 0px', threshold: 0 });
        fadeTargets.forEach(function(el) {
            if (!el.classList.contains('is-visible')) observer.observe(el);
        });
    } else {
        fadeTargets.forEach(makeVisible);
    }

    @auth
    fetch('{{ route("wishlist.count") }}')
        .then(response => response.json())
        .then(data => {
            const countEl = document.getElementById('wishlistCount');
            if (countEl) {
                countEl.textContent = data.count || 0;
            }
        })
        .catch(error => console.error('Error loading wishlist count:', error));
    @endauth

    // Newsletter Subscription
    $('#newsletterForm').on('submit', function(e) {
        e.preventDefault();
        const form = this;
        const formData = new FormData(form);
        const messageDiv = $('.newsletter-message');

        fetch('{{ route("newsletter.subscribe") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                messageDiv.html('<div class="alert alert-success">' + data.message + '</div>');
                form.reset();
            } else {
                messageDiv.html('<div class="alert alert-danger">' + data.message + '</div>');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            messageDiv.html('<div class="alert alert-danger">{{ __("frontend.error_occurred") }}</div>');
        });
    });

    // Product Comparison
    $(document).on('click', '.add-to-comparison-btn', function(e) {
        e.preventDefault();
        const productId = $(this).data('product-id');
        const btn = $(this);

        fetch('{{ route("comparison.add") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ product_id: productId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                btn.find('.comparison-text').text('{{ __("frontend.in_comparison") }}');
                btn.addClass('btn-info').removeClass('btn-outline-info');
                updateComparisonCount();
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('{{ __("frontend.error_occurred") }}');
        });
    });

    function updateComparisonCount() {
        fetch('{{ route("comparison.count") }}')
            .then(response => response.json())
            .then(data => {
                $('.comparison-count').text(data.count || 0);
            });
    }

    // Update comparison count on page load
    updateComparisonCount();

    const wishlistJsonHeaders = {
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
    };
    const wishlistJsonHeadersWithCsrf = {
        ...wishlistJsonHeaders,
        'X-CSRF-TOKEN': '{{ csrf_token() }}',
    };

    function notifyWishlist(type, message) {
        if (!message) {
            return;
        }
        if (typeof window.showAlert === 'function') {
            window.showAlert(type, message);
        } else {
            alert(message);
        }
    }

    // Handle wishlist toggle on product cards
    document.querySelectorAll('.wishlist-toggle').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const productId = this.getAttribute('data-product-id');
            const icon = this.querySelector('i');

            @guest
            alert('{{ __("frontend.please_login_first") }}');
            window.location.href = '{{ route("customer.login") }}';
            return;
            @endguest

            fetch(`{{ url('/wishlist/check') }}/${productId}`, { headers: wishlistJsonHeaders })
                .then(response => response.json())
                .then(data => {
                    const isInWishlist = data.in_wishlist;

                    if (isInWishlist) {
                        fetch(`{{ url('/wishlist/remove') }}/${productId}`, {
                            method: 'DELETE',
                            headers: wishlistJsonHeadersWithCsrf,
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                if (icon) icon.classList.remove('active');
                                const countEl = document.getElementById('wishlistCount');
                                if (countEl) {
                                    countEl.textContent = data.count || 0;
                                }
                                notifyWishlist('success', data.message);
                            } else if (data.message) {
                                notifyWishlist('info', data.message);
                            }
                        })
                        .catch(err => console.error('Wishlist remove failed', err));
                    } else {
                        fetch('{{ route('wishlist.add') }}', {
                            method: 'POST',
                            headers: {
                                ...wishlistJsonHeadersWithCsrf,
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({ product_id: parseInt(productId, 10) })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                if (icon) icon.classList.add('active');
                                const countEl = document.getElementById('wishlistCount');
                                if (countEl) {
                                    countEl.textContent = data.count || 0;
                                }
                                notifyWishlist('success', data.message);
                            } else if (data.message) {
                                notifyWishlist('info', data.message);
                            }
                        })
                        .catch(err => console.error('Wishlist add failed', err));
                    }
                })
                .catch(err => console.error('Wishlist check failed', err));
        });
    });
});
</script>

<!-- Live Chat Integration (Tawk.to) -->
@if(env('TAWK_TO_PROPERTY_ID') && env('TAWK_TO_WIDGET_ID'))
<!-- Start of Tawk.to Script -->
<script type="text/javascript">
var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src='https://embed.tawk.to/{{ env("TAWK_TO_PROPERTY_ID") }}/{{ env("TAWK_TO_WIDGET_ID") }}';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})();
</script>
<!-- End of Tawk.to Script -->
@endif
<!--Start of Tawk.to Script-->
<script type="text/javascript">
var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src='https://embed.tawk.to/6999c28f3658c71c38c8f32e/1ji0a01o4';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})();
</script>
<!--End of Tawk.to Script-->
</body>

</html>
