<div class="item-body row">
    <div class="col-sm-5 col-md-5 item-image">
        <img src="{{ config('app.s3_path').$lot->image_medium_path }}" class="img-responsive">
        <div class="enlarge"><i class="fa fa-expand" aria-hidden="true"></i> click to enlarge</div>
    </div>
    <div class="col-sm-7 col-md-7 item-detail">
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