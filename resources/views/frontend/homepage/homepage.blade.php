@extends('frontend.template.layout')

@section('content')

    <script src="{{ asset('js/web.js') }}?refresh=2017052701"></script>

    @include('frontend.homepage.carousel')

    <div class="row" id="featured-article">
        <?php

            foreach($featuredArticles as $feArtIndex => $featuredArticle) {

                foreach($categories as $cate) {
                    if($featuredArticle['category_id'] == $cate['id']) {
                        $categoryName = $cate['name'];
                        if($cate['name'] != $cate['default_name']) $categoryName = $cate['default_name']." ".$cate['name'];
                        $categorySlug = $cate['slug'];
                    }
                }

                $image_path = $featuredArticle['photo']['s3'] ? config("app.s3_path").$featuredArticle['photo']['image_path'] : asset($featuredArticle['photo']['image_path']);
        ?>
                <ul class='col-xs-6 col-md-3 ul-clean'>
                    <li><a href='{{ route('frontend.article', ['slug' => $featuredArticle['slug']]) }}'><img src='{!! $image_path !!}' class='img-responsive'></a></li>
                    <li><a href='{{ route('frontend.category', ['slug' => $categorySlug]) }}' class='category_name'>{{ $categoryName }}</a></li>
                    <li><a href='{{ route('frontend.article', ['slug' => $featuredArticle['slug']]) }}'>{{ $featuredArticle['title'] }}</a></li>
                </ul>

        <?php
                if($feArtIndex == 1)  {
                    echo '<div class="clearfix visible-xs-block visible-sm-block"></div>';
                }
            }
        ?>
    </div>

    <hr>

    <div class="row homepage-content" id="home-content">

        @include('frontend.homepage.content-left')

        @include('frontend.homepage.content-right')

    </div>

@endsection