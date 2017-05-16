<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AuctionSeriesDetail extends Model
{

    protected $table = 'auction_series_details';

    public function series()
    {
        return $this->belongsTo('App\AuctionSeries', 'auction_series_id');
    }

}
