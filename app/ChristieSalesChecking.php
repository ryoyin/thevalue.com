<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChristieSalesChecking extends Model
{
    protected $table = 'christie_sales_checking';

    public function details()
    {
        return $this->hasOne('App\ChristieSpiderSale', 'int_sale_id', 'int_sale_id');
    }
}
