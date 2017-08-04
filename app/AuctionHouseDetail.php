<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AuctionHouseDetail extends Model
{
    public function house()
    {
        return $this->belongsTo('App\AuctionHouse', 'auction_house_id');
    }
}
