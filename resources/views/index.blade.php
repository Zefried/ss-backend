@extends('template.master')
@section('content')


<!--Main Slider Four Start-->
<section class="main-slider-four">
    <div class="swiper-container thm-swiper__slider" data-swiper-options='{"slidesPerView": 1, "loop": true,
        "effect": "fade",
        "pagination": {
        "el": "#main-slider-pagination",
        "type": "bullets",
        "clickable": true
        },
        "navigation": {
        "nextEl": "#main-slider__swiper-button-next",
        "prevEl": "#main-slider__swiper-button-prev"
        },
        "autoplay": {
        "delay": 5000
        }}'>
        <div class="swiper-wrapper">

            <div class="swiper-slide">
                <div class="image-layer-four" style="background-image: url({{asset('assets/img/Slider/slider1.jpg')}});"></div>
                <!-- /.image-layer -->


                <div class="container">
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="main-slider-four__content">
                                <h2 class="main-slider-four__title">Your Health, Our Priority</h2>
                                <p class="main-slider-four__text">Quality Care at Affordable Prices</p>
                                <!-- <div class="main-slider-four__btn-box">
                                    <a href="#" class="thm-btn main-slider__btn-one">Discover More</a>
                                    <a href="#" class="thm-btn main-slider__btn-two">Donate
                                        now</a>
                                </div> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="swiper-slide">
                <div class="image-layer-four" style="background-image: url({{asset('assets/img/Slider/slider2.jpg')}});"></div>
                <!-- /.image-layer -->


                <div class="container">
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="main-slider-four__content">
                                <h2 class="main-slider-four__title">Care You Can Trust</h2>
                                <p class="main-slider-four__text"> Costs You Can Afford.</p>
                                <!-- <div class="main-slider-four__btn-box">
                                    <a href="#" class="thm-btn main-slider__btn-one">Discover More</a>
                                    <a href="#" class="thm-btn main-slider__btn-two">Donate
                                        now</a>
                                </div> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="swiper-slide">
                <div class="image-layer-four" style="background-image: url({{asset('assets/img/Slider/slider3.jpg')}});"></div>
                <!-- /.image-layer -->


                <div class="container">
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="main-slider-four__content">
                                <h2 class="main-slider-four__title">Making Healthcare Accessible</h2>
                                <p class="main-slider-four__text">Affordable, Reliable, Compassionate</p>
                                <!-- <div class="main-slider-four__btn-box">
                                    <a href="#" class="thm-btn main-slider__btn-one">Discover More</a>
                                    <a href="#" class="thm-btn main-slider__btn-two">Donate
                                        now</a>
                                </div> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>



        </div>


        <!-- If we need navigation buttons -->
        <div class="main-slider-four__nav">
            <div class="swiper-button-prev" id="main-slider__swiper-button-next">
                <i class="icon-left-arrow"></i>
            </div>
            <div class="swiper-button-next" id="main-slider__swiper-button-prev">
                <i class="icon-right-arrow"></i>
            </div>
        </div>

    </div>
</section>
<!--Main Slider Four End-->

@include('section.about-sec')

<!--Why Choose Us Start-->
@include('section.why-choose-sec')
<!--Why Choose Us End-->

<!--Services Start-->
@include('section.service-sec')
<!--Services End-->

<!-- Counter Section -->
@include('section.counter-sec')
<!-- Counter Section Ends Here -->

<!-- Contact Start -->
@include('section.contact-sec')
<!-- Contact End-->


@endsection