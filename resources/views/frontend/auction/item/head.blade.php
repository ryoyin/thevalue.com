<div class="row item-head" url="{{ route('frontend.auction.house.sale', ['slug' => $sale->slug]) }}" onclick="redirectItem(this);">
    <div class="hidden-xs col-sm-2 col-md-2 col-lg-1">
        <img src="{{ config('app.s3_path').$sale->image_path }}" class="img-responsive">
    </div>
    <div class="col-xs-12 col-sm-10 col-md-10 col-lg-11 item-head-right">
        <div class="misc">
            <div class="cell-name">{{ $auctionTypeName }} - {{ $saleDetail->title }}</div>

            <?php
                $saleDateRaw = strtotime($sale->start_date);
//                $saleDate = date('Y-m-d', $saleDateRaw);

//                dd($house);

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
            ?>
            <div style="float:left">{{ $saleDate[$locale] }}</div>
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
    </div>
</div>