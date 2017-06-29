<?php

namespace App\Http\Controllers\Backend\Crawler;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

use Intervention\Image\Facades\Image;

use App\Http\Controllers\Controller;
use App;

class SothebysController extends Controller
{

    public function index()
    {
        $locale = App::getLocale();

        $sales = App\SothebysSale::all();

        $data = array(
            'locale' => $locale,
            'menu' => array('auction', 'crawler', 'sothebys.index'),
            'sales' => $sales
        );

        return view('backend.auctions.crawler.sothebys.index', $data);
    }

    public function crawler(Request $request)
    {
        $url = trim($request->url);

//        echo $url;

        $this->getSaleByURL($url);

        return redirect()->route('backend.auction.sothebys.index');
    }

    public function getSaleByURL($url)
    {

        set_time_limit(600);

        echo "Getting content from: ".$url;
        echo "<br>";

        $filename = str_replace('.html', '', basename($url));

        $exFilename = explode('-', $filename);
        $intSaleID = $exFilename[count($exFilename) -1];

        echo "Int Sale ID: ".$intSaleID;

        echo "<br>";


        $cSession = curl_init();

        curl_setopt($cSession,CURLOPT_URL,$url);
        curl_setopt($cSession,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($cSession,CURLOPT_HEADER, false);

        $content=curl_exec($cSession);

        $storePath = 'spider/sothebys/sale/' . $intSaleID . '/';

        Storage::disk('local')->put($storePath . $intSaleID . '.html', $content);

        // id	int_sale_id	html	json	image	import	status
        $sale = App\SothebysSale::where('int_sale_id', $intSaleID)->first();

        if(count($sale) == 0) {
            $sale = New App\SothebysSale;
            $sale->int_sale_id = $intSaleID;
        }

        $sale->html = false;
        $sale->json = false;
        $sale->image = false;
        $sale->resize = false;
        $sale->pushS3 = false;
        $sale->import = false;
        $sale->status = 0;
        $sale->save();

        echo 'Spider '.$url.' end';
        echo "<br>";

        return true;

    }

}
