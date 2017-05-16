<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AuctionSale extends Model
{

    protected $table = 'auction_sales';

    public function series()
    {
        return $this->belongsTo('App\AuctionSeries', 'auction_series_id');
    }

    public function details()
    {
        return $this->hasMany('App\AuctionSaleDetails');
    }

    public function times()
    {
        return $this->hasMany('App\AuctionSaleTime');
    }

    public function items()
    {
        return $this->hasMany('App\AuctionItem');
    }

}
