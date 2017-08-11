<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class AuctionController extends Controller
{

    public function saleList(Request $request)
    {
        $locale = App::getLocale();

        $slug = trim($request->slug);

        if($slug != '') {
            $sales = App\AuctionSale::where('slug', 'like', '%'.$slug.'%')->orderBy('start_date', 'desc')->paginate(50);
        } else {
            $sales = App\AuctionSale::orderBy('start_date', 'desc')->paginate(50);
        }

        $data = array(
            'locale' => $locale,
            'menu' => array('auction', 'sale.list'),
            'sales' => $sales,
        );

        return view('backend.auctions.sale.index', $data);

    }

    public function itemList($saleID) {

        set_time_limit(60000);

        $locale = App::getLocale();

        $sale = null;
        $saleDetail = null;
        $items = array();

        if($saleID != null) {
            $sale = App\AuctionSale::where('id', $saleID)->first();
//            exit;
            $saleDetail = $sale->details()->where('lang', $locale)->first();
            $items = $sale->items()->orderBy('sorting')->paginate(20);
        }

        $articles = array();
        $data = array(
            'locale' => $locale,
            'menu' => array('auction', 'sale.list'),
            'articles' => $articles,
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
                'sorting' => $item->sorting,
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
                'sorting' => old('sorting'),
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

    public function itemUpdate(Request $request, $itemID) {
        $langs = config('app.supported_languages');

        $detailFields = array(
            'title' => 'Title',
            'maker' => 'Maker',
            'description' => 'Description',
            'misc' => 'Misc',
            'provenance' => 'Provenance',
            'post_lot_text' => 'Post Lot Text'
        );


//            category_id, slug, photo_id, hit_counter, share_counter
        $item = App\AuctionItem::find($itemID);
        $item->slug = $request->slug;
        $item->dimension = $request->dimension;
        $item->sorting = $request->sorting;
        $item->save();


        foreach($langs as $key => $lang) {
            $detail = App\AuctionItemDetail::where('auction_item_id', $item->id)->where('lang', $lang)->first();
//            dd($detail);
            foreach($detailFields as $dkey => $field) {
                $carrier = $dkey.'-'.$key;
                $detail->$dkey = $request->$carrier;
            }
            $detail->save();
        }

        return redirect('tvadmin/auction/item/'.$itemID)->with('alert-success', 'Auction item was successful updated!');
    }

    public function pushS3Index()
    {
        $locale = App::getLocale();

        $data = array(
            'locale' => $locale,
            'menu' => array('auction', 'pushS3.list'),
        );

        return view('backend.auctions.sale.pushS3', $data);
    }

    public function pushS3Process()
    {
        $sales = App\AuctionSale::all();

        $path = base_path().'/public/';

        foreach($sales as $sale) {
            if(!$sale->image_pushS3) {
                $this->pushS3($path, $sale->image_path);
                $sale->image_pushS3 = true;
                $sale->save();
            }
        }

        $houses = App\AuctionHouse::all();
        foreach($houses as $house) {
            if(!$house->image_pushS3) {
                $this->pushS3($path, $house->image_path);
                $house->image_pushS3 = true;
                $house->save();
            }
        }

        return redirect()->route('backend.auction.sale.pushS3');
    }

    public function pushS3($baseDirectory, $filePath)
    {
        $s3 = \Storage::disk('s3');
        $localPath = $baseDirectory.'/'.$filePath;

        echo $localPath;

        $image = fopen($localPath, 'r+');
        $s3->put('/'.$filePath, $image, 'public');
    }
}
