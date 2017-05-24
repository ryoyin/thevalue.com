<div id="left" class="col-md-9">

    <div id="block" style="border: 0px !important">
        <div id="category-head">Home > <span>{{ $categoryDetail['name'] }}</span></div>
        <div id="stories">

            @foreach($categoryStories as $story)
                {{--{{ dd($categoryDetail) }}--}}
                <?php
                    $image_path = $story['photo']['s3'] ? config("app.s3_path").$story['photo']['image_path'] : asset($story['photo']['image_path']);
                ?>
                <div class='news'>
                    <div class='col-md-5 left'>
                        <a href='{{ route('frontend.article', ['slug' => $story['slug']]) }}'>
                            <img src='{{ $image_path }}' class='img-responsive' style='width:100%'>
                        </a>
                    </div>
                    <div class='col-md-7 right'>
                        <ul class='ul-clean'>
                            <li class='cate'><a href="{{ route("frontend.category", ['slug' => $story['category']['slug']]) }}">{{ $story['category']['name'] }}</a></li>
                            <li class='title'><a href='{{ route('frontend.article', ['slug' => $story['slug']]) }}'>{{ $story['title'] }}</a></li>
                            <li class='published_at'><span>@lang('thevalue.publish-date')</span> {!! $story['published_at'] !!}</li>
                            <li class='desc' style='clear:both'>{!! $story['short_desc'] !!}</li>
                            <li class='social pull-right'>
                                <ul class='ul-clean share'>
                                    <li><a href="http://service.weibo.com/share/share.php?url={{ route("frontend.article", ['slug' => $story['slug']]) }}=&title={{ $story['title'] }}&pic={{ $image_path }}&ralateUid=&language=zh_cn" target="_blank"><i class='fa fa-weibo' aria-hidden='true'></i></a></li>
                                    <li><a href="http://twitter.com/intent/tweet?text={{ $story['title'] }}&url={{ route("frontend.article", ['slug' => $story['slug']]) }}&pic={{ $image_path }}" target="_blank"><i class='fa fa-twitter' aria-hidden='true'></i></a></li>
                                    <li><a href="http://www.facebook.com/share.php?u={{ route("frontend.article", ['slug' => $story['slug']]) }}&t={{ $story['title'] }}&pic={{ $image_path }}" target="_blank"><i class='fa fa-facebook-f' aria-hidden='true'></i></a></li>
                                </ul>
                            </li>
                            <li class='social pull-right'>
                                <ul class='ul-clean share'>
                                    <li class="text">{{ $story['share_counter'] }} <span>@lang('thevalue.shares')</span></li>
                                    <li class="text">{{ $story['hit_counter'] }} <span>@lang('thevalue.hits')</li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
                <div style='clear:both'></div>
            @endforeach

        </div>
    </div>

</div>