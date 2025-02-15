<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Jana Kalyan Swasta Sewa </title>
    <!-- favicons Icons -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{asset('assets/img/favicon_io/apple-touch-icon.png')}}" />
    <link rel="icon" type="image/png" sizes="32x32" href="{{asset('assets/img/favicon_io/favicon-32x32.png')}}" />
    <link rel="icon" type="image/png" sizes="16x16" href="/assets/img/favicon_io/favicon-16x16.png" />
    <link rel="manifest" href="assets/images/favicons/site.html" />
    <meta name="description" content="Jana Kalyan Swasta Sewa" />


    <link rel="preconnect" href="https://fonts.googleapis.com/">

    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,wght@0,400;0,500;0,700;1,400;1,500;1,700&amp;display=swap"
        rel="stylesheet">

    <link
        href="https://fonts.googleapis.com/css2?family=Merriweather:ital,wght@0,300;0,400;0,700;0,900;1,300;1,400;1,700;1,900&amp;display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="{{asset('assets/vendors/bootstrap/css/bootstrap.min.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/vendors/animate/animate.min.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/vendors/animate/custom-animate.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/vendors/fontawesome/css/all.min.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/vendors/jarallax/jarallax.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/vendors/jquery-magnific-popup/jquery.magnific-popup.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/vendors/nouislider/nouislider.min.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/vendors/nouislider/nouislider.pips.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/vendors/odometer/odometer.min.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/vendors/swiper/swiper.min.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/vendors/wishon-icons/style.css')}}">
    <link rel="stylesheet" href="{{asset('assets/vendors/tiny-slider/tiny-slider.min.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/vendors/reey-font/stylesheet.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/vendors/owl-carousel/owl.carousel.min.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/vendors/owl-carousel/owl.theme.default.min.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/vendors/bxslider/jquery.bxslider.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/vendors/bootstrap-select/css/bootstrap-select.min.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/vendors/vegas/vegas.min.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/vendors/jquery-ui/jquery-ui.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/vendors/timepicker/timePicker.css')}}" />

    <link rel="stylesheet" href="{{asset('assets/css/wishon.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/css/wishon-responsive.css')}}" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">



    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">


</head>

<body>



    <div class="preloader">
        <div class="preloader__image"></div>
    </div>
    <!-- preloader -->




    <div class="page-wrapper">
        <div style="height:0px;">
            <img src="{{asset('assets/img/logo.png')}}" alt="logo" class="logo-img">
        </div>

        <header class="main-header-four">

            <div class="main-header-four__top">
                <div class="main-header-four__top-wrapper">
                    <div class="container">
                        <div class="main-header-four__top-inner">
                            <div class="main-header-four__top-left">
                                <ul class="list-unstyled main-header-four__contact-list">
                                    <li>
                                        <div class="icon">
                                            <i class="fa-solid fa-location-dot"></i>
                                        </div>
                                        <div class="text">
                                            <p><a href="#">Barpeta,Assam,781301</a></p>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="icon">
                                            <i class="fas fa-mobile"></i>
                                        </div>
                                        <div class="text">
                                            <p><a href="tel:7002306906">+91 7002306906</a></p>
                                        </div>
                                    </li>
                                </ul>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <nav class="main-menu main-menu-four">

                <div class="main-menu-four__wrapper">
                    <div class="container">

                        <div class="main-menu-four__wrapper-inner">
                            <!-- <div class="main-menu-four__left">
                                <img src="{{asset('assets/img/logo.png')}}" alt="">
                            </div> -->

                            <div class="main-menu-four__right">
                                <div class="main-menu-four__main-menu-box">
                                    <a href="#" class="mobile-nav__toggler"><i class="fa fa-bars"></i></a>
                                    <ul class="main-menu__list">
                                        <li>
                                            <a href="{{route('index')}}">Home </a>
                                        </li>
                                        <li>
                                            <a href="{{route('about')}}">About</a>
                                        </li>
                                        <li>
                                            <a href="{{route('services')}}">Services</a>
                                        </li>
                                        <li>
                                            <a href="{{route('contact')}}">Contact</a>
                                        </li>
                                        <li class="dropdown reg-btn">
                                            <a href="{{route('doctor-registration')}}" target="_blank">Registration</a>
                                            {{-- <ul>
                                                <li><a href="{{route('doctor-registration')}}">Doctor Registration</a></li>
                                                <li><a href="{{route('pharma-registration')}}">Pharmasist Registration</a></li>
                                            </ul> --}}
                                        </li>
                                    </ul>

                                </div>
                            </div>
                        </div>
                    </div>
            </nav>
        </header>