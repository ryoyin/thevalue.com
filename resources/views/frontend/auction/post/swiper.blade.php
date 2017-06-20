<!-- Link Swiper's CSS -->
<link rel="stylesheet" href="{{ asset('js/swiper/swiper.min.css?update=2017062101') }}">

<!-- Swipe Custom styles -->
<link rel="stylesheet" href="{{ asset('js/swiper/swiper.custom.css') }}">

@include('frontend.auction.post.pre')

<!-- Swiper JS -->
<script src="{{ asset('js/swiper/swiper.jquery.min.js') }}"></script>

<!-- Initialize Swiper -->
<script>

    var sliderPerView = 1;
    var slideTo = 0;
    if(window.screen.availWidth <=460) {
        sliderPerView = 1;
        slideTo = 0;
    } else if(window.screen.availWidth <=991) {
        sliderPerView = 2;
        slideTo = 1;
    } else {
        sliderPerView = 3;
        slideTo = 1;
    }

    var swiper = new Swiper('.swiper-container', {
        scrollbar: '.swiper-scrollbar',
        scrollbarHide: false,
        slidesPerView: sliderPerView,
        centeredSlides: true,
        spaceBetween: 30,
        grabCursor: true,
        slideTo: 1,
    });

    /*for(var i=0; i < swiper.length; i++) {
        swiper[i].slideTo(slideTo);
    }*/

//    swiper.slideTo(2);

    $(window).resize(function() {
        if(window.screen.availWidth <=460) {
            updateSwiper(1, 1);
//            console.log('move');
        } else if(window.screen.availWidth <=991) {
            updateSwiper(2, 2);
        } else {
            updateSwiper(3, 3);
        }
    });

    /*function updateSwiper(slidesPerView, swiperTo) {
        for(var i=0; i < swiper.length; i++) {
            swiper[i].params.slidesPerView = slidesPerView;
            swiper[i].update();
            swiper[i].slideTo(swiperTo);
        }
    }*/

    function updateSwiper(slidesPerView, swiperTo) {
        swiper.params.slidesPerView = slidesPerView;
        swiper.update();
        swiper.slideTo(swiperTo);
    }

   //    console.log(swiper);
</script>