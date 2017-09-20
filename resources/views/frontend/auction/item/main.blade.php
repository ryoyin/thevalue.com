@extends('frontend.template.layout')

@section('content')

    @include('frontend.auction.item.content')

    {!! link_to(URL::previous(), 'Back', ['class' => 'btn btn-default btn-l-back']) !!}

@endsection