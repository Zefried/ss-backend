<!--Site Footer Start-->
<footer class="site-footer site-footer-two">
    <div class="site-footer__shape-1 float-bob-y">
        <img src="{{asset('assets/images/shapes/footer-shape-1.png')}}" alt="">
    </div>
    <div class="site-footer__shape-2 float-bob-x">
        <img src="{{asset('assets/images/shapes/footer-shape-2.png')}}" alt="">
    </div>
    <div class="site-footer__top">
        <div class="container">
            <div class="row">

                <div class="col-xl-4 col-lg-4 col-md-4 col-xs-6 wow fadeInUp" data-wow-delay="200ms">
                    <div class="footer-widget__links">
                        <div class="footer-widget__title-box">
                            <h4 class="footer-widget__title">Links</h4>
                        </div>
                        <div class="footer-widget__links-points-box">
                            <ul class="footer-widget__links-list list-unstyled">
                                <li><a href="{{route('index')}}">Home</a></li>
                                <li><a href="{{route('about')}}">About us</a></li>
                                <li><a href="{{route('services')}}">Services</a></li>
                                <li><a href="#">Gallery</a></li>

                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-4 col-xs-6 wow fadeInUp" data-wow-delay="300ms">
                    <div class="footer-widget__non-profit">
                        <div class="footer-widget__title-box">
                            <h4 class="footer-widget__title">Social Media</h4>
                        </div>
                        <ul class="footer-widget__non-profit-list list-unstyled">
                            <li class="d-flex"><a href="#"><i class="fab fa-twitter"></i> </a><span>Twitter</span></li>
                            <li class="d-flex"><a href="#" class="fab fa-facebook-square"></a><span>Facebook</span></li>
                            <li class="d-flex"><a href="#" class="fab fa-instagram"></a><span>Instagram</span></li>
                        </ul>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-4 col-xs-12 wow fadeInUp" data-wow-delay="300ms">
                    <div class="footer-widget__contact">
                        <div class="footer-widget__title-box">
                            <h4 class="footer-widget__title">Contact</h4>
                        </div>
                        <ul class="list-unstyled footer-widget__contact-list">
                            <!-- <li>
                                <div class="icon">
                                    <i class="fas fa-envelope-open"></i>
                                </div>
                                <div class="text">
                                    <p><a href="mailto:needhelp@company.com ">needhelp@company.com</a></p>
                                </div>
                            </li> -->
                            <li>
                                <div class="icon">
                                    <i class="fas fa-mobile"></i>
                                </div>
                                <div class="text">
                                    <p><a href="tel:13077760608">+91 7002306906</a></p>
                                </div>
                            </li>
                            <li>
                                <div class="icon">
                                    <i class="fa-solid fa-location-dot"></i>
                                </div>
                                <div class="text">
                                    <p class="footer-widget__contact-text">Barpeta,Assam,781301</p>
                                </div>
                            </li>
                        </ul>

                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div class="site-footer__bottom">
                <div class="site-footer__bottom-inner">
                    <p class="site-footer__bottom-text">© Copyright 2024 by <a href="#"><strong> Jana Kalyan Swasta
                                Sewa.</strong> </a>
                        <span>Designed By <a href="https://www.webinfotech.net.in/">WebInfoTech</a></span>
                    </p>

                </div>
            </div>
        </div>
    </div>
</footer>
<!--Site Footer End-->


</div><!-- /.page-wrapper -->


<div class="mobile-nav__wrapper">
    <div class="mobile-nav__overlay mobile-nav__toggler"></div>
    <!-- /.mobile-nav__overlay -->
    <div class="mobile-nav__content">
        <span class="mobile-nav__close mobile-nav__toggler"><i class="fa fa-times"></i></span>

        <div class="logo-box">
            <!-- <a href="{{route('index')}}" aria-label="logo image"><img src="{{asset('assets/images/resources/logo-2.png')}}" width="145"
                    alt="" /></a> -->
        </div>
        <!-- /.logo-box -->
        <div class="mobile-nav__container"></div>
        <!-- /.mobile-nav__container -->

        <ul class="mobile-nav__contact list-unstyled">
            <li>
                <i class="fa-solid fa-location-dot"></i>
                <a href="#">Barpeta,Assam,781301</a>
            </li>
            <li>
                <i class="fas fa-mobile"></i>
                <a href="tel:7002306906">+91 7002306906</a>
            </li>
        </ul><!-- /.mobile-nav__contact -->
        <div class="mobile-nav__top">
            <div class="mobile-nav__social">
                <a href="#" class="fab fa-twitter"></a>
                <a href="#" class="fab fa-facebook-square"></a>
                <a href="#" class="fab fa-instagram"></a>

            </div><!-- /.mobile-nav__social -->
        </div><!-- /.mobile-nav__top -->



    </div>
    <!-- /.mobile-nav__content -->
</div>
<!-- /.mobile-nav__wrapper -->

<div class="search-popup">
    <div class="search-popup__overlay search-toggler"></div>
    <!-- /.search-popup__overlay -->
    <div class="search-popup__content">
        <form action="#">
            <label for="search" class="sr-only">search here</label><!-- /.sr-only -->
            <input type="text" id="search" placeholder="Search Here..." />
            <button type="submit" aria-label="search submit" class="thm-btn">
                <i class="icon-magnifying-glass"></i>
            </button>
        </form>
    </div>
    <!-- /.search-popup__content -->
</div>
<!-- /.search-popup -->

<a href="#" data-target="html" class="scroll-to-target scroll-to-top"><i class="fa fa-angle-up"></i></a>


<script src="{{asset('assets/vendors/jquery/jquery-3.6.0.min.js')}}"></script>
<script src="{{asset('assets/vendors/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('assets/vendors/jarallax/jarallax.min.js')}}"></script>
<script src="{{asset('assets/vendors/jquery-ajaxchimp/jquery.ajaxchimp.min.js')}}"></script>
<script src="{{asset('assets/vendors/jquery-appear/jquery.appear.min.js')}}"></script>
<script src="{{asset('assets/vendors/jquery-circle-progress/jquery.circle-progress.min.js')}}"></script>
<script src="{{asset('assets/vendors/jquery-magnific-popup/jquery.magnific-popup.min.js')}}"></script>
<script src="{{asset('assets/vendors/jquery-validate/jquery.validate.min.js')}}"></script>
<script src="{{asset('assets/vendors/nouislider/nouislider.min.js')}}"></script>
<script src="{{asset('assets/vendors/odometer/odometer.min.js')}}"></script>
<script src="{{asset('assets/vendors/swiper/swiper.min.js')}}"></script>
<script src="{{asset('assets/vendors/tiny-slider/tiny-slider.min.js')}}"></script>
<script src="{{asset('assets/vendors/wnumb/wNumb.min.js')}}"></script>
<script src="{{asset('assets/vendors/wow/wow.js')}}"></script>
<script src="{{asset('assets/vendors/isotope/isotope.js')}}"></script>
<script src="{{asset('assets/vendors/countdown/countdown.min.js')}}"></script>
<script src="{{asset('assets/vendors/owl-carousel/owl.carousel.min.js')}}"></script>
<script src="{{asset('assets/vendors/bxslider/jquery.bxslider.min.js')}}"></script>
<script src="{{asset('assets/vendors/bootstrap-select/js/bootstrap-select.min.js')}}"></script>
<script src="{{asset('assets/vendors/vegas/vegas.min.js')}}"></script>
<script src="{{asset('assets/vendors/jquery-ui/jquery-ui.js')}}"></script>
<script src="{{asset('assets/vendors/timepicker/timePicker.js')}}"></script>
<script src="{{asset('assets/vendors/circleType/jquery.circleType.js')}}"></script>
<script src="{{asset('assets/vendors/circleType/jquery.lettering.min.js')}}"></script>
<script src="{{asset('assets/vendors/sidebar-content/jquery-sidebar-content.js')}}"></script>





<!-- Owl Carousel JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
<script>
// Initialize the Owl Carousel
$(document).ready(function() {
    $(".owl-carousel1").owlCarousel({
        items: 3, // Display 3 items at a time
        loop: true, // Enable looping
        margin: 10, // Space between items
        nav: false, // Show next/prev buttons
        autoplay: true, // Enable autoplay
        autoplayTimeout: 3000, // 3-second autoplay interval
        dots: false, // Show dots for navigation
        responsive: {
            0: {
                items: 1 // Display 1 item on small screens
            },
            600: {
                items: 2 // Display 2 items on medium screens
            },
            1000: {
                items: 3 // Display 3 items on large screens
            }
        }
    });
});
</script>
<script>
$(document).ready(function() {
    $(".owl-carousel2").owlCarousel({
        items: 1,
        loop: true,
        margin: 10,
        nav: false,
        autoplay: true,
        autoplayTimeout: 4000,
        dots: false,
    });
});
</script>

<!-- template js -->
<script src="{{asset('assets/js/wishon.js')}}"></script>
</body>


</html>