<ul class='ul-clean'>
    <li class='title' id="article-title">{{ $articleDetails['title'] }}</li>
    <li>
        <ins class="adsbygoogle"
             style="display:block"
             data-ad-client="ca-pub-8545127753274353"
             data-ad-slot="7391805026"
             data-ad-format="auto"></ins>
        <script>
            (adsbygoogle = window.adsbygoogle || []).push({});
        </script>
    </li>
    <li class='notes' id="article-note">{{ $articleDetails['note'] }}</li>
    <li>
        <ul class='misc ul-clean'>
            <li class='pull-left'>
                @if($articleDetails['author'] != "")
                    by <span id="article-author">{{ $articleDetails['author'] }}</span>
                @endif

                 <span id="article-date">{{ $article['published_at'] }}</span>
            </li>
            @if(!$appMode)
                <li class='pull-right'>
                    <ul class='ul-clean share'>
                        {{--<li><i class='fa fa-envelope' aria-hidden='true'></i></li>--}}
                        {{--<li><i class='fa fa-wechat' aria-hidden='true'></i></li>--}}
                        {{--<li><i class='fa fa-weibo' aria-hidden='true'></i></li>--}}
                        <li><a onclick="updateCounter('{{ $slug }}', 'share_counter')" href="http://service.weibo.com/share/share.php?url={{ route("frontend.article", ['slug' => $slug]) }}=&title={{ $articleDetails['title'] }}&pic={{asset($article_photo) }}&ralateUid=&language=zh_cn" target="_blank"><i class='fa fa-weibo' aria-hidden='true'></i></a></li>
                        <li><a onclick="updateCounter('{{ $slug }}', 'share_counter')" href="http://twitter.com/intent/tweet?text={{ $articleDetails['title'] }}&url={{ route("frontend.article", ['slug' => $slug]) }}&pic={{ asset($article_photo) }}" target="_blank"><i class='fa fa-twitter' aria-hidden='true'></i></a></li>
                        <li><a onclick="updateCounter('{{ $slug }}', 'share_counter')" href="http://www.facebook.com/share.php?u={{ route("frontend.article", ['slug' => $slug]) }}&t={{ $articleDetails['title'] }}&pic={{ asset($article_photo) }}" target="_blank"><i class='fa fa-facebook-f' aria-hidden='true'></i></a></li>
                        <li><span class="article-shares">{{ $article['shares'] }} @lang('thevalue.shares')</span></li>
                        <li><span class="article-shares">{{ $article['hit'] }} @lang('thevalue.hits')</span></li>
                    </ul>
                </li>
            @endif
        </ul>
    </li>

    <?php
//        $desc = preg_replace('/(\<img[^>]+)(style\=\"[^\"]+\")([^>]+)(>)/', '${1} class="img-responsive" ${3}${4}', $articleDetails['description']);
//        $test = preg_match('/< *img[^>]*src *= *["\']?([^"\']*)/i', $desc, $match);
//        dd($test);
        if( isset($_SERVER['HTTPS'] ) ) {
            $article_desc = str_replace("http://", "https://", $articleDetails['description']);
        } else {
            $article_desc = $articleDetails['description'];
        }
    ?>

    @if($appMode && count($appArticleBanner) > 0)
        <li style="margin: 15px 30px; border: 1px solid #000; padding: 7px;">
            @foreach($appArticleBanner as $apBanner)
                <img src="{{ asset($apBanner['image_path']) }}" class="img-responsive" onclick="galleryInit(this)">
            @endforeach
        </li>
    @endif

    <li class='desc' style='clear:both' id="article-desc">{!! $article_desc !!}</li>
</ul>

@if($articleDetails['source'] != '' || $articleDetails['photographer'] != '')
    <div class="source">
        <div class="line"></div>
        @if($articleDetails['source'] != "")
            <div>資料來源 <span id="article-source">{{ $articleDetails['source'] }}</span></div>
        @endif
        @if($articleDetails['photographer'] != "")
            <div>PHOTOGRAPHER <span id="article-photographer">{{ $articleDetails['photographer'] }}</span></div>
        @endif
    </div>
@endif

@if(!$appMode)
<div class="tag">
    <ul class="ul-clean">
        @foreach($tags as $tag)
            <li><a href="{{ route("frontend.tag", [$tag['slug']]) }}">{{ $tag['name'] }}</a></li>
        @endforeach
    </ul>
</div>

<div style="clear: both"></div>

<div class="row">
    <div class="col-md-6 social">
        <div class="line"></div>
        <div>分享這篇文章</div>
        <ul class='ul-clean share'>
            <li><a href="http://www.facebook.com/share.php?u={{ route("frontend.article", ['slug' => $slug]) }}&t={{ $articleDetails['title'] }}&pic={{ asset($article_photo) }}" target="_blank"><i class='fa fa-facebook-f' aria-hidden='true'></i></a></li>
            <li><a href="http://twitter.com/intent/tweet?text={{ $articleDetails['title'] }}&url={{ route("frontend.article", ['slug' => $slug]) }}&pic={{ asset($article_photo) }}" target="_blank"><i class='fa fa-twitter' aria-hidden='true'></i></a></li>
            {{--<li>
                <a href="http://v.t.qq.com/share/share.php?url={{ route("frontend.article", ['slug' => $slug]) }}&title={{ $articleDetails['title'] }}">
                    <i class='fa fa-weibo' aria-hidden='true'></i>
                </a>
            </li>--}}
            {{--<li><i class='fa fa-wechat' aria-hidden='true'></i></li>--}}
            {{--<li><i class='fa fa-envelope' aria-hidden='true'></i></li>--}}
            <li><span class="article-shares">{{ $article['shares'] }} shares</span></li>
        </ul>
    </div>
    <div class="col-md-6 subscription">
        <div class="line"></div>
        <div>訂閱 THE VALUE</div>
        <div>收取我們最新資訊</div>
        <div class="input-group">
            <input type="text" class="form-control" aria-label="your email address" id="subsciprtion-email">
            <div class="input-group-btn">
                <button type="button" class="btn btn-primary" onclick="subscription();">Sign Up</button>
            </div>
        </div>
        <div id="article-share-the-value-invalid-email" style="color: red; display:none;">@lang('thevalue.subscript-invalid-email')</div>
        <div id="article-share-the-value-sent-email" style="display:none;">@lang('thevalue.subscript-sent-email')</div>
    </div>
</div>
@endif


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

        @foreach($gallery as $img)
        {
            src: '{{ $img['image_path'] }}',
            w: {{ $img[0] }},
            h: {{ $img[1] }}
        },
        @endforeach
    ];



    function galleryInit(obj) {
        // define options (if needed)

        var imgCount = 0;
        var number = 0;

        $('img').each( function() {
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