<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChristieSale extends Model
{
    public function sale()
    {
        return $this->hasOne('App\AuctionSale', 'number', 'sale_number');
    }
}
