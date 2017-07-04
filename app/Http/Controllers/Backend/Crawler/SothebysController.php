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

        set_time_limit(60000);

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

        foreach($salesArray['sale']['en']['lots'] as $lot) {
            $url = str_replace("'", '', $lot['condRep']);
            $exURL = explode('#', $url);
            $url = $exURL[0];

            $salesArray['lots'][] = array(
                // Useful Item: id, image, lowEst, highEst, salePrice, condRep (url)
                'number' => str_replace("'", '', $lot['id']),
                'image_path' => 'http://www.sothebys.com'.str_replace("'", '', $lot['image']),
                'currency' => str_replace("'", '', $lot['currency']),
                'estimate_initial' => str_replace("'", '', $lot['lowEst']),
                'estimate_end' => str_replace("'", '', $lot['highEst']),
                'realized_price' => str_replace("'", '', $lot['salePrice']),
                'url' => 'http://www.sothebys.com'.$url
            );
        }

        foreach($salesArray['lots'] as $lot) {
            // get lot en content
            $url = $lot['url'];
            $this->getLotContentByLang($url, $intSaleID, 'en', $lot['number']);

            // get lot zh content
            $url = str_replace('/en', '/zh', $lot['url']);
            $this->getLotContentByLang($url, $intSaleID, 'zh', $lot['number']);
        }

//        dd($salesArray);

//        exit;

        $storePath = 'spider/sothebys/sale/'.$intSaleID.'/';

        Storage::disk('local')->put($storePath . $intSaleID . '.json', json_encode($salesArray));

        $sale = App\SothebysSale::where('int_sale_id', $intSaleID)->first();
        $sale->html = true;
        $sale->save();

        return redirect()->route('backend.auction.sothebys.index');

    }

    public function parseItems($intSaleID)
    {
        $internalErrors = libxml_use_internal_errors(true);

        $storePath = 'spider/sothebys/sale/' . $intSaleID . '/' . $intSaleID . '.json';
        $json = Storage::disk('local')->get($storePath);
        $saleArray = json_decode($json, true);

        // auction_sales - slug, *source_image_path, *image_path, number, start_date, end_date

        // auction_sale_details - type (sale/viewing), title, country, location, lang

        // auction_sale_times - type (sale/viewing), lots, start_date, end_date

        // auction_items - slug, *dimension, number,
        // source_image_path, image_path, image_fit_path, image_large_path, image_medium_path, image_small_path,
        // currency_code, estimate_value_initial, estimate_value_end, sold_value, sorting, status

        // auction_item_details - title, description, *maker, misc, provenance, *post_lot_text, exhibited, lang

//        dd($saleArray);

        foreach($saleArray['lots'] as $key => $lot) {
            $saleArray['lots'][$key] = $this->getLotDetailsByLang($intSaleID, $lot['number'], 'en', $lot);
        }

//        dd($saleArray);

        $saleArray = json_encode($json);
        Storage::disk('local')->put($storePath, $saleArray);

        $sale = App\SothebysSale::where('int_sale_id', $intSaleID)->first();
        $sale->json = true;
        $sale->save();

        return redirect()->route('backend.auction.sothebys.index');

    }

    public function downloadImages($intSaleID)
    {
        $internalErrors = libxml_use_internal_errors(true);

        $storePath = 'spider/sothebys/sale/' . $intSaleID . '/' . $intSaleID . '.json';
        $json = Storage::disk('local')->get($storePath);
        $saleArray = json_decode($json, true);

        dd($saleArray);

    }

    private function getLotDetailsByLang($intSaleID, $number, $lang, $lot)
    {
//        echo $number;
//        echo '<br>';

        // get raw html content
        $storePath = 'spider/sothebys/sale/'.$intSaleID.'/'.$lang.'/';
        $path = $storePath.$number.'.html';
        $html = Storage::disk('local')->get($path);

        $dom = new \DOMDocument('1.0', 'UTF-8');
        $internalErrors = libxml_use_internal_errors(true);
        $dom->loadHTML($html);

//        echo $html;

        // get title - lotdetail-guarantee
        $finder = new \DomXPath($dom);
        $node = $finder->query("//*[contains(@class, 'lotdetail-guarantee')]");
        $title = $node->item(0)->textContent;
        $lot['title'] = $title;
        // - title

        // get misc - lotdetail-subtitle
        $node = $finder->query("//*[contains(@class, 'lotdetail-subtitle')]");
        if($node->length > 0) {
            $misc = $node->item(0)->textContent;
            $lot['misc'] = $misc;
        }
        // - misc

        // get description - lotdetail-description
        $node = $finder->query("//*[contains(@class, 'lotdetail-description-text')]");
        $description = $node->item(0)->textContent;
        $lot['description'] = trim(str_replace("\r\n", '', $description));
        // - description

        // get provenance - readmore-content
        $node = $finder->query("//*[contains(@class, 'readmore-content')]");
//        $provenanceBlock = $node->item(0)->ownerDocument->saveHTML($node->item(0));

        foreach($node as $key => $item) {

            if($key == 0) {
                $provenance = $item->textContent;
                $provenance = str_replace(";", ";<br>", $provenance);
                $lot['provenance'] = $provenance;
            }

            if($key == 1) {
                $exhibited = $item->textContent;
                $exhibited = str_replace(";", ";<br>", $exhibited);
                $lot['exhibited'] = $exhibited;
            }

        }

//        dd($lot);

        return $lot;

    }

    private function parseMainContentByLang($intSaleID, $lang)
    {
        $contentArray = array(); // declare content array

        // get raw html content
        $storePath = 'spider/sothebys/sale/'.$intSaleID.'/'.$lang.'/';
        $path = $storePath.$intSaleID.'.html';
        $html = Storage::disk('local')->get($path);

//        echo $html;

        $dom = new \DOMDocument('1.0', 'UTF-8');
        $internalErrors = libxml_use_internal_errors(true);
        $dom->loadHTML($html);

        $contentArray['title'] =  $this->getTitle($dom); // Get Title

        $contentArray['auction']['datetime'] = $this->getAuctionDateTime($dom); // Get Auction Datetime

        $contentArray['auction']['location'] = $this->getAuctionLocation($dom); // Get Auction Location - eventdetail-eventtime

        $contentArray['viewing'] = $this->getViewingInfo($dom); // Get Viewing Datetime -  class: eventdetail-times > ul

        // Get Lot Info
        $dom->loadHTML($html); //resset load html
        $contentArray['lots'] = $this->getLotInfo($dom);

        return $contentArray;

    }

    private function getTitle($dom)
    {
        $headerItems = $dom->getElementsByTagName('h1');

        $title = '';

        foreach($headerItems as $key => $item) {
            $title .= $item->textContent;
        }

        $title = str_replace("\r\n", '', $title);
        $title = str_replace('Now', '', $title);
        $title = str_replace('現在進行中', '', $title);
        $title = trim($title);

        return $title;
    }

    private function getAuctionDateTime($dom)
    {
        $timeItems = $dom->getElementsByTagName('time');

        $time = '';

        foreach($timeItems as $key => $item) {
            if($time != '') $time .= ' ';
            $time .= $item->textContent;
        }

        $time = strtotime($time);

        return $time;
    }

    private function getAuctionLocation($dom)
    {
        $finder = new \DomXPath($dom);
        $node = $finder->query("//*[contains(@class, 'eventdetail-eventtime')]");
        $auctionLocBlock = $node->item(0)->textContent;

        $exAuctionLoc = explode('|', $auctionLocBlock);
        $auctionLocation = $exAuctionLoc[count($exAuctionLoc) - 1];

        $auctionLocation = str_replace("\r\n", '', $auctionLocation);
        $auctionLocation = trim($auctionLocation);

        return $auctionLocation;
    }

    private function getViewingInfo($dom)
    {
        $finder = new \DomXPath($dom);
        $node = $finder->query("//*[contains(@class, 'eventdetail-times')]");

        if($node->length == 0) return false;

        $content = $dom->saveHTML($node->item(0));

        $locationBlock = $node->item(0)->textContent;

//        echo $locationBlock;
//        $locationBlock =
        $exLocation = explode("\r\n", $locationBlock);

        $foundItem = array();
        foreach($exLocation as $key => $item) {
            $parsed = trim(str_replace("\n", '', $item));
            if($parsed != '') {
                $foundItem[] = $parsed;
            }
        }

        $location = $foundItem[1];

//        echo $location;
//        echo '<br>';

        $dom->loadHTML($content);

        $viewingItems = $dom->getElementsByTagName('li');

        $viewingArray = array();
        foreach($viewingItems as $key => $item) {
            if($key > 0) {
                $viewingArray[] = $item->textContent;
            }
        }

        $viewingStartDatetime =  $viewingArray[0];
        $viewingEndDatetime =  $viewingArray[count($viewingArray) -1];

//        echo $viewingStartDatetime;
//        echo '<br>';
        $exViewingStart1 = explode(',', $viewingStartDatetime);
        $exViewingStart2 = explode('|', $exViewingStart1[1]);

//        echo $exViewingStart2[0];
//        echo '<br>';
//        echo $exViewingStart2[1];
        $exViewingStart3 = explode('-', $exViewingStart2[1]);
//        echo $exViewingStart3[0];
//        echo '<br>';
        $parsedViewingStartDatetime = $exViewingStart2[0].' '.$exViewingStart3[0].' BST';

        $viewingStartDatetime = strtotime($parsedViewingStartDatetime);
//        echo 'Viewing StartTime: '.$viewingStartDatetime;
//        echo '<br>';

        $exViewingEnd1 = explode(',', $viewingEndDatetime);
        $exViewingEnd2 = explode('|', $exViewingEnd1[1]);
        $exViewingEnd3 = explode('-', $exViewingEnd2[1]);

        $parsedViewingEndDatetime = $exViewingEnd2[0].' '.$exViewingEnd3[1];
        $viewingEndDatetime = strtotime($parsedViewingEndDatetime);

//        echo '<br>';
//        echo 'Viewing EndTime'.$viewingEndDatetime;
//        echo '<br>';

//        dd($viewingArray);

        return array(
            'location' => $location,
            'datetime' => array(
                'start' => $viewingStartDatetime,
                'end' => $viewingEndDatetime
            ),
        );

    }

    private function getLotInfo($dom)
    {
//        echo $dom->textContent;
        $scriptBlock = $dom->getElementsByTagName('script');

        foreach($scriptBlock as $key => $script){
            $readScript = $script->textContent;
            if(strpos($readScript, 'ECAT.lot')) {
                $foundScript = $readScript;
            }
//            echo '<br>';
        }
//        echo $foundScript;

        $exScript = explode('ECAT.', $foundScript);

//        dd($exScript);

        $rawLots = array();
        foreach($exScript as $key => $item) {

            if(strpos($item, 'lotItemId')) {
//                echo $item;
//                echo '<br>';
//                echo '<br>';
                $exItem = explode('=', $item);

                $parsedItem = '';

                foreach($exItem as $k => $i) {
                    if($k > 0) {
                        if($parsedItem != '') $parsedItem .= '=';
                        $parsedItem .= $i;
                    }
                }

                $item = substr($parsedItem, 1, -2);

//                echo $item;
//                echo '<br>';
//                echo '<br>';

                $item = str_getcsv($item, ',', "'");

//                dd($item);

                $rawLots[] = $item;
            }
        }

//        echo '<pre>';
//        print_r($rawLots);

        $lots = array();
        foreach($rawLots as $key => $item) {
            $lot = array();
//            dd($item);
            foreach($item as $nKey => $node) {
                $exNode = explode(':', $node);

                if(count($exNode) > 1) {
                    $lot[$exNode[0]] = $exNode[1];
                } else {
                    $lot[$exNode[0]] = '';
                }
            }
            $lots[] = $lot;
        }

        // Useful Item: id, image, lowEst, highEst, salePrice, condRep (url)

        return $lots;

//        exit;

    }

    public function getLotContentByLang($url, $intSaleID, $lang, $number)
    {
        set_time_limit(60000);

//        echo $url;

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
//
//         exit;

        $storePath = 'spider/sothebys/sale/' . $intSaleID . '/'.$lang.'/';

        Storage::disk('local')->put($storePath . $number . '.html', $content);

    }

}
