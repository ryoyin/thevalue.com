@extends('frontend.template.layout')

@section('content')

    <hr style="padding: 0; margin:0">

    <div class="row" id="home-content">

        @include('frontend.tags.content-left')

        @include('frontend.tags.content-right')

    </div>

@endsection