@extends('frontend.template.layout')

@section('content')

    <script>
        var slug = "{{ $slug }}";
    </script>

    <script src="{{ asset('js/tags.js') }}?refresh=20170416"></script>

    {{--<div class="row" id="featured-article"></div>--}}

    <hr style="padding: 0; margin:0">

    <div class="row" id="home-content">

        @include('frontend.tags.content-left')

        @include('frontend.tags.content-right')

    </div>

@endsection