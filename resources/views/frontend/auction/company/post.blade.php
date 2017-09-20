@extends('frontend.template.layout')

@section('content')

    @include('frontend.auction.company.postContent')

@endsection

{!! link_to(route('frontend.auction.auction', ['slug' => 'post']), 'Back', ['class' => 'btn btn-default btn-l-back']) !!}