<div class="pre-auction-block pre-ab-1">
    @foreach($series as $auction)
        <?php
            if($auction->id == 2) break;
            $house = $auction->house;
            $houseDetail = $house->details->where('lang', $locale)->first();

            $customLocale = array('en' => 'en','trad' => 'zh','sim' => 'zh');

            if($house->id == 2) {
                $browseMore = 'http://www.sothebys.com/'.$customLocale[$locale].'/auctions.html';
                $target = 'target="_blank"';
            } else {
                $browseMore = route('frontend.auction.house.post', ['slug' => $house->slug]);
                $target = '';
            }
        ?>
        <div style="position: relative;">
            <div class="store-name"><img src="{{ asset($house->image_path) }}"><span>{{ $houseDetail->name }}</span></div>
            <div class="more"><a href="{{ $browseMore }}" {{ $target }}>@lang('thevalue.browse')</a></div>
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
                <div class="datetime">@lang('thevalue.auction-date'): {{ $auctionDate[$locale] }}</div>
                <div class="datetime">@lang('thevalue.auction-location'): {{ $auctionDetail->location }}</div>
                <?php
                    $sales = $auction->sales();
                    switch($auction->id) {
                        case 1;
                            $salesGroup1 = $sales->wherein('number', [13267])->get();
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
                            @if(count($salesGroup1) == 0)
                                <div class="swiper-slide">
                                    <div class="row">
                                        <?php
                                            $saleCounter ++;
                                            $customTitle = array('en' => 'Qing dynasty jades from a Hong Kong Collection','trad' => '瓊林美玉 – 香港清玉收藏','sim' => '琼林美玉 - 香港清玉收藏');
                                        ?>
                                        <div class="col-xs-5">
                                            <a href="http://www.sothebys.com/{{ $customLocale[$locale] }}/auctions/2017/qing-dynasty-jade-carvings-from-hong-kong-collection-hk0771.html" target="_blank">
                                                <img src='{{ asset('images/company_logo/HK0771_auctionlist.jpg') }}' class="img-responsive">
                                            </a>
                                        </div>
                                        <div class="col-xs-7 detail">

                                            <a class="cell-name" href="#">{{ $houseDetail->name }}</a><br>

                                            <div class="misc">
                                                <div class="cell-name">
                                                    <a href="http://www.sothebys.com/{{ $customLocale[$locale] }}/auctions/2017/qing-dynasty-jade-carvings-from-hong-kong-collection-hk0771.html" target="_blank">
                                                        {{ $customTitle[$locale] }}
                                                    </a>
                                                </div>
                                                <?php
                                                $sDate = strtotime('2017-06-01 10:00:00');
                                                $saleDate = array(
                                                    'en' => date('Y-m-d', $sDate),
                                                    'trad' => date('Y年m月d日', $sDate),
                                                    'sim' => date('Y年m月d日', $sDate),
                                                );
                                                ?>

                                                <div>{{ $saleDate[$locale] }}</div>
{{--                                                <div id="date-counter-{{ $saleCounter }}" class="date-counter"></div>--}}
                                                <div style="height: 15px"></div>
                                                {{--拍卖地点：<span>{{ $detail->location }}</span><br>--}}
                                                @lang('thevalue.total-lots')：<span>100</span><br>
                                                <a href="http://www.sothebys.com/{{ $customLocale[$locale] }}/auctions/2017/qing-dynasty-jade-carvings-from-hong-kong-collection-hk0771.html" target="_blank" class="btn btn-primary btn-browse">@lang('thevalue.browse-lots')</a>

                                            </div>

                                            {{--<script type="text/javascript">
                                                $("#date-counter-{{ $saleCounter }}")
                                                    .countdown("{{ date('Y-m-d', $sDate) }}", function(event) {
                                                        $(this).text(
                                                            event.strftime('%D days %H:%M:%S')
                                                        );
                                                    });
                                            </script>--}}

                                        </div>
                                    </div>
                                </div>
                                <div class="swiper-slide">
                                    <div class="row">
                                        <?php
                                        $saleCounter ++;

                                        $locale = App::getLocale();
                                        $customTitle = array(
                                            'en' => 'Chinese Art, including the late John Payne collection of Japanese prints',
                                            'trad' => '中國藝術品 - 包括John Payne舊藏日本版畫',
                                            'sim' => '中国艺术品 - 包括John Payne旧版日本版画',
                                        )
                                        ?>
                                        <div class="col-xs-5">
                                            <a href="http://www.sothebys.com/{{ $customLocale[$locale] }}/auctions/2017/chinese-art-hk0732.html" target="_blank">
                                                <img src='{{ asset('images/company_logo/HK0732_auctionlist.jpg') }}' class="img-responsive">
                                            </a>
                                        </div>
                                        <div class="col-xs-7 detail">

                                            <a class="cell-name" href="{{ $browseMore }}">{{ $houseDetail->name }}</a><br>

                                            <div class="misc">
                                                <div class="cell-name">
                                                    <a href="http://www.sothebys.com/{{ $customLocale[$locale] }}/auctions/2017/chinese-art-hk0732.html" target="_blank">
                                                        {{ $customTitle[$locale] }}
                                                    </a>
                                                </div>
                                                <?php
                                                $sDate = strtotime('2017-06-01 14:00:00');
                                                $saleDate = array(
                                                    'en' => date('Y-m-d', $sDate),
                                                    'trad' => date('Y年m月d日', $sDate),
                                                    'sim' => date('Y年m月d日', $sDate),
                                                );
                                                ?>

                                                <div>{{ $saleDate[$locale] }}</div>
{{--                                                <div id="date-counter-{{ $saleCounter }}" class="date-counter"></div>--}}
                                                <div style="height: 15px"></div>
                                                {{--拍卖地点：<span>{{ $detail->location }}</span><br>--}}
                                                @lang('thevalue.total-lots')：<span>483</span><br>
                                                <a href="http://www.sothebys.com/{{ $customLocale[$locale] }}/auctions/2017/chinese-art-hk0732.html" target="_blank" class="btn btn-primary btn-browse">@lang('thevalue.browse-lots')</a>

                                            </div>

                                            {{--<script type="text/javascript">
                                                $("#date-counter-{{ $saleCounter }}")
                                                    .countdown("{{ date('Y-m-d', $sDate) }}", function(event) {
                                                        $(this).text(
                                                            event.strftime('%D days %H:%M:%S')
                                                        );
                                                    });
                                            </script>--}}

                                        </div>
                                    </div>
                                </div>
                            @endif
                            @foreach($salesGroup1 as $saleIndex => $sale)
                                <?php
                                    $saleCounter ++;
                                    $detail = $sale->details()->where('lang', $locale)->first();
                                ?>
                                <div class="swiper-slide">
                                    <div class="row">
                                        <div class="col-xs-5"><a href="{{ route('frontend.auction.house.sale', ['slug' => $sale->slug]) }}"><img src="{{ asset($sale->image_path) }}" class="img-responsive"></a></div>
                                        <div class="col-xs-7 detail">

                                            <a class="cell-name" href="{{ $browseMore }}">{{ $houseDetail->name }}</a><br>

                                            <div class="misc">
                                                <div class="cell-name cell-title"><a href="{{ route('frontend.auction.house.sale', ['slug' => $sale->slug]) }}">{{ $detail->title }}</a></div>
                                                <?php
                                                    $sDate = strtotime($sale->start_date);
                                                    $saleDate = array(
                                                      'en' => date('Y-m-d', $sDate),
                                                      'trad' => date('Y年m月d日', $sDate),
                                                      'sim' => date('Y年m月d日', $sDate),
                                                    );
                                                ?>

                                                <div>{{ $saleDate[$locale] }}</div>
{{--                                                <div id="date-counter-{{ $saleCounter }}" class="date-counter" date="{{ $sDate }}"></div>--}}
                                                <div style="height: 15px"></div>
                                                {{--拍卖地点：<span>{{ $detail->location }}</span><br>--}}
                                                @lang('thevalue.total-lots')：<span>{{ $sale->total_lots }}</span><br>
                                                <a href="{{ route('frontend.auction.house.sale', ['slug' => $sale->slug]) }}" class="btn btn-primary btn-browse">@lang('thevalue.browse-lots')</a>

                                            </div>

                                            {{--<script type="text/javascript">
                                                $("#date-counter-{{ $saleCounter }}")
                                                    .countdown("{{ date('Y-m-d', $sDate) }}", function(event) {
                                                        $(this).text(
                                                            event.strftime('%D days %H:%M:%S')
                                                        );
                                                    });
                                            </script>--}}

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
