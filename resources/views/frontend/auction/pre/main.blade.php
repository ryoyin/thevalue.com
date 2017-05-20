@extends('frontend.template.layout')

@section('content')

    <script src="{{ asset('js/jquery/jquery.countdown.min.js') }}"></script>

    <hr style="padding: 0; margin:0">

    <div class="row auction" id="home-content">

        <div id="left">

            <div id="block" style="border: 0px !important">

                <div id="category-head" class="auction-menu auction-custom-menu">
                    <ul>
                        <li><a href="{{ route('frontend.auction.auction', ['slug' => 'upcoming']) }}" class="active">@lang('thevalue.pre-auction')</a></li>
                        <li><a href="{{ route('frontend.auction.auction', ['slug' => 'post']) }}">@lang('thevalue.post-auction')</a></li>
                    </ul>
                </div>

                <div style="clear: both"></div>

                {{--<div class="hidden-md hidden-lg">--}}
                <div class="">@include('frontend.auction.pre.swiper')</div>

                {{--<div class="hidden-xs hidden-sm col-md-9 col-lg-9">--}}

                    {{--@include('frontend.auction.index.content-left')--}}

                {{--</div>--}}

            </div>

        </div>

        <div class="hidden-xs hidden-sm col-md-3 col-lg-3">

            @include('frontend.auction.pre.content-right')

        </div>

    </div>

@endsection