@extends('frontend.template.layout')

@section('content')

    <script>
        var slug = "{{ $slug }}";
    </script>

    <script src="{{ asset('js/article.js') }}?refresh=20170308"></script>

    @include('frontend.article.carousel')

    <div class="row" id="article-content">

        <div class="col-md-8" id="left">
            @include('frontend.article.content-left')
        </div>

        <div class="col-md-4" id="right">
            @include('frontend.article.content-right')
        </div>

    </div>

@endsection