<div class="row item-head" url="{{ route('frontend.auction.house.sale', ['slug' => $sale->slug]) }}" onclick="redirectItem(this);">
    <div class="hidden-xs col-sm-2 col-md-2 col-lg-1">
        <img src="{{ asset($sale->image_path) }}" class="img-responsive">
    </div>
    <div class="col-xs-12 col-sm-10 col-md-10 col-lg-11 item-head-right">
        <div class="misc">
            <div class="cell-name">{{ $saleDetail->title }}</div>

            <?php
                $saleDateRaw = strtotime($sale->start_date);
                $saleDate = date('Y-m-d', $saleDateRaw);
            ?>
            <div style="float:left">{{ $saleDate }}</div>
            <div id="date-counter-1" class="date-counter" style="float:left"></div>
            <div style="clear:both"></div>
            @lang('thevalue.auction-location')：<span>{{ $saleDetail->location }}</span><br>
            @lang('thevalue.total-lots')：<span>{{ $sale->total_lots }}</span> <br>

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