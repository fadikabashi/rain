@extends((Session::get('locale') ==='ar'? 'layouts.main-rtl' : 'layouts.main'))
@section('content')
<div class="breadcrumb-area bg-grey">
    <div class="container">
        <div class="ho-breadcrumb">
            <ul>
                <li><a href="/">{{ __('frontend.home') }}</a></li>
                <li>{{ __('frontend.aboutus') }}</li>
            </ul>
        </div>
    </div>
</div>
<main class="page-content page-about">

    <!-- About Page Hero -->
    <section class="about-page-hero">
        <div class="container">
            <h1>{{ __('frontend.aboutus') }} — {{ __('frontend.title') }}</h1>
            <p class="about-tagline">{{ __('frontend.about_tagline') }}</p>
        </div>
    </section>

    <!-- About Story & Image -->
    <div class="about-area is-visible">
        <div class="container">
            <div class="row align-items-center about-story">
                <div class="col-lg-6 order-lg-2">
                    <div class="about-image">
                        <img src="{{ asset('frontend/rtl/images/others/1.jpeg') }}" alt="{{ __('frontend.title') }}" loading="lazy">
                    </div>
                </div>
                <div class="col-lg-6 order-lg-1">
                    <div class="about-content">
                        <h2>{{ __('frontend.how_is') }} <span>{{ __('frontend.title') }}</span></h2>
                        <p>{{ __('frontend.rain_description') }}</p>
                    </div>
                </div>
            </div>

            <!-- Vision -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="about-content">
                        <h5>{{ __('frontend.our_vision') }}</h5>
                        <p>{{ __('frontend.our_vision_description') }}</p>
                        <h5>{{ __('frontend.kuwait_address') }}</h5>
                        <p>{{ __('frontend.location') }}</p>
                    </div>
                </div>
            </div>

            <!-- CTA Block -->
            <div class="about-cta-block">
                <h4>{{ __('frontend.about_cta_heading') }}</h4>
                <div class="about-cta-buttons">
                    <a href="{{ url('/contactus') }}" class="ho-button ho-button-dark">
                        <i class="lnr lnr-envelope"></i>
                        <span>{{ __('frontend.contact') }}</span>
                    </a>
                    <a href="{{ route('products') }}" class="ho-button">
                        <i class="lnr lnr-cart"></i>
                        <span>{{ __('frontend.shop_now') }}</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

</main>
@endsection
