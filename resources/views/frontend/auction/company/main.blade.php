@extends('frontend.template.layout')

@section('content')

    @include('frontend.auction.company.content')

@endsection

{!! link_to(URL::previous(), 'Back', ['class' => 'btn btn-default btn-l-back']) !!}