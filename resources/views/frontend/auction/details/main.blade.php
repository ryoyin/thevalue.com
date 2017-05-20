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

                            <div class="misc">
                                <div class="cell-name">{{ $saleDetail->title }}</div>

                                <div style="float:left">2017年05月09日 17:30</div>
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

                            <div class="row">
                                @foreach($items as $iKey => $item)
                                    <?php $itemDetail = $item->details()->where('lang', $locale)->first() ?>
                                    <div class="col-xs-6 col-sm-3 col-md-3 col-lg-3 lot">
                                        <div class="lot-block item">
                                            <div class=""><img data-original="{{ config('app.s3_path').$item->image_fit_path }}" class="img-responsive lazy"></div>
                                            <div class="lot-detail">
                                                <?php
                                                    $itemTitle = mb_substr($itemDetail->title, 0, 70, 'utf-8');
                                                    if(strlen($itemDetail->title) > 70) $itemTitle .= '...';
                                                ?>
                                                <div class="lot-title"><span>Lot {{ $item->number }}</span> <br>{{ $itemTitle }}</div>
                                                <?php
                                                    $estimate_initial = str_replace('HKD ', '', $item->estimate_value_initial);
                                                    $estimate_end = str_replace('HKD ', '', $item->estimate_value_end);
                                                ?>
                                                <div class="lot-value">Estimate: {{ $item->currency_code }} {{ $estimate_initial }} - {{ $estimate_end }}</div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
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
    </script>

@endsection