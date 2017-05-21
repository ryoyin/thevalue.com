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