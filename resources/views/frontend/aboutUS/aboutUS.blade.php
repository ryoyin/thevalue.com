@extends('frontend.template.layout')

@section('content')

    <script src="{{ asset('js/aboutus.js') }}?refresh=20170412"></script>

    <hr style="padding: 0; margin:0">

    @include('frontend.aboutUS.content')

@endsection