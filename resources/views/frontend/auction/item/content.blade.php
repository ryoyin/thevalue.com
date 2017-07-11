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
                    <div class="store-name"><img src="{{ config('app.s3_path').$house->image_path }}"><span>{{ $houseDetail->name }}</span></div>
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
                        <div class="row">
                            <div class="col-md-9">

                                <!-- Left Heading -->
                            @include('frontend.auction.item.head')
                            <!-- /Left Heading -->

                                <!-- Left Body -->
                            @include('frontend.auction.item.body')

                            @include('frontend.auction.item.relatedLots')
                            <!-- /Left Body -->

                            </div>
                            <div class="hidden-xs hidden-sm col-md-3">
                                @include('frontend.auction.item.side')
                            </div>
                        </div>
                    </div>
                </div>


            </div>

        </div>

    </div>

</div>

<script>
    jQuery(function($) {
        $("img.lazy").lazyload();
    });
    jQuery(function($) {
     $('.item').matchHeight();
     });

    function redirectExhibit(obj) {
        var url = $(obj).val();
        window.location = url;
    }

    function redirectItem(obj) {
        var url = $(obj).attr('url');
        window.location = url;
    }
</script>