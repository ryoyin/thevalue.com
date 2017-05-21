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