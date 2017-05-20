<div class="pre-auction-block pre-ab-1">
    @foreach($series as $auction)
        <?php
        $house = $auction->house->first();
        $houseDetail = $house->details->where('lang', $locale)->first();
        ?>
        <div class="store-name"><img src="{{ asset($house->image_path) }}"><span>{{ $houseDetail->name }}</span></div>
        {{--<div class="more"><a href="{{ route('frontend.auction.house.upcoming', ['slug' => $house->slug]) }}">查看更多</a></div>--}}
        <div class="series">
            <?php $auctionDetail = $auction->details->where('lang', $locale)->first(); ?>
            <div class="title">{{ $auctionDetail->name }}</div>
            <?php
            $startDate = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $auction->start_date);
            $endDate = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $auction->end_date);

            $startDateArray = array('en' => $startDate->format('M d, Y'), 'trad' => $startDate->format('Y年m月d日'), 'sim' => $startDate->format('Y年m月d日'));
            $endDateArray = array('en' => $endDate->format('M d, Y'), 'trad' => $endDate->format('Y年m月d日'), 'sim' => $endDate->format('Y年m月d日'));

            $auctionDate = array(
                'en' => $startDateArray[$locale].' - '.$endDateArray[$locale],
                'trad' => $startDateArray[$locale].' 至 '.$endDateArray[$locale],
                'sim' => $startDateArray[$locale].' 至 '.$endDateArray[$locale],
            )
            ?>
            <div class="datetime">拍賣日期: {{ $auctionDate[$locale] }}</div>
            <div class="datetime">拍賣地點: {{ $auctionDetail->location }}</div>
            <?php
                $sales = $auction->sales();

//                dd($sales);

                $salesGroup1 = $sales->orderBy('number')->limit(6)->get();
//                dd($salesGroup1);
                $salesGroup2 = $sales->orderBy('number')->offset(6)->limit(6)->get();
//                dd($salesGroup2);
                $salesGroup3 = $sales->orderBy('number')->offset(12)->limit(2)->get();
//                dd($salesGroup3);
            ?>

                <!-- Swiper -->
                <div class="swiper-container">
                    <div class="swiper-wrapper">
                        @foreach($salesGroup1 as $sale)
                            <?php
                                $detail = $sale->details()->where('lang', $locale)->first();
                            ?>
                        <div class="swiper-slide">
                            <div class="row">
                                <div class="col-xs-5"><img src="{{ asset($sale->image_path) }}" class="img-responsive"></div>
                                <div class="col-xs-7 detail">

                                    <a class="cell-name" href="#">{{ $houseDetail->name }}</a><br>

                                    <div class="misc">
                                        <div class="cell-name">{{ $detail->title }}</div>

                                        <div>2017年05月09日 17:30</div>
                                        <div id="date-counter-1" class="date-counter"></div>
                                        <div style="height: 15px"></div>
                                        {{--拍卖地点：<span>{{ $detail->location }}</span><br>--}}
                                        拍卖总数：<span>{{ $sale->total_lots }}</span> 件<br>
                                        <a href="{{ route('frontend.auction.house.sale', ['slug' => $sale->slug]) }}" class="btn btn-primary btn-browse">觀看展品</a>

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
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="swiper-scrollbar"></div>
                </div>

                <!-- Swiper -->
                <div class="swiper-container">
                    <div class="swiper-wrapper">
                        @foreach($salesGroup2 as $sale)
                            <?php
                            $detail = $sale->details()->where('lang', $locale)->first();
                            ?>
                            <div class="swiper-slide">
                                <div class="row">
                                    <div class="col-xs-5"><img src="{{ asset($sale->image_path) }}" class="img-responsive"></div>
                                    <div class="col-xs-7 detail">

                                        <a class="cell-name" href="#">{{ $houseDetail->name }}</a><br>

                                        <div class="misc">
                                            <div class="cell-name">{{ $detail->title }}</div>

                                            <div>2017年05月09日 17:30</div>
                                            <div id="date-counter-1" class="date-counter"></div>
                                            <div style="height: 15px"></div>
{{--                                            拍卖地点：<span>{{ $detail->location }}</span><br>--}}
                                            拍卖总数：<span>{{ $sale->total_lots }}</span> 件<br>
                                            <a href="{{ route('frontend.auction.house.sale', ['slug' => $sale->slug]) }}" class="btn btn-primary btn-browse">觀看展品</a>

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
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="swiper-scrollbar"></div>
                </div>

                <!-- Swiper -->
                <div class="swiper-container">
                    <div class="swiper-wrapper">
                        @foreach($salesGroup3 as $sale)
                            <?php
                            $detail = $sale->details()->where('lang', $locale)->first();
                            ?>
                            <div class="swiper-slide">
                                <div class="row">
                                    <div class="col-xs-5"><img src="{{ asset($sale->image_path) }}" class="img-responsive"></div>
                                    <div class="col-xs-7 detail">

                                        <a class="cell-name" href="#">{{ $houseDetail->name }}</a><br>

                                        <div class="misc">
                                            <div class="cell-name">{{ $detail->title }}</div>

                                            <div>2017年05月09日 17:30</div>
                                            <div id="date-counter-1" class="date-counter"></div>
                                            <div style="height: 15px"></div>
                                            {{--拍卖地点：<span>{{ $detail->location }}</span><br>--}}
                                            拍卖总数：<span>{{ $sale->total_lots }}</span> 件<br>
                                            <a href="{{ route('frontend.auction.house.sale', ['slug' => $sale->slug]) }}" class="btn btn-primary btn-browse">觀看展品</a>

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
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="swiper-scrollbar"></div>
                </div>



        </div>

    @endforeach
</div>
