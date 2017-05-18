<div class="pre-auction-block pre-ab-1">
    @foreach($auctions as $auction)
        <?php
        $house = $auction->house->first();
        $houseDetail = $house->details->where('lang', $locale)->first();
        ?>
        <div class="store-name"><img src="{{ asset($house->image_path) }}"><span>{{ $houseDetail->name }}</span></div>
        <div class="more"><a href="{{ route('frontend.auction.house', ['slug' => $house->slug]) }}">查看更多</a></div>
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
            <?php
                $sales = $auction->sales->take(6);
                $testArray = array(1,2,3,4,5,6);

            ?>

                <!-- Swiper -->
                <div class="swiper-container">


                    <div class="swiper-wrapper">
                        <?php for($i=0; $i<6; $i++) { ?>
                        <div class="swiper-slide">
                            <div class="row">
                                <div class="col-xs-5"><img src="{{ asset('images/auction-p1.jpg') }}" class="img-responsive"></div>
                                <div class="col-xs-7 detail">

                                    <a class="cell-name" href="#">伦敦佳士得</a><br>

                                    <div class="misc">
                                        <div class="cell-name">中國當代水墨畫</div>

                                        <div>2017年05月09日 17:30</div>
                                        <div id="date-counter-1" class="date-counter"></div>
                                        <div style="height: 15px"></div>
                                        拍卖地点：<span>8 King Street St. James’s London SW1Y 6QT</span><br>
                                        拍卖总数：<span>136</span> 件<br>
                                        <a class="btn btn-primary btn-browse">觀看展品</a>

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
                        <?php } ?>
                    </div>


                    <div class="swiper-scrollbar"></div>
                </div>


        </div>

    @endforeach
</div>
