<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AuctionItem extends Model
{

    protected $table = 'auction_items';

    public function sale()
    {
        return $this->belongsTo('App\AuctionSale', 'auction_sale_id');
    }

    public function detail()
    {
        return $this->hasMany('App\AuctionItemDetail');
    }

}
