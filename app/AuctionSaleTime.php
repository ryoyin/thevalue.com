<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AuctionSaleTime extends Model
{

    protected $table = 'auction_sale_times';

    public function sale()
    {
        $this->belongsTo('App\AuctionSale', 'auction_sale_id');
    }

}
