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

                        if($item->status != 'pending') {
                            switch($item->status) {
                                case 'bought in':
                                    $lotValue = trans('thevalue.bought-in');
                                    break;
                                case 'withdraw':
                                    $lotValue = trans('thevalue.withdraw');
                                    break;
                                case 'noshow':
                                    $lotValue = trans('thevalue.realised-price').': '.trans('thevalue.noshow');
                                    break;
                                default:
                                    $soldValue = number_format( (int)$item->sold_value, 0, ".", "," );
                                    $lotValue = trans('thevalue.realised-price').': '.$item->currency_code.' '.$soldValue;
                            }
                        } else {
                            $estimate_initial = $item->estimate_value_initial;
                            $estimate_end = $item->estimate_value_end;
                            if($estimate_initial == '' && $estimate_end == '') {
                                $lotValue = trans('thevalue.estimate-on-request');
                            } else {
                                $estimate_initial = number_format( (int)$estimate_initial, 0, ".", "," );
                                $estimate_end = number_format( (int)$estimate_end, 0, ".", "," );;
                                $estimate = $estimate_initial.' - '.$estimate_end;
                                $lotValue = trans('thevalue.estimate').': '.$item->currency_code.' '.$estimate;
                            }
                        }

                    ?>
                    <div class="lot-value">{{ $lotValue }}</div>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endif