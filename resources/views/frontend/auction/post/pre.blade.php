<div class="panel-group auction-advance-search-block" role="tablist" style="margin-right: -15px;">
    <div class="panel panel-default">
        {{--<div class="panel-heading" role="tab" id="collapseListGroupHeading1">--}}
            {{--<h4 class="panel-title"> <a href="#collapseListGroup1" class="collapsed" role="button" data-toggle="collapse" aria-expanded="false" aria-controls="collapseListGroup1">--}}
                    {{--Collapsible list group </a> </h4>--}}
        {{--</div>--}}
        <div class="panel-heading" role="tab" id="collapseListGroupHeading1">
            <h4 class="panel-title"> <a href="#collapseListGroup1" class="collapsed" role="button" data-toggle="collapse" aria-expanded="false" aria-controls="collapseListGroup1" style="width: 100%; display: inline-block; text-decoration: none;">
                    @lang('thevalue.advance-search')
                </a> </h4>
        </div>
        <div class="panel-collapse collapse auction-advance-search" role="tabpanel" id="collapseListGroup1" aria-labelledby="collapseListGroupHeading1" aria-expanded="false" style="height: 0px;">
            <div class="panel-body">
                <div class="row">

                    <form method="POST" action="{{ route('frontend.auction.auction', ['slug' => 'post']) }}">

                        {{ csrf_field() }}

                        <input type="hidden" name="advance_search">

                        <div class="col-md-4" style="margin-bottom: 5px;">
                            <div class="input-group">
                                <div class="input-group-addon">@lang('thevalue.house')</div>
                                <select class="form-control" name="house">
                                    <option value="-">@lang('thevalue.please-select-2')</option>
                                    @foreach($houses as $house)
                                        <option value="{{ $house->slug }}"
                                            @if($advanceSearch)
                                                @if(isset($searchCriteria['houseSlug']) && $searchCriteria['houseSlug'] == $house->slug)
                                                    selected
                                                @endif
                                            @endif
                                        >{{ $house->getDetailByLang($locale)->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-1"></div>

                        <div class="col-md-4" style="margin-bottom: 5px;">
                            <div class="input-group">
                                <div class="input-group-addon">@lang('thevalue.date')</div>
                                <input type="text" name="start_date" class="form-control date" data-provide="datepicker" data-date-format="yyyy-mm-dd" value="{{ $rangeDatetime['start_date'] }}" required>
                                <div class="input-group-addon">-</div>
                                <input type="text" name="end_date" class="form-control date" data-provide="datepicker" data-date-format="yyyy-mm-dd" value="{{ $rangeDatetime['end_date'] }}" required>
                            </div>
                        </div>

                        <div class="col-md-2 pull-right" style="margin-bottom: 5px;">
                            <div class="input-group">
                                <input type="submit" class="btn btn-normal pull-right" value="@lang('thevalue.searchSubmit')" style="margin-right: 5px;">
                                <span class="input-group-btn">
                                    <input type="button" class="btn btn-normal" value="@lang('thevalue.searchReset')" name="reset" onclick="reset_search(); return false;">
                                </span>
                            </div>
                        </div>

                    </div>

                </form>

            </div>

        </div>
    </div>
</div>


<script>
    $(document).ready(function() {
        $('.auction-advance-search').collapse();
    });
</script>

<script>
    function reset_search() {
        window.location = '{{ route('frontend.auction.auction', ['slug' => 'post']) }}';
    }
</script>

<div class="pre-auction-block pre-ab-1">
    @foreach($series as $auction)
        <?php

//            dd($auction);

            $sales = $auction->sales()->where('end_date', '<', Carbon\Carbon::now()->format('Y-m-d'))->orderBy('start_date')->get();

            if(count($sales) == 0) continue;

            if($auction->id == 2) continue;
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
            <div class="store-name"><img src="{{ config('app.s3_path').$house->image_path }}"><span>{{ $houseDetail->name }}</span></div>
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
//                    $sales = $auction->sales;

                    $saleCounter = 0;
                ?>
                    <!-- Swiper -->
                    <div class="swiper-container">
                        <div class="swiper-wrapper">
                            @foreach($sales as $saleIndex => $sale)
                                <?php
                                    $saleCounter ++;
                                    $detail = $sale->details()->where('lang', $locale)->first();
                                ?>
                                <div class="swiper-slide">
                                    <div class="row">
                                        <div class="col-xs-5" style="min-height: 120px;"><a href="{{ route('frontend.auction.house.sale', ['slug' => $sale->slug]) }}"><img src="{{ config('app.s3_path').$sale->image_path }}" class="img-responsive"></a></div>
                                        <div class="col-xs-7 detail">

                                            <a class="cell-name" href="{{ $browseMore }}">{{ $houseDetail->name }}</a><br>

                                            <div class="misc">
                                                <div class="cell-name cell-title"><a href="{{ route('frontend.auction.house.sale', ['slug' => $sale->slug]) }}">{{ $detail->title }}</a></div>
                                                <?php
                                                    $sDate = strtotime($sale->start_date);

                                                    if($house->timezone != '') {
                                                        $dateTime = new \DateTime(null, new DateTimeZone($house->timezone));
                                                        $dateTime->setTimestamp($sDate);

                                                        $saleDate = array(
                                                            'en' => $dateTime->format('Y-m-d H:i:s T'),
                                                            'trad' => $dateTime->format('Y年m月d日 H:i:s T'),
                                                            'sim' => $dateTime->format('Y年m月d日 H:i:s T'),
                                                        );
                                                    } else {
                                                        $saleDate = array(
                                                            'en' => date('Y-m-d', $sDate),
                                                            'trad' => date('Y年m月d日', $sDate),
                                                            'sim' => date('Y年m月d日', $sDate),
                                                        );
                                                    }

                                                ?>

                                                <div>{{ $saleDate[$locale] }}</div>
                                                {{--<div id="date-counter-{{ $saleCounter }}" class="date-counter" date="{{ $sDate }}"></div>--}}
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