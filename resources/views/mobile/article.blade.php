@extends('mobile.template.layout')

@section('content')

    @if(count($articlePhotos) > 1)
        @include('frontend.article.carousel')
    @else
        <img src="{{ asset($articlePhotos[0]['image_path']) }}" alt="{{ $articlePhotos[0]['alt'] }}" class="img-responsive">
    @endif
    
    {{--@include('frontend.article.carousel')--}}

    <div class="row" id="article-content">

        <div class="col-md-12" id="left">
            @include('frontend.article.content-left')
        </div>

    </div>

@endsection