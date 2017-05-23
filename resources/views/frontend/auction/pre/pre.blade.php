<div class="pre-auction-block pre-ab-1">
    @foreach($series as $auction)
        <?php
            $house = $auction->house;
            $houseDetail = $house->details->where('lang', $locale)->first();
        ?>
    <div style="position: relative;">
        <div class="store-name"><img src="{{ asset($house->image_path) }}"><span>{{ $houseDetail->name }}</span></div>
        <div class="more"><a href="{{ route('frontend.auction.house.upcoming', ['slug' => $house->slug]) }}">查看更多</a></div>
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
                switch($auction->id) {
                    case 1;
                        $salesGroup1 = $sales->wherein('number', [13267, 15710, 14336, 14337, 14338])->get();
                        break;
                    case 2:
                        $salesGroup1 = $sales->wherein('number', [14715, 14716, 13268, 13269])->get();
                        break;
                }

                /*$sales = $auction->sales();
                $salesGroup2 = $sales->wherein('number', [15710, 14336, 14337, 14338])->get();
                $sales = $auction->sales();
                $salesGroup3 = $sales->wherein('number', [14809, 15657, 15658, 14612])->get();
                $sales = $auction->sales();
                $salesGroup4 = $sales->wherein('number', [14715, 14716, 14557])->get();*/
                $saleCounter = 0;
            ?>
                <!-- Swiper -->
                <div class="swiper-container">
                    <div class="swiper-wrapper">
                        @foreach($salesGroup1 as $saleIndex => $sale)
                            <?php
                                $saleCounter ++;
                                $detail = $sale->details()->where('lang', $locale)->first();
                            ?>
                        <div class="swiper-slide">
                            <div class="row">
                                <div class="col-xs-5"><img src="{{ asset($sale->image_path) }}" class="img-responsive"></div>
                                <div class="col-xs-7 detail">

                                    <a class="cell-name" href="#">{{ $houseDetail->name }}</a><br>

                                    <div class="misc">
                                        <div class="cell-name">{{ $detail->title }}</div>
                                        <?php $sDate = strtotime($sale->start_date); ?>

                                        <div>{{ date('Y年m月d日', $sDate) }}</div>
                                        <div id="date-counter-{{ $saleCounter }}" class="date-counter"></div>
                                        <div style="height: 15px"></div>
                                        {{--拍卖地点：<span>{{ $detail->location }}</span><br>--}}
                                        拍卖总数：<span>{{ $sale->total_lots }}</span> 件<br>
                                        <a href="{{ route('frontend.auction.house.sale', ['slug' => $sale->slug]) }}" class="btn btn-primary btn-browse">觀看展品</a>

                                    </div>

                                    <script type="text/javascript">
                                        $("#date-counter-{{ $saleCounter }}")
                                            .countdown("{{ date('Y-m-d', $sDate) }}", function(event) {
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
    </div>
    @endforeach
</div>
