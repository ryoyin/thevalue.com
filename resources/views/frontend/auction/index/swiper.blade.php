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

@include('frontend.auction.index.pre')

{{--@include('frontend.auction.index.post')--}}

<!-- Swiper JS -->
<script src="{{ asset('js/swiper/swiper.jquery.min.js') }}"></script>

<!-- Initialize Swiper -->
<script>

    var swiper = new Swiper('.swiper-container', {
        scrollbar: '.swiper-scrollbar',
        scrollbarHide: false,
        slidesPerView: '1',
        centeredSlides: true,
        spaceBetween: 30,
        grabCursor: true
    });

//    console.log(swiper);
</script>