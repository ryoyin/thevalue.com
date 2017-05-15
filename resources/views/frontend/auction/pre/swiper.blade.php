<!-- Link Swiper's CSS -->
<link rel="stylesheet" href="{{ asset('js/swiper/swiper.min.css') }}">

<!-- Swipe Custom styles -->
<link rel="stylesheet" href="{{ asset('js/swiper/swiper.custom.css') }}">

<style>

    #left {
        padding-right: inherit !important;
    }

    .swiper-container {
        width: 100%;
        padding-top: 10px;
    }

    .swiper-wrapper {
        width: auto;
        height: auto;
        font-size: 13px;
        padding-bottom: 10px;
    }

    .swiper-wrapper .detail {
        padding-left: 10px;
        /*width: auto;*/
    }

    .swiper-slide {
        width: 100%;
    }

    .store-name ul {
        list-style-type: none;
        margin: 0;
        padding: 7px 0;
        display: inline-block;
        width: auto;
    }

    .store-name ul li {
        float: left;
        cursor: pointer;
        padding: 3px 12px;
        margin: 0 10px 0 0;
        border: 1px solid #000000;
        font-size: 10px;
    }

    .pre-auction-block {
        /*display: none;*/
        width: auto;
        position: relative;
    }

    .active {
        display: block !important;
    }

</style>

@include('frontend.auction.pre.pre')


<!-- Swiper JS -->
<script src="{{ asset('js/swiper/swiper.jquery.min.js') }}"></script>

<!-- Initialize Swiper -->
<script>

    var sliderPerView = 1;
    if(window.screen.availWidth <=460) {
        sliderPerView = 1;
    } else if(window.screen.availWidth <=991) {
        sliderPerView = 2;
    } else {
        sliderPerView = 3;
    }

    var swiper = new Swiper('.swiper-container', {
        scrollbar: '.swiper-scrollbar',
        scrollbarHide: false,
        slidesPerView: sliderPerView,
        centeredSlides: true,
        spaceBetween: 30,
        grabCursor: true
    });

    $(window).resize(function() {
        if(window.screen.availWidth <=460) {
            updateSwiper(1);
        } else if(window.screen.availWidth <=991) {
            updateSwiper(2, 2);
        } else {
            updateSwiper(3, 3);
        }
    });

    function updateSwiper(slidesPerView, swiperTo) {
        for(var i=0; i < swiper.length; i++) {
            swiper[i].params.slidesPerView = slidesPerView;
            swiper[i].update();
            swiper[i].slideTo(swiperTo);
        }
    }


    //    console.log(swiper);
</script>