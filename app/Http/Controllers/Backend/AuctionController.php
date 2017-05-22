<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class AuctionController extends Controller
{
    public function itemList($saleID = null) {

        $locale = App::getLocale();

        $sale = null;
        $saleDetail = null;
        $items = array();

        $sales = App\AuctionSale::all();

        if($saleID != null) {
            $sale = App\AuctionSale::where('id', $saleID)->first();
            $saleDetail = $sale->details()->where('lang', $locale)->first();
            $items = $sale->items;
        }

        $articles = array();
        $data = array(
            'locale' => $locale,
            'menu' => array('auction', 'item.list'),
            'articles' => $articles,
            'sales' => $sales,
            'saleInfo' => array('sale' => $sale, 'saleDetail' => $saleDetail),
            'items' => $items
        );

        return view('backend.auctions.items.index', $data);
    }

    public function itemEdit($itemID) {

        $locale = App::getLocale();

        $langs = config('app.supported_languages');

        $item = App\AuctionItem::where('id', $itemID)->first();
        $sale = $item->sale;

        $detailFields = array(
            'title' => 'Title',
            'maker' => 'Maker',
            'description' => 'Description',
            'misc' => 'Misc',
            'provenance' => 'Provenance',
            'post_lot_text' => 'Post Lot Text',
        );

        if(old('slug') === NULL) {
            $itemInfo = array(
                'id' => $item->id,
                'slug' => $item->slug,
                'dimension' => $item->dimension,
                'image_medium_path' => $item->image_medium_path,
            );

            foreach($langs as $lang) {
                $itemDetail = $item->details->where('lang', $lang)->first();

                foreach($detailFields as $key => $field) {

                    $itemInfo[$key.'-'.$lang] = $itemDetail->$key;

                }

            }
        } else {
            $itemInfo = array(
                'slug' => old('slug'),
                'dimension' => old('dimension'),
            );
        }

        $data = array(
            'sale' => $sale,
            'locale' => $locale,
            'menu' => array('auction', 'item.list'),
            'item' => $itemInfo,
            'langs' => $langs
        );

        return view('backend.auctions.items.form', $data);
    }

    public function itemUpdate($itemID) {

    }
}
