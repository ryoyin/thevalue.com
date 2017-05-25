<div class="item-body row">
    <div class="col-sm-5 col-md-5 item-image">
        <img src="{{ config('app.s3_path').$lot->image_medium_path }}" class="img-responsive" onclick="galleryInit();">
        <div class="enlarge" onclick="galleryInit();"><i class="fa fa-expand" aria-hidden="true"></i> @lang('thevalue.click-to-enlarge')</div>
    </div>
    <div class="col-sm-7 col-md-7 item-detail">
        <div class="lot-number">Lot {{ $lot->number }}</div>
        <div class="lot-title">{{ $lotDetail->title }}</div>
        <div class="lot-stitle">{{ $lotDetail->secondary_title }}</div>
        <div class="lot-desc">{!! $lotDetail->description !!}</div>
        @if(trim($lot->dimension) != '')
            <div class="lot-dimension"><span>@lang('thevalue.dimension')</span>{!! $lot->dimension !!}</div>
        @endif
        <?php
        $estimate_initial = str_replace('HKD ', '', $lot->estimate_value_initial);
        $estimate_end = str_replace('HKD ', '', $lot->estimate_value_end);
        $estimate = $estimate_initial.' - '.$estimate_end;

        $estimate = trans('thevalue.estimate').'<br>'.$lot->currency_code.' '.$estimate;

        if($estimate_initial == '' && $estimate_end == '') {
            $estimate = trans('thevalue.estimate-on-request');
        }
        ?>
        <div class="lot-estimate">{!! $estimate !!}</div>
    </div>
</div>

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

<?php
    $imageSize = getimagesize(config('app.s3_path').$lot['image_large_path']);
?>

<script>

    var pswpElement = document.querySelectorAll('.pswp')[0];

    // build items array
    var items = [
        {
            src: '{{ config('app.s3_path').$lot['image_large_path'] }}',
            w: {{ $imageSize[0] }},
            h: {{ $imageSize[1] }}
        },
    ];

    function galleryInit() {
        // Initializes and opens PhotoSwipe
        var gallery = new PhotoSwipe( pswpElement, PhotoSwipeUI_Default, items);

        gallery.listen('close', function() {
        $('.head-dropdown-menu').addClass('navbar-fixed-top');
        });
        gallery.init();
        $('.head-dropdown-menu').removeClass('navbar-fixed-top');
    }

</script>