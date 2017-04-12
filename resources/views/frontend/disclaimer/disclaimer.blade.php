@extends('frontend.template.layout')

@section('content')

    <script src="{{ asset('js/disclaimer.js') }}?refresh=201704012"></script>

    <hr style="padding: 0; margin:0">

    @include('frontend.disclaimer.content')


@endsection