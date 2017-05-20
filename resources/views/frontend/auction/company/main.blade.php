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

    <script src="{{ asset('js/jquery/jquery.countdown.min.js') }}"></script>

    <hr style="padding: 0; margin:0">

    <div class="row auction auction-company" id="home-content">

        <div id="left">

            <div id="block" style="border: 0px !important">

                <div class="logo">
                    <img src="{{ asset($house->image_path) }}">
                    <div class="name">{{ $houseDetail->name }}</div>
                </div>


                <div id="category-head" class="tab auction-menu">
                    <ul>
                        <li><a href="{{ route('frontend.auction.house.upcoming', ['house' => $house->slug]) }}" class="tab-1 active">@lang('thevalue.pre-auction')</a></li>
                        <li><a href="{{ route('frontend.auction.house.upcoming', ['house' => $house->slug]) }}" class="tab-2">@lang('thevalue.post-auction')</a></li>
                        <li><a href="{{ route('frontend.auction.house.upcoming', ['house' => $house->slug]) }}" class="tab-3">@lang('thevalue.about-us')</a></li>
                    </ul>
                </div>

                <div style="clear: both"></div>

                <div class="pre-auction-block pre-ab-1">

                    <div class="series">

                        <div class="input-group selection">
                            <span class="input-group-addon" id="basic-addon1">請選擇 :</span>
                            <select class="form-control" id="sel1" aria-describedby="basic-addon1">
                                @foreach($seriesArray as $series)
                                    <?php $seriesDetail = $series->details()->where('lang', $locale)->first(); ?>
                                    <option>{{ $seriesDetail->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="a-detail">
                            <?php
                                $presetSeriesDetail = $presetSeries->details()->where('lang', $locale)->first();

                                $startDate = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $presetSeries->start_date);
                                $endDate = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $presetSeries->end_date);

                                $startDateArray = array('en' => $startDate->format('M d, Y'), 'trad' => $startDate->format('Y年m月d日'), 'sim' => $startDate->format('Y年m月d日'));
                                $endDateArray = array('en' => $endDate->format('M d, Y'), 'trad' => $endDate->format('Y年m月d日'), 'sim' => $endDate->format('Y年m月d日'));

                                $auctionDate = array(
                                    'en' => $startDateArray[$locale].' - '.$endDateArray[$locale],
                                    'trad' => $startDateArray[$locale].' 至 '.$endDateArray[$locale],
                                    'sim' => $startDateArray[$locale].' 至 '.$endDateArray[$locale],
                                )
                            ?>
                            <div class="title">{{ $presetSeriesDetail->name }}</div>
                            {{--<div class="ele">预展时间：2017年5月4日-7日</div>--}}
                            <div class="ele">拍卖时间：{{ $auctionDate[$locale] }}</div>
                            <div class="ele">拍卖地點： {{ $presetSeriesDetail->location }}</div>
                        </div>
                        <!-- Swiper -->
                        <div class="swiper-container">

                            <div class="swiper-wrapper">
                                <?php for($i=0; $i<4; $i++) { ?>
                                <div class="swiper-slide">
                                    <div class="row col-sm-6 col-md-6 col-md-4">
                                        <div class="col-xs-5"><img src="{{ asset('images/auction_p1.jpg') }}" class="img-responsive"></div>
                                        <div class="col-xs-7 detail">

                                            <div class="misc">

                                                <div class="cell-name">中國當代水墨畫</div>
                                                拍卖地点：<span>8 King Street St. James’s London SW1Y 6QT</span><br>
                                                拍卖总数：<span>136</span> 件<br>

                                            </div>

                                            <script type="text/javascript">
                                                $("#date-counter-1")
                                                    .countdown("2017/05/09 17:30:00", function(event) {
                                                        $(this).text(
                                                            event.strftime('%D days %H:%M:%S')
                                                        );
                                                    });
                                            </script>

                                        </div>

                                        <div class="col-xs-7 detail bottom">
                                            <div class="misc">
                                                <div class="sepline"></div>
                                                <div>2017年05月09日 17:30</div>
                                                <div id="date-counter-1" class="date-counter"></div>

                                                <a href="{{ route('frontend.auction.house.exhibition', ['house' => 'christies', 'event' => '1', 'exhibition']) }}" class="btn btn-primary btn-browse">觀看展品</a>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php } ?>
                            </div>

                            <!-- Add Scrollbar -->
                            <div class="swiper-scrollbar"></div>

                        </div>

                    </div>
                </div>


            </div>

        </div>

        <div class="hidden-xs hidden-sm col-md-3 col-lg-3">

            @include('frontend.auction.pre.content-right')

        </div>

    </div>

@endsection