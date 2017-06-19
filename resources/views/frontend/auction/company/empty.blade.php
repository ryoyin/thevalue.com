@extends('frontend.template.layout')

@section('content')

    <style>

        #left {
            padding-right: inherit !important;
        }

        .pre-auction-block {
            /*display: none;*/
            width: auto;
            position: relative;
        }

        .active {
            display: block !important;
        }

    </style>


    <hr style="padding: 0; margin:0">

    <div class="row auction auction-company" id="home-content">

        <div id="left">

            <div id="block" style="border: 0px !important">

                <div class="logo">
                    <?php
                    $companyImage = str_replace('_30', '', $house->image_path);
                    ?>
                    <img src="{{ asset($companyImage) }}">
                    <div class="name">{{ $houseDetail->name }}</div>
                </div>


                <div id="category-head" class="tab auction-menu">
                    <ul>
                        <li><a href="{{ route('frontend.auction.house.upcoming', ['house' => $house->slug]) }}" class="tab-1
                         @if($auctionType == 'pre')
                            active
                        @endif
                        ">@lang('thevalue.pre-auction')</a></li>
                        <li><a href="{{ route('frontend.auction.house.post', ['house' => $house->slug]) }}" class="tab-2
                        @if($auctionType == 'post')
                                    active
                                @endif
                        ">@lang('thevalue.post-auction')</a></li>
                        <li><a href="{{ route('frontend.auction.house.upcoming', ['house' => $house->slug]) }}" class="tab-3">@lang('thevalue.about-us')</a></li>
                    </ul>
                </div>

                <div style="clear: both"></div>

                <div style="padding: 100px; height: 300px; font-weight: bold; font-size: 30px; text-align: center;">
                    @if($auctionType == 'pre')
                        @lang('thevalue.no-upcoming-auction')
                    @else
                        @lang('thevalue.no-post-auction')
                    @endif
                </div>

            </div>

        </div>

    </div>




@endsection