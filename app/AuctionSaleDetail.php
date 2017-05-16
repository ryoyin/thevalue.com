<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AuctionSaleDetail extends Model
{

    protected $table = 'auction_sale_details';

    public function sale()
    {
        return $this->belongsTo('App\AuctionSale', 'auction_sale_id');
    }

}
