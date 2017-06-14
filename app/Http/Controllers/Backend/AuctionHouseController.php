<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App;

class AuctionHouseController extends Controller
{
    public function index()
    {
        $houses = App\AuctionHouse::all();

        $data = array(
            'menu' => array('auction', 'auction.house.index'),
            'houses' => $houses,
        );

        return view('backend.auctions.house.index', $data);
    }

    public function create()
    {
        $data = array(
            'menu' => array('auction', 'auction.house.index'),
            'title' => 'Create',
            'action' => url('tvadmin/auction/house'),
            'house' => array(),
        );

        return view('backend.auctions.house.form', $data);
    }

    public function edit($id)
    {
        $house = App\AuctionHouse::find($id);
        $houseDetails = $house->details;

        foreach($houseDetails as $houseDetail) {
            $house[$houseDetail->lang.'-name'] = $houseDetail->name;
            $house[$houseDetail->lang.'-address'] = $houseDetail->address;
            $house[$houseDetail->lang.'-office_hour'] = $houseDetail->office_hour;
        }

        $data = array(
            'menu' => array('auction', 'auction.house.index'),
            'title' => 'Modify',
            'formMethod' => 'PUT',
            'action' => 'tvadmin/auction/house/'.$id,
            'house' => $house
        );

        return view('backend.auctions.house.form', $data);
    }

    public function update(Request $request, $id)
    {
        // "en-name" , "en-address" , "en-office_hour" , "trad-name" , "trad-address" , "trad-office_hour" , "sim-name" , "sim-address" , "sim-office_hour" , "slug" , "tel_no" , "fax_no" , "email" , "status"
        $house = App\AuctionHouse::find($id);

        $house->slug = $request->slug;
        $house->tel_no = $request->tel_no;
        $house->fax_no = $request->fax_no;
        $house->email = $request->email;
        $house->status = $request->status;

        $house->save();
    }

    public function store(Request $request)
    {

        $this->validate($request, [
            'aws_sns_topic_id' => 'required|integer',
            'message' => 'required|max:256',
        ]);

    }
}
