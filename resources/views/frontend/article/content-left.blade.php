<ul class='ul-clean'>
    <li class='title' id="article-title">{{ $articleDetails['title'] }}</li>
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
                        <li><i class='fa fa-envelope' aria-hidden='true'></i></li>
                        <li><i class='fa fa-wechat' aria-hidden='true'></i></li>
                        <li><i class='fa fa-weibo' aria-hidden='true'></i></li>
                        <li><i class='fa fa-twitter' aria-hidden='true'></i></li>
                        <li><i class='fa fa-facebook-f' aria-hidden='true'></i></li>
                        <li><span class="article-shares">{{ $article['shares'] }} shares</span></li>
                    </ul>
                </li>
            @endif
        </ul>
    </li>

    <?php
//        $desc = preg_replace('/(\<img[^>]+)(style\=\"[^\"]+\")([^>]+)(>)/', '${1} class="img-responsive" ${3}${4}', $articleDetails['description']);
//        $test = preg_match('/< *img[^>]*src *= *["\']?([^"\']*)/i', $desc, $match);
//        dd($test);
    ?>
    <li class='desc' style='clear:both' id="article-desc">{!! $articleDetails['description'] !!}</li>

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
                <li>
                    <a href="http://v.t.qq.com/share/share.php?url={{ route("frontend.article", ['slug' => $slug]) }}&title={{ $articleDetails['title'] }}">
                        <i class='fa fa-weibo' aria-hidden='true'></i>
                    </a>
                </li>
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
</ul>