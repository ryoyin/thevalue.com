@if(count($items) > 0)
<div class="misc-block item-related input-group selection">
    <div class="title">@lang('thevalue.related-lot')</div>
    @foreach($items as $iKey => $item)
        <?php $itemDetail = $item->details()->where('lang', $locale)->first() ?>
        <div class="col-xs-6 col-sm-4 col-md-6 col-lg-4 lot item">
            <div class="item lot-side" onclick="redirectItem(this)" url="{{ route('frontend.auction.house.sale.item', ['slug' => $slug, 'lot' => $item->id]) }}">
                <div class="col-md-6 col-lg-6"><img src="{{ config('app.s3_path').$item->image_fit_path }}" class="img-responsive"></div>
                <div class="lot-detail item-detail-lot col-md-6 col-lg-6">
                    <?php
                    $itemTitleEN = mb_substr($itemDetail->title, 0, 50, 'utf-8');
                    if(strlen($itemDetail->title) > 50) $itemTitleEN .= '...';
                    $itemTitleZH = mb_substr($itemDetail->title, 0, 30, 'utf-8');
                    $itemTitleZH = str_replace('，“', '， “', $itemTitleZH);
                    if(strlen($itemDetail->title) > 30) $itemTitleZH .= '...';
                    $itemTitleArray = array('en' => $itemTitleEN, 'trad' => $itemTitleZH, 'sim' => $itemTitleZH);
                    ?>
                    <div class="lot-title"><span>Lot {{ $item->number }}</span> <br>{{ $itemTitleArray[$locale] }}</div>
                    <?php
                    $estimate_initial = str_replace('HKD ', '', $item->estimate_value_initial);
                    $estimate_end = str_replace('HKD ', '', $item->estimate_value_end);
                    $estimate = $estimate_initial.' - '.$estimate_end;
                    $estimate = trans('thevalue.estimate').': '.$item->currency_code.' '.$estimate;

                    if($estimate_initial == '' && $estimate_end == '') {
                        $estimate = trans('thevalue.estimate-on-request');
                    }
                    ?>
                    <div class="lot-value">{{ $estimate }}</div>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endif