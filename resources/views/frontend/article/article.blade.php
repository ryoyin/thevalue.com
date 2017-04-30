@extends('frontend.template.layout')

@section('content')

    <script>
        var slug = "{{ $slug }}";
    </script>

    <script src="{{ asset('js/article.js') }}?refresh=20170419"></script>
    <script src="{{ asset('js/fluidvids.js') }}"></script>

    @if(count($articlePhotos) > 1)
        @include('frontend.article.carousel')
    @else
        <img src="{{ asset($articlePhotos[0]['image_path']) }}" alt="{{ $articlePhotos[0]['alt'] }}" class="img-responsive" onclick="galleryInit(this)">
    @endif

    <div class="row" id="article-content">

        <div class="col-md-8" id="left">
            @include('frontend.article.content-left')
        </div>

        <div class="col-md-4" id="right">
            @include('frontend.article.content-right')
        </div>

    </div>

    <script>
        fluidvids.init({
            selector: ['iframe'], // runs querySelectorAll()
            players: ['www.youtube.com', 'player.vimeo.com'] // players to support
        });
    </script>

@endsection