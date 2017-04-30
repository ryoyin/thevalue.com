@extends('mobile.template.layout')

@section('content')

    <script src="{{ asset('js/fluidvids.js') }}"></script>

    @if(count($articlePhotos) > 1)
        @include('frontend.article.carousel')
    @else
        <?php
            if($articlePhotos[0]['s3']) {
                $image_html_path = config("app.s3_path").$articlePhotos[0]['image_path'];
            } else {
                $image_html_path = asset($articlePhotos[0]['image_path']);
            }
        ?>
        <img src="{{ $image_html_path }}" alt="{{ $articlePhotos[0]['alt'] }}" class="img-responsive" onclick="galleryInit(this)">
    @endif

    {{--@include('frontend.article.carousel')--}}

    <div class="row" id="article-content">

        <div class="col-md-12" id="left">
            @include('frontend.article.content-left')
        </div>

    </div>

    <script>
        fluidvids.init({
            selector: ['iframe'], // runs querySelectorAll()
            players: ['www.youtube.com', 'player.vimeo.com'] // players to support
        });
    </script>

@endsection