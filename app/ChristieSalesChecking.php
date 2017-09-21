<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ChristieSalesChecking extends Model
{
    protected $table = 'christie_sales_checking';

    public function details()
    {
        return $this->hasOne('App\ChristieSpiderSale', 'int_sale_id', 'int_sale_id');
    }

    private function getJSONPath($intSaleID)
    {
        return 'spider/christie/sale/' . $intSaleID . '/' . $intSaleID . '.json';
    }

    public function doesObjectExist()
    {
        $intSaleID = $this->int_sale_id;

        return Storage::disk('s3')->exists($this->getJSONPath($intSaleID));
    }

    public function getJSON()
    {
        $intSaleID = $this->int_sale_id;

        return json_decode(Storage::disk('s3')->get($this->getJSONPath($intSaleID)), true);
    }

}
