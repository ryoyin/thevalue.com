@extends('frontend.template.layout')

@section('content')

    @include('frontend.auction.item.content')

    {!! link_to(route('frontend.auction.house.sale', ['slug' => $sale->slug]), 'Back', ['class' => 'btn btn-default btn-l-back']) !!}

@endsection