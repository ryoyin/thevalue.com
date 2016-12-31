@extends('frontend.template.layout')

@section('content')

    @include('frontend.homepage.carousel')

    <div class="row" id="featured-article"></div>

    <hr>

    <div class="row" id="home-content">

        @include('frontend.homepage.content-left')

        @include('frontend.homepage.content-right')

    </div>

@endsection