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

        return Storage::disk('local')->exists($this->getJSONPath($intSaleID));
    }

    public function getJSON($decode = true)
    {
        $intSaleID = $this->int_sale_id;

        if($decode) {
            return json_decode(Storage::disk('local')->get('spider/christie/past/json/'.$intSaleID.'.json'), true);
        } else {
            return Storage::disk('s3')->get($this->getJSONPath($intSaleID));
        }

    }

}
