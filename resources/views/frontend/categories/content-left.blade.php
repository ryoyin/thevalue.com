<div id="left" class="col-md-9">

    <div id="block" style="border: 0px !important">
        <div id="category-head">Home > <span>{{ $categoryDetail['name'] }}</span></div>

        <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
        <script>
            (adsbygoogle = window.adsbygoogle || []).push({
                google_ad_client: "ca-pub-123456789",
                enable_page_level_ads: true
            });
        </script>

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
                            <li class='desc' style='clear:both'>{!! $story['short_desc'] !!}</li>
                        </ul>
                    </div>
                </div>
                <div style='clear:both'></div>
            @endforeach

        </div>
    </div>

</div>