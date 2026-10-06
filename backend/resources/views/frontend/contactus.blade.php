@extends((Session::get('locale') ==='ar'? 'layouts.main-rtl' : 'layouts.main'))
@section('content')
<div class="breadcrumb-area bg-grey">
    <div class="container">
        <div class="ho-breadcrumb">
            <ul>
                <li><a href="/">{{__('frontend.home')}}</a></li>
                <li>{{__('frontend.contact')}}</li>
            </ul>
        </div>
    </div>
</div>
<main class="page-content">

    <!-- Contact Us Area -->
    <div class="contact-us-area ptb-30">
        <div class="container">
            <div class="row">
                <div class="col-lg-7">
                    <div class="commentbox">
                        <h5>{{__('frontend.leave_us_a_message')}}</h5>
                        <form id="contact-form" action="/admin/costumer" method="POST" class="ho-form contact-form">
                            @csrf
                            <div class="ho-form-inner">
                                <div class="single-input single-input-half">
                                    <label for="new-review-name">{{__('frontend.name')}}*</label>
                                    <input type="text" name="name" id="new-review-name">
                                </div>
                                <div class="single-input single-input-half">
                                    <label for="new-review-email">{{__('frontend.phone')}}*</label>
                                    <input type="text" name="phone" id="new-review-email">
                                </div>
                                <div class="single-input">
                                    <label for="new-review-subject">{{__('frontend.subject')}}*</label>
                                    <input type="text" name="subject" id="new-review-subject">
                                </div>
                                <div class="single-input">
                                    <label for="new-review-textbox">{{__('frontend.your_message')}}</label>
                                    <textarea id="new-review-textbox" name="message" cols="30" rows="5"></textarea>
                                </div>
                                <div class="single-input">
                                    <button class="ho-button ho-button-dark" type="submit"><span>{{__('frontend.send')}}</span></button>
                                </div>
                            </div>
                        </form>
                        <p class="form-message"></p>
                    </div>
                </div>
                <div class="col-lg-5 pt-50 pt-lg-0">
                    <h2>{{__('frontend.contact_with_us')}}</h2>
                    <div class="contact-content">
                        <div class="single-content">
                            <span class="single-content-icon">
                                <i class="lnr lnr-map-marker"></i>
                            </span>
                            {{__('frontend.title')}} - {{__('frontend.location')}} 
                        </div>
                        
                        <div class="single-content">
                            <span class="single-content-icon">
                                <i class="lnr lnr-printer"></i>
                            </span>
                            <b>{{__('frontend.call_us')}}: </b><br>
                            +(965) 699 08320
                        </div>
                        <div class="single-content">
                            <span class="single-content-icon">
                                <i class="lnr lnr-envelope"></i>
                            </span>
                            <b>{{__('frontend.email_us')}}:</b><br>
                            <b>info@rain-technology.com</b>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--// Contact Us Area -->

    <!-- leaflet Map -->
    <div id="map" style="height: 400px;"></div>
    <!--// leaflet Map -->


</main>
@endsection