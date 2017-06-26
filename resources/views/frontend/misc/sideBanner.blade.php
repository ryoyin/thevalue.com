<ul id="advert" class="ul-clean">
    <?php
        if(isset($sideBanners)) {

                foreach($sideBanners as $bIndex => $banner) {
                $bannerImages[$bIndex] = getimagesize($banner['image_path']);
                $bannerImages[$bIndex]['image_path'] = $banner['image_path'];
                $image_path = $banner['s3'] ? config("app.s3_path").$banner['image_path'] : asset($banner['image_path']);
    ?>

                <li><img src='{{ $image_path }}' style='width: 100%' class='img-responsive banner-image' onclick="galleryInit(this)"></li>

    <?php
                }
        }
    ?>
</ul> <!-- advert -->

<!-- Root element of PhotoSwipe. Must have class pswp. -->
<div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">

        <!-- Background of PhotoSwipe.
             It's a separate element as animating opacity is faster than rgba(). -->
        <div class="pswp__bg"></div>

        <!-- Slides wrapper with overflow:hidden. -->
        <div class="pswp__scroll-wrap">

                <!-- Container that holds slides.
                    PhotoSwipe keeps only 3 of them in the DOM to save memory.
                    Don't modify these 3 pswp__item elements, data is added later on. -->
                <div class="pswp__container">
                        <div class="pswp__item"></div>
                        <div class="pswp__item"></div>
                        <div class="pswp__item"></div>
                </div>

                <!-- Default (PhotoSwipeUI_Default) interface on top of sliding area. Can be changed. -->
                <div class="pswp__ui pswp__ui--hidden">

                        <div class="pswp__top-bar">

                                <!--  Controls are self-explanatory. Order can be changed. -->

                                <div class="pswp__counter"></div>

                                <button class="pswp__button pswp__button--close" title="Close (Esc)"></button>

                                {{--<button class="pswp__button pswp__button--share" title="Share"></button>--}}

                                {{--<button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>--}}

                                <button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>

                                <!-- Preloader demo http://codepen.io/dimsemenov/pen/yyBWoR -->
                                <!-- element will get class pswp__preloader--active when preloader is running -->
                                <div class="pswp__preloader">
                                        <div class="pswp__preloader__icn">
                                                <div class="pswp__preloader__cut">
                                                        <div class="pswp__preloader__donut"></div>
                                                </div>
                                        </div>
                                </div>
                        </div>

                        <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
                                <div class="pswp__share-tooltip"></div>
                        </div>

                        <button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)">
                        </button>

                        <button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)">
                        </button>

                        <div class="pswp__caption">
                                <div class="pswp__caption__center"></div>
                        </div>

                </div>

        </div>

</div>

<script>

    var pswpElement = document.querySelectorAll('.pswp')[0];

    // build items array
    var items = [
//        {
//            src: 'https://s3-ap-southeast-1.amazonaws.com/laravel-storage/images/2017/04/article-03-500.jpg',
//            w: 600,
//            h: 400
//        },

        @if(isset($bannerImages))
                @foreach($bannerImages as $img)
                {
                    src: '{{ asset($img['image_path']) }}',
                    w: {{ $img[0] }},
                    h: {{ $img[1] }}
                },
                @endforeach
        @endif
    ];



    function galleryInit(obj) {
        // define options (if needed)

        var imgCount = 0;
        var number = 0;

        $('.banner-image').each( function() {
            if($(this).attr('src') == $(obj).attr('src')) number = imgCount;
            imgCount ++;
        });

        var options = {
            // optionName: 'option value'
            // for example:
            index: number // start at first slide
        };

        // Initializes and opens PhotoSwipe
        var gallery = new PhotoSwipe( pswpElement, PhotoSwipeUI_Default, items, options);

        gallery.init();
    }



</script>