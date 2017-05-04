@extends('frontend.template.layout')

@section('content')

    <hr style="padding: 0; margin:0">

    <div class="row" id="home-content" class="auction">

        <div id="left">

            <div id="block" style="border: 0px !important">

                <div id="category-head">Home > <span>@lang('thevalue.auction')</span></div>

                {{--<div class="hidden-md hidden-lg">--}}
                <div class="">

                    @include('frontend.auction.index.swiper-slides-pre-view')

                </div>

                {{--<div class="hidden-xs hidden-sm col-md-9 col-lg-9">--}}

                    {{--@include('frontend.auction.index.content-left')--}}

                {{--</div>--}}

            </div>

        </div>

        <div class="hidden-xs hidden-sm col-md-3 col-lg-3">

            @include('frontend.auction.index.content-right')

        </div>

    </div>

@endsection