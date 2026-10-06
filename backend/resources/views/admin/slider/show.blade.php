@extends('layouts.app')
@section('title', 'سلايدات')
@section('content')
<div class="col-lg-9">
    <!-- Hero Area -->
    <div class="herobanner herobanner-3 slider-navigation slider-dots mt-30">

        <!-- Herobanner Single -->
        <div class="herobanner-single">
            <img src="{{ asset($slider->ad_825)}}" alt="hero image">
            <div class="herobanner-content">
                <div class="herobanner-box">
                    <h4>{{$slider->sub_heading}}</h4>
                </div>
                <div class="herobanner-box">
                    <h1>{{$slider->heading}}</h1>
                </div>
                <div class="herobanner-box">
                    <p>{{$slider->description}}</p>
                </div>
            </div>
            <span class="herobanner-progress"></span>
        </div>
        <!--// Herobanner Single -->

    </div>
    <!--// Hero Area -->
</div>
@endsection