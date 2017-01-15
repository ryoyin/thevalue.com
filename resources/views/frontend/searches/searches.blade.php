@extends('frontend.template.layout')

@section('content')

    <script src="{{ asset('js/searches.js') }}"></script>

    {{--<div class="row" id="featured-article"></div>--}}

    <hr style="padding: 0; margin:0">

    <div class="row" id="home-content">

        @include('frontend.categories.content-left')

        @include('frontend.categories.content-right')

    </div>

@endsection