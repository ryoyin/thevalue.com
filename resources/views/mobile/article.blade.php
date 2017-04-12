@extends('mobile.template.layout')

@section('content')

    @include('frontend.article.carousel')

    <div class="row" id="article-content">

        <div class="col-md-12" id="left">
            @include('frontend.article.content-left')
        </div>

    </div>

@endsection