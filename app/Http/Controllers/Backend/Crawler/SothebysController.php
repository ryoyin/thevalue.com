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

//        exit;

        echo "try: ".$url;
        echo "<br>";

        // get EN Content;
        $this->getMainContentByLang($url, $intSaleID, 'en');
        //get ZH Content;
        $url = str_replace('/en/', '/zh/', $url);
        $this->getMainContentByLang($url, $intSaleID, 'zh');

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

    public function getMainContentByLang($url, $intSaleID, $lang)
    {

        $cSession = curl_init();

        curl_setopt($cSession,CURLOPT_URL,$url);
        curl_setopt($cSession, CURLOPT_HEADER, 0);
        curl_setopt($cSession,CURLOPT_RETURNTRANSFER,1);

        $headers = [
            'Accept: application/json, text/javascript, */*; q=0.01',
//            'Accept-Encoding: gzip, deflate',
//            'Accept-Language: zh-CN,zh;q=0.8',
            'Connection: keep-alive',
            'Content-Length: 0',
            'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
            'Host: www.sothebys.com',
            'Origin: http://www.sothebys.com',
            'Referer: '.$url, //Your referrer address
            'User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.87 Safari/537.36',
            'X-MicrosoftAjax: Delta=true',
            'X-Requested-With: XMLHttpRequest',
        ];

        curl_setopt($cSession,CURLOPT_HTTPHEADER, $headers);

        $content=curl_exec($cSession);

//        echo $content;

        // exit;

        $storePath = 'spider/sothebys/sale/' . $intSaleID . '/'.$lang.'/';

        Storage::disk('local')->put($storePath . $intSaleID . '.html', $content);

    }

    public function downloadData($intSaleID)
    {

        $mainContentEN = $this->parseMainContentByLang($intSaleID, 'en');
        $mainContentZH = $this->parseMainContentByLang($intSaleID, 'zh');

        $salesArray = array(
            'sale' => array(
                'en' => $mainContentEN,
                'zh' => $mainContentZH
            )
        );

        dd($salesArray);

        exit;

        $itemJSON = json_encode($itemArray);

        Storage::disk('local')->put($storePath . $intSaleID . '.json', $itemJSON);

        $sale = App\YiDuSale::where('int_sale_id', $intSaleID)->first();
        $sale->html = true;
        $sale->save();

        return redirect()->route('backend.auction.yidu.index');

    }

    private function parseMainContentByLang($intSaleID, $lang)
    {
        // get en data
        $storePath = 'spider/sothebys/sale/'.$intSaleID.'/'.$lang.'/';
        $path = $storePath.$intSaleID.'.html';

        $html = Storage::disk('local')->get($path);

//        echo $html;

        $sale = array();

        $dom = new \DOMDocument('1.0', 'UTF-8');
        $internalErrors = libxml_use_internal_errors(true);
        $dom->loadHTML($html);

        $headerItems = $dom->getElementsByTagName('h1');

        $title = '';

        foreach($headerItems as $key => $item) {
            $title .= $item->textContent;
        }

        $title = str_replace("\r\n", '', $title);
        $title = str_replace('Now', '', $title);
        $title = str_replace('現在進行中', '', $title);
        $title = trim($title);

        $contentArray = array(
            'title' => $title,
        );

        return $contentArray;

    }



}
