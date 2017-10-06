<?php

namespace App\Http\Controllers\Backend\Crawler\Calender;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ChristieController extends Controller
{
    public function getSaleByIntSaleID($intSaleID)
    {
        set_time_limit(6000);

        echo "<p>";
        echo 'Spider '.$intSaleID.' start';
        echo "<br>";

        $url = "http://www.christies.com/calendar/";
        $content = $this->getContentByURL($url); // get content from christie

        $sale = array();

        $dom = new \DOMDocument('1.0', 'UTF-8');
        $internalErrors = libxml_use_internal_errors(true);
        $dom->loadHTML($content);

        $finder = new \DomXPath($dom);


        $nodes = $finder->query("//*[contains(@class, 'chr-result-block-inner')]");

        $calenderInfo = array();

        echo '<pre>';

        foreach($nodes as $node) {

//            echo $node->textContent;

//            echo "\n";

            $links = $node->getElementsByTagName('a');

            if($this->isSale($intSaleID, $links)) {

                $saleInfo = $this->getSaleInfoCSV($links);
                if($saleInfo != null) {
                    $calenderInfo[] = $saleInfo;
                }

//                dd($saleInfo);

//                echo $node->textContent;

            }
        }

        dd($calenderInfo);

        exit;

        $nodes = $finder->query("//*[contains(@class, 'chr-sale-lot-info-has-dialog')]");

        foreach($nodes as $node) {
            /*$link = $node->getElementsByTagName('a');
            $info = str_replace('javascript:ShowSaleInfoContent_Multilanguage(','', $link[0]->getAttribute('onclick'));
            $info = str_replace('); return false;','', $info);
            $data = explode("',", $info);
            $rawSaleID = explode('-', $data[0]);*/

        }
        exit;

        $saleArray = $this->makeSaleInfo($intSaleID, $content, false);

        if ($saleArray === false) {
            return redirect('backend.auction.christie.index')->with('warning', 'Sale not exist!');
        }

        $saleJSON = json_encode($saleArray);

        $storePath = 'spider/christie/sale/' . $intSaleID . '/';

        Storage::disk('local')->put($storePath . $intSaleID . '.json', $saleJSON);

        $saleID = $saleArray['sale']['id'];

        echo 'Spider '.$intSaleID.' end';
        echo "<br>";

        return $saleArray;

    }

    private function isSale($intSaleID, $links)
    {
        foreach($links as $link) {
            $href = $link->getAttribute('href');
            $rawIntSaleID = explode('=', $href);
            $rawIntSaleID = $rawIntSaleID[count($rawIntSaleID)-1];

            if($intSaleID == $rawIntSaleID) {
                return true;
            }
        }
    }

    private function getSaleInfoCSV($links)
    {
        foreach($links as $link) {
            $onclick = $link->getAttribute('onclick');

            if(strpos($onclick, 'ShowSaleInfoContent_Multilanguage')) {

                $info = str_replace('javascript:ShowSaleInfoContent_Multilanguage(','', $onclick);
                $info = str_replace('); return false;','', $info);
                $data = explode("',", $info);

//                dd($data);

                $rawSaleID = explode('-', $data[0]);
                $rawSaleID = $rawSaleID[1];

                $rawAuctionTime = $data[2]; //auction time

                echo "paring auction time: \n";
                $auctionTime = $this->getDatetime($rawAuctionTime);

//                dd($exRawAuctionTime);

                $exViewingTime = explode('~', str_replace('^', '', $data[3]));
                $location = $exViewingTime[0];
                $rawViewingTime = $exViewingTime[1];

                echo "paring viewing time: \n";
                $viewingTime = $this->getDatetime($rawViewingTime);

                $info = array(
                    'saleID' => $rawSaleID,
                    'location' => $location,
                    'auction' => $auctionTime,
                    'viewing' => $rawViewingTime
                );

                return $info;
            }
        }
    }

    private function getDatetime($rawAuctionTime)
    {
        $exRawAuctionTime = explode('|', $rawAuctionTime);

        $auctionTime = array();

        foreach($exRawAuctionTime as $i) {

            echo 'start parsing'."\n";
            echo $i."\n";

            if($i != "") {
                $exI = explode("#", $i);

                $date = explode(" ", $exI[0]);

                $datetime = $date[1].' '.str_replace("'", "", $date[0]).' 2017 '.$exI[1];

//                        echo $datetime."\n";

                $timestamp = strtotime($datetime);

                $auctionTime[] = array('datetime' => $datetime, 'timestamp' => $timestamp, 'lot' => trim($exI[2]));

//                        dd($exI);
            }
        }

        return $auctionTime;
    }

    private function getContentByURL($url)
    {

        echo "Getting content from: ".$url;
        echo "<br>\n";

        $cSession = curl_init();

        curl_setopt($cSession,CURLOPT_URL,$url);
        curl_setopt($cSession,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($cSession,CURLOPT_HEADER, false);

        $result=curl_exec($cSession);

        return $result;
    }


}
