@extends('frontend.template.layout')

@section('content')

    <script>
        var slug = "{{ $slug }}";
    </script>

    <script src="{{ asset('js/categories.js') }}?refresh=20170415"></script>

    {{--<div class="row" id="featured-article"></div>--}}

    <hr style="padding: 0; margin:0">

    <div class="row" id="home-content">

        @include('frontend.categories.content-left')

        @include('frontend.categories.content-right')

    </div>

@endsection