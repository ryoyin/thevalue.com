@extends('frontend.template.layout')

@section('content')

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

                    <div class="pre-auction-block">
                        <div class="store-name"><img src="{{ asset($house->image_path) }}"><span>{{ $houseDetail->name }}</span></div>
                        <div class="more"><a href="{{ route('frontend.auction.auction', ['slug' => 'upcoming']) }}"">查看更多</a></div>
                        <div class="series">
                            <div class="title">拍卖预展 - {{ $seriesDetail->name }}</div>
                            <div class="input-group selection">
                                <span class="input-group-addon" id="basic-addon1">請選擇 :</span>
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
                            <div class="row">
                                <div class="col-md-9">

                                    <!-- Left Heading -->
                                    <div class="row item-head" url="{{ route('frontend.auction.house.sale', ['slug' => $sale->slug]) }}" onclick="redirectItem(this);">
                                        <div class="hidden-xs col-sm-2 col-md-2 col-lg-1">
                                            <img src="{{ asset($sale->image_path) }}" class="img-responsive">
                                        </div>
                                        <div class="col-xs-12 col-sm-10 col-md-10 col-lg-11"  style="padding: 0 0 0 20px;">
                                            <div class="misc">
                                                <div class="cell-name">{{ $saleDetail->title }}</div>

                                                <?php
                                                    $saleDateRaw = strtotime($sale->start_date);
                                                    $saleDate = date('Y-m-d', $saleDateRaw);
                                                ?>
                                                <div style="float:left">{{ $saleDate }}</div>
                                                <div id="date-counter-1" class="date-counter" style="float:left"></div>
                                                <div style="clear:both"></div>
                                                拍卖地点：<span>{{ $saleDetail->location }}</span><br>
                                                拍卖总数：<span>{{ $sale->total_lots }}</span> 件<br>

                                            </div>

                                            <script type="text/javascript">
                                                $("#date-counter-1")
                                                    .countdown("{{ $sale->start_date }}", function(event) {
                                                        $(this).text(
                                                            event.strftime('(%D days %H:%M:%S)')
                                                        );
                                                    });
                                            </script>
                                        </div>
                                    </div>
                                    <!-- /Left Heading -->

                                    <!-- Left Body -->
                                    <div class="item-body row">
                                        <div class="col-md-5 item-image">
                                            <img src="{{ config('app.s3_path').$lot->image_medium_path }}" class="img-responsive">
                                            <div class="enlarge"><i class="fa fa-expand" aria-hidden="true"></i> click to enlarge</div>
                                        </div>
                                        <div class="col-md-7 item-detail">
                                            <div class="lot-number">Lot {{ $lot->number }}</div>
                                            <div class="lot-title">{{ $lotDetail->title }}</div>
                                            <div class="lot-stitle">{{ $lotDetail->secondary_title }}</div>
                                            <div class="lot-desc">{{ $lotDetail->description }}</div>
                                            <?php
                                            $estimate_initial = str_replace('HKD ', '', $lot->estimate_value_initial);
                                            $estimate_end = str_replace('HKD ', '', $lot->estimate_value_end);
                                            $estimate = $estimate_initial.' - '.$estimate_end;

                                            if($estimate_initial == '' && $estimate_end == '') $estimate = 'Estimate on Request';
                                            ?>
                                            <div class="lot-estimate">Estimate<br>{{$lot->currency_code}} {{ $estimate }}</div>
                                        </div>
                                    </div>

                                    <div class="misc-block item-related input-group selection">
                                        <div class="title">Related Lots</div>
                                        @foreach($items as $iKey => $item)
                                            <?php $itemDetail = $item->details()->where('lang', $locale)->first() ?>
                                            <div class="col-xs-6 col-sm-3 col-md-12 col-lg-4 lot">
                                                <div class="item lot-side" onclick="redirectItem(this)" url="{{ route('frontend.auction.house.sale.item', ['slug' => $slug, 'lot' => $item->id]) }}">
                                                    <div class="col-md-12 col-lg-6"><img data-original="{{ config('app.s3_path').$item->image_fit_path }}" class="img-responsive lazy"></div>
                                                    <div class="lot-detail col-md-12 col-lg-6">
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
                                                        $estimate_initial = str_replace('HKD ', '', $item->estimate_value_initial);
                                                        $estimate_end = str_replace('HKD ', '', $item->estimate_value_end);
                                                        $estimate = $estimate_initial.' - '.$estimate_end;

                                                        if($estimate_initial == '' && $estimate_end == '') $estimate = 'Estimate on Request';
                                                        ?>
                                                        <div class="lot-value">Estimate: {{ $item->currency_code }} {{ $estimate }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <!-- /Left Body -->

                                </div>
                                <div class="col-md-3">
                                    @foreach($sales as $rSale)
                                        <div class="col-xs-6 col-sm-3 col-md-12 col-lg-12 lot">
                                            <div class="sale-side" url="{{ route('frontend.auction.house.sale', ['slug' => $rSale->slug]) }}" onclick="redirectItem(this);">
                                                <div class="col-md-12 col-lg-12 sale-side-block" style="text-align: center;">
                                                    <img src="{{ asset($rSale->image_path) }}" class="img-responsive lazy">
                                                    <?php
                                                        $rSaleDetail = $rSale->details()->where('lang', $locale)->first();
                                                    ?>
                                                    <div class="title">{{ $rSaleDetail->title }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>


                </div>

                {{--<div class="hidden-xs hidden-sm col-md-9 col-lg-9">--}}

                    {{--@include('frontend.auction.index.content-left')--}}

                {{--</div>--}}

            </div>

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

@endsection