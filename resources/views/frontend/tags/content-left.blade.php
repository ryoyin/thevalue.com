<div id="left" class="col-md-9">

    <div id="block" style="border: 0px !important">
        <div id="category-head">Home > <span>{{ $tagDetail['name'] }}</span></div>
        <div id="stories">

            @foreach($tagStories as $story)
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