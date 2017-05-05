@extends('frontend.template.layout')

@section('content')

    <script>
        var slug = "{{ $slug }}";
        var cat_slug = slug;
    </script>

    {{--<script src="{{ asset('js/categories.js') }}?refresh=20170418"></script>--}}

    {{--<div class="row" id="featured-article"></div>--}}

    <hr class="top-hr">

    <div class="row" id="home-content">

        @include('frontend.categories.content-left')

        @include('frontend.categories.content-right')

    </div>

@endsection