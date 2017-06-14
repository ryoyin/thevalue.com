<?php

namespace App\Http\Controllers\Backend\Crawler;


use App\Http\Controllers\Controller;
use App;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

use Intervention\Image\Facades\Image;

class YiDuController extends Controller
{
    public function index()
    {
        $locale = App::getLocale();


        $sales = App\YiDuSale::all();

        $data = array(
            'locale' => $locale,
            'menu' => array('auction', 'crawler', 'yidu.index'),
            'sales' => $sales
        );

        return view('backend.auctions.crawler.yidu.index', $data);
    }

    public function crawler(Request $request)
    {
        $intSaleID = trim($request->int_sale_id);

        $this->getSaleByIntSaleID($intSaleID);

        return redirect()->route('backend.auction.yidu.index');
    }

    public function getSaleByIntSaleID($intSaleID)
    {

        set_time_limit(600);

        echo "<p>";
        echo 'Spider '.$intSaleID.' start';
        echo "<br>";


        $url = 'http://www.yidulive.com/auctionlist/show.php?sid='.$intSaleID.'&counts=1000&o=0&plo=0&page=1';

        echo "<br>";
        echo "Getting content from: ".$url;
        echo "<br>";

        $cSession = curl_init();

        curl_setopt($cSession,CURLOPT_URL,$url);
        curl_setopt($cSession,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($cSession,CURLOPT_HEADER, false);

        $content=curl_exec($cSession);

        $storePath = 'spider/yidu/sale/' . $intSaleID . '/';

        Storage::disk('local')->put($storePath . $intSaleID . '.html', $content);

        // id	int_sale_id	html	json	image	import	status
        $sale = App\YiDuSale::where('int_sale_id', $intSaleID)->first();

        if(count($sale) == 0) {
            $sale = New App\YiDuSale;
            $sale->int_sale_id = $intSaleID;
        }

        $sale->html = false;
        $sale->json = false;
        $sale->image = false;
        $sale->import = false;
        $sale->status = 0;
        $sale->save();

        echo 'Spider '.$intSaleID.' end';
        echo "<br>";

        return true;

    }

    public function makeSaleInfo($intSaleID)
    {
        $storePath = 'spider/yidu/sale/'.$intSaleID.'/';
        $path = $storePath.$intSaleID.'.html';

        $html = Storage::disk('local')->get($path);

//        echo $html;

        $sale = array();

        $dom = new \DOMDocument('1.0', 'UTF-8');
        $internalErrors = libxml_use_internal_errors(true);
        $dom->loadHTML($html);

//        exit;

        $finder = new \DomXPath($dom);
        $node = $finder->query("//*[contains(@class, 'content')]");
        $content = $dom->saveHTML($node->item(0));

        $dom->loadHTML($content);

        $items = $dom->getElementsByTagName('li');

        $itemArray = array();

        foreach($items as $key => $item) {
            $getItemLink = $item->getElementsByTagName('span');
            $getItemLink = $getItemLink[0]->getElementsByTagName('a');

            $itemPath = 'http://www.yidulive.com/auctionlist/';

            $itemLink = $getItemLink[0]->getAttribute('href');

            $itemURL = $itemPath.$itemLink;

            $itemArray[$key]['url'] = $itemURL;

            $itemFilePath = $this->getItemByURL($intSaleID, $key, $itemURL);

            $itemArray[$key]['filePath'] = $itemFilePath;

//            exit;
        }

        $itemJSON = json_encode($itemArray);

        Storage::disk('local')->put($storePath . $intSaleID . '.json', $itemJSON);

        $sale = App\YiDuSale::where('int_sale_id', $intSaleID)->first();
        $sale->html = true;
        $sale->save();

        return redirect()->route('backend.auction.yidu.index');

    }

    private function getItemByURL($intSaleID, $key, $url)
    {
        set_time_limit(600);

        echo "<br>";
        echo "Getting content from: ".$url;
        echo "<br>";

        $cSession = curl_init();

        curl_setopt($cSession,CURLOPT_URL,$url);
        curl_setopt($cSession,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($cSession,CURLOPT_HEADER, false);

        $content=curl_exec($cSession);

        $storePath = 'spider/yidu/sale/'.$intSaleID.'/item/';

        $filePath = $storePath.$key.'.html';

        Storage::disk('local')->put($filePath, $content);

        return $filePath;
    }

    public function crawlerRemove($intSaleID)
    {

        $intSaleID = trim($intSaleID);

        $path = 'spider/yidu/sale/'.$intSaleID;

        Storage::disk('local')->delete($path.'/'.$intSaleID.'.json');
        Storage::disk('local')->deleteDirectory($path);

        $sale = App\YiDuSale::where('int_sale_id', $intSaleID)->first();
//        dd($sale);
        $sale->delete();

        return redirect()->route('backend.auction.yidu.index');

    }

    public function parseItems($intSaleID)
    {
        $internalErrors = libxml_use_internal_errors(true);

        $storePath = 'spider/yidu/sale/'.$intSaleID.'/'.$intSaleID.'.json';
        $json = Storage::disk('local')->get($storePath);
        $itemArray = json_decode($json, true);

//        dd($saleArray);
        $itemInfoArray = array();

        foreach($itemArray as $item ) {
            $html = Storage::disk('local')->get($item['filePath']);

            $dom = new \DOMDocument('1.0', 'UTF-8');

            $dom->loadHTML($html);

            $imageBlock = $dom->getElementById('detailImg-box');

            $imageBlock = $imageBlock->getElementsByTagName('a');

//            $imageBlock = $imageBlock[0]->ownerDocument->saveHTML($imageBlock[0]);

            $imagePath = $imageBlock[0]->getAttribute('href');



            exit;
        }
    }
}
