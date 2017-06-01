<script src="{{ asset('js/jquery/jquery.countdown.min.js') }}"></script>
<script src="{{ asset('js/jquery/jquery.lazyload.min.js') }}"></script>
<script src="{{ asset('js/jquery/jquery.matchHeight-min.js') }}"></script>

<hr style="padding: 0; margin:0">

<div class="row auction auction-detail" id="home-content">

    <div id="left">

        <div id="block" style="border: 0px !important">

            <div id="category-head">

            </div>

            <div style="clear: both"></div>

            {{--<div class="hidden-md hidden-lg">--}}
            <div class="">

                <?php
                $nowDateTime = date('Y-m-d 00:00:00');

                if($sale->start_date >= $nowDateTime) {
                    $auctionTypeName = trans('thevalue.upcoming-auction');
                    $auctionType = 'upcoming';
                } else {
                    $auctionTypeName = trans('thevalue.post-auction');
                    $auctionType = 'post';
                }

                ?>

                <div class="pre-auction-block">
                    <div class="store-name"><img src="{{ asset($house->image_path) }}"><span>{{ $houseDetail->name }}</span></div>
                    <div class="more"><a href="{{ route('frontend.auction.auction', ['slug' => $auctionType]) }}">@lang('thevalue.browse')</a></div>
                    <div class="series">
                        <div class="title">{{ $seriesDetail->name }}</div>
                        <div class="input-group selection">
                            <span class="input-group-addon" id="basic-addon1">@lang('thevalue.please-select')</span>
                            <?php
                            $seriesSales = $series->sales()->orderBy('start_date')->get();
                            ?>
                            <select class="form-control" id="sel1" aria-describedby="basic-addon1" onchange="redirectExhibit(this);">
                                @foreach($seriesSales as $seriesSale)
                                    <?php $seriesSaleDetail = $seriesSale->details()->where('lang', $locale)->first(); ?>
                                    <option
                                            @if($seriesSale->slug == $sale->slug)
                                            selected
                                            @endif
                                            value="{{ route('frontend.auction.house.sale', ['slug' => $seriesSale->slug]) }}">{{ $seriesSaleDetail->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="misc">
                            <div class="cell-name">{{ $auctionTypeName }} - {{ $saleDetail->title }}</div>
                            <?php
                            $sDate = strtotime($sale->start_date);
                            $sDate = array(
                                'en' => date('Y-m-d', $sDate),
                                'trad' => date('Y年m月d日', $sDate),
                                'sim' => date('Y年m月d日', $sDate),
                            );
                            ?>

                            <div style="float:left">@lang('thevalue.auction-date')： {{ $sDate[$locale] }}</div>
                                {{--<div id="date-counter-1" class="date-counter" style="float:left"></div>

                                <script type="text/javascript">
                                    $("#date-counter-1")
                                        .countdown("{{ $sale->start_date }}", function(event) {
                                            $(this).text(
                                                event.strftime('(%D days %H:%M:%S)')
                                            );
                                        });
                                </script>--}}
                            <div style="clear:both"></div>
                            @lang('thevalue.auction-location')：<span>{{ $saleDetail->location }}</span><br>
                            @lang('thevalue.total-lots')：<span>{{ $sale->total_lots }}</span> <br>

                        </div>

                        <div class="row">
                            @foreach($items as $iKey => $item)
                                <?php
//                                    $customLocale = $locale == 'sim' ? 'trad' : $locale;
                                    $itemDetail = $item->details()->where('lang', $locale)->first()
                                ?>
                                <div class="col-xs-6 col-sm-3 col-md-3 col-lg-3 lot">
                                    <div class="lot-block item" onclick="redirectItem(this)" url="{{ route('frontend.auction.house.sale.item', ['slug' => $slug, 'lot' => $item->id]) }}">
                                        <div class=""><img data-original="{{ config('app.s3_path').$item->image_fit_path }}" class="img-responsive lazy"></div>
                                        <div class="lot-detail">
                                            <?php
                                                $itemTitleEN = mb_substr($itemDetail->title, 0, 70, 'utf-8');
                                                if(strlen($itemDetail->title) > 70) $itemTitleEN .= '...';
                                                $itemTitleZH = mb_substr($itemDetail->title, 0, 48, 'utf-8');
                                                $itemTitleZH = str_replace('，“', '， “', $itemTitleZH);
                                                if(strlen($itemDetail->title) > 40) $itemTitleZH .= '...';
                                                $itemTitleArray = array('en' => $itemTitleEN, 'trad' => $itemTitleZH, 'sim' => $itemTitleZH);
                                            ?>
                                            <div class="lot-title"><span>Lot {{ $item->number }}</span> <br>{{ $itemTitleArray[$locale] }}</div>
                                            <?php
                                                if($item->sold_value != null) {
                                                   switch($item->sold_value) {
                                                       case 'bought in':
                                                           $lotValue = trans('thevalue.bought-in');
                                                           break;
                                                       case 'withdraw':
                                                           $lotValue = trans('thevalue.withdraw');
                                                           break;
                                                       default:
                                                           $soldValue = number_format( $item->sold_value, 0, ".", "," );
                                                           $lotValue = trans('thevalue.realised-price').': '.$item->currency_code.' '.$soldValue;
                                                   }
                                                } else {

                                                    $estimate_initial = $item->estimate_value_initial;
                                                    $estimate_end = $item->estimate_value_end;

                                                    if($estimate_initial == '' && $estimate_end == '') {
                                                        $lotValue = trans('thevalue.estimate-on-request');
                                                    } else {
                                                        $estimate_initial = number_format( $estimate_initial, 0, ".", "," );
                                                        $estimate_end = number_format( $estimate_end, 0, ".", "," );
                                                        $lotValue = trans('thevalue.estimate').': '.$item->currency_code.' '.$estimate_initial.' - '.$estimate_end;
                                                    }
                                                }
                                            ?>
                                            <div class="lot-value">{{ $lotValue }}</div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="row">
                            <div class="pull-right item-detail-pagination">{{ $items->links() }}</div>
                        </div>
                    </div>
                </div>


            </div>

            {{--<div class="hidden-xs hidden-sm col-md-9 col-lg-9">--}}

            {{--@include('frontend.auction.index.content-left')--}}

            {{--</div>--}}

        </div>

    </div>

    <div class="hidden-xs hidden-sm col-md-3 col-lg-3">

        @include('frontend.auction.pre.content-right')

    </div>

</div>

<script>
    jQuery(function($) {
        $("img.lazy").lazyload();
    });
    /*jQuery(function($) {
     $('.item').matchHeight();
     });*/

    function redirectExhibit(obj) {
        var url = $(obj).val();
        window.location = url;
    }

    function redirectItem(obj) {
        var url = $(obj).attr('url');
        window.location = url;
    }
</script>