<script src="{{ asset('js/jquery/jquery.matchHeight-min.js') }}"></script>

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
                <img src="{{ config('app.s3_path').$house->image_path }}">
                <div class="name">{{ $houseDetail->name }}</div>
            </div>


            <div id="category-head" class="tab auction-menu">
                <ul>
                    <li><a href="{{ route('frontend.auction.house.upcoming', ['house' => $house->slug]) }}" class="tab-1 active">@lang('thevalue.pre-auction')</a></li>
                    <li><a href="{{ route('frontend.auction.house.post', ['house' => $house->slug]) }}" class="tab-2">@lang('thevalue.post-auction')</a></li>
                    <li><a href="{{ route('frontend.auction.house.upcoming', ['house' => $house->slug]) }}" class="tab-3">@lang('thevalue.about-us')</a></li>
                </ul>
            </div>

            <div style="clear: both"></div>

            <div class="pre-auction-block pre-ab-1">

                <div class="series">

                    <div class="input-group selection">
                        <span class="input-group-addon" id="basic-addon1">@lang('thevalue.please-select') :</span>
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
                        <div class="ele">@lang('thevalue.auction-date')：{{ $auctionDate[$locale] }}</div>
                        <div class="ele">@lang('thevalue.auction-location')： {{ $presetSeriesDetail->location }}</div>
                    </div>
                    <!-- Swiper -->
                    <div class="swiper-container">

                        <div class="swiper-wrapper">
                            @foreach($seriesArray as $series)
                                <?php $sales = $series->sales()->where('start_date', '>=', date('Y-m-d'))->orderBy('start_date')->get(); ?>

                                @foreach($sales as $sIndex => $sale)
                                    <?php $saleDetail = $sale->details()->where('lang', $locale)->first(); ?>
                                    <div class="swiper-slide">
                                        <div class="row col-sm-6 col-md-4 item">
                                            <div class="col-xs-5" style="min-height: 120px;"><img src="{{ config('app.s3_path').$sale->image_path }}" class="img-responsive"></div>
                                            <div class="col-xs-7 detail">

                                                <div class="misc" style="font-size: 12px">

                                                    <div class="cell-name" style="font-size: 12px">{{ mb_substr($saleDetail->title, 0, 50, 'utf-8') }}...</div>
                                                    @lang('thevalue.auction-location')：<span>{{ $saleDetail->location }}</span><br>
                                                    @lang('thevalue.browse-lots')：<span>{{ $sale->total_lots }}</span> <br>

                                                </div>


                                            </div>

                                            <div class="col-xs-7 detail bottom">
                                                <div class="misc">
                                                    <div class="sepline"></div>
                                                    <?php
                                                        $saleDateRaw = strtotime($sale->start_date);
                                                        if($house->timezone != '') {
                                                            $dateTime = new \DateTime(null, new DateTimeZone($house->timezone));
                                                            $dateTime->setTimestamp($saleDateRaw);

                                                            $saleDate = array(
                                                                'en' => $dateTime->format('Y-m-d H:i:s T'),
                                                                'trad' => $dateTime->format('Y年m月d日 H:i:s T'),
                                                                'sim' => $dateTime->format('Y年m月d日 H:i:s T'),
                                                            );
                                                        } else {
                                                            $saleDate = array(
                                                                'en' => date('Y-m-d', $saleDateRaw),
                                                                'trad' => date('Y年m月d日', $saleDateRaw),
                                                                'sim' => date('Y年m月d日', $saleDateRaw),
                                                            );
                                                        }
                                                        $saleDateCount = date('Y/m/d H:i:s', $saleDateRaw);
//                                                        $saleDate = date('Y年m月d H:i:s', $saleDateRaw);
                                                        /*$saleDate = array(
                                                            'en' => date('Y-m-d', $saleDateRaw),
                                                            'trad' => date('Y年m月d', $saleDateRaw),
                                                            'sim' => date('Y年m月d', $saleDateRaw),
                                                        )*/
                                                    ?>

                                                    <div>{{ $saleDate[$locale] }}</div>
                                                    {{--<div id="date-counter-{{ $sIndex }}" class="date-counter"></div>--}}

                                                    {{--<script type="text/javascript">
                                                        $("#date-counter-{{ $sIndex }}")
                                                            .countdown("{{ $saleDateCount }}", function(event) {
                                                                $(this).text(
                                                                    event.strftime('%D days %H:%M:%S')
                                                                );
                                                            });
                                                    </script>--}}

                                                    <a href="{{ route('frontend.auction.house.sale', ['slug' => $sale->slug]) }}" class="btn btn-primary btn-browse">@lang('thevalue.browse-lots')</a>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endforeach
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

<div style="height: 25px;"></div>

<script>
    jQuery(function($) {
        $('.item').matchHeight();
    });
</script>