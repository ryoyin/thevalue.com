<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App;

class AuctionSeriesController extends Controller
{
    public function index()
    {
        $series = App\AuctionSeries::all();

        $data = array(
            'menu' => array('auction', 'auction.series.list'),
            'series' => $series
        );

        return view('backend.auctions.series.index', $data);

    }

    // slug start_date end_date auction_house_id status name country location

    public function create()
    {
        $houses = App\AuctionHouse::all();

        $data = array(
            'menu' => array('auction', 'auction.series.list'),
            'title' => 'Create',
            'action' => url('tvadmin/auction/series'),
            'houses' => $houses
        );

        return view('backend.auctions.series.form', $data);
    }

    public function store(Request $request)
    {
        // slug start_date end_date auction_house_id status name country location
        $series = new App\AuctionSeries;
        $series->slug = $request->slug;
        $series->total_lots = 0;
        $series->start_date = $request->start_date;
        $series->end_date = $request->end_date;
        $series->auction_house_id = $request->auction_house_id;
        $series->status = $request->status;
        $series->save();

        $seriesID = $series->id;

        $langs = config('app.supported_languages');
        foreach($langs as $lang) {
            $name = $lang.'-name';
            $country = $lang.'-country';
            $location = $lang.'-location';

            $seriesDetail = New App\AuctionSeriesDetail;
            $seriesDetail->name = $request->$name;
            $seriesDetail->country = $request->$country;
            $seriesDetail->location = $request->$location;
            $seriesDetail->lang = $lang;
            $seriesDetail->auction_series_id = $seriesID;

            $seriesDetail->save();
        }

        $series->save();

        return redirect('tvadmin/auction/series')->with('alert-success', 'Series was successful added!');;
    }

    public function destroy($id)
    {
        $series = App\AuctionSeries::findOrFail($id);
        $seriesDetail = $series->getDetailByLang('trad');
        $name = $seriesDetail->name;
        $series->delete();

        $seriesDetails = $series->details;
        foreach($seriesDetails as $seriesDetail) {
            $seriesDetail->delete();
        }

        return redirect('tvadmin/auction/series')->with('alert-warning', '"<b>'.$name.'<b>" have been removed');
    }
}
