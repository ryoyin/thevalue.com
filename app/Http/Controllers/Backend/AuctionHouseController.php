<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

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

    public function store(Request $request)
    {
        //validate user file
        $uploaded_file = NULL;
        $isValidFile = FALSE;

//        dd($_FILES);

        if ($request->hasFile('uploaded_file')) {

            $uploaded_file = $request->file('uploaded_file');
            $mimeType = $uploaded_file->getMimeType();
            $validFileType = array('png', 'pdf', 'jpeg');

            foreach($validFileType AS $fileType) {

                if(strpos($mimeType, $fileType)) {
                    $isValidFile = TRUE;
                }

            }

            if($isValidFile) {

                $alternative_path = 'images/company_logo/';
                $destination_path = public_path().'/'.$alternative_path;
                $filename = $uploaded_file->getClientOriginalName();
                $fileExtension = $uploaded_file->getClientOriginalExtension();

                $uploaded_file->move($destination_path, $filename);

            } else {

                return redirect('tvadmin/photos/create')->with('fileerrors', 'File upload failed!');

            }

        } else {
            return redirect('tvadmin/photos/create')->with('fileerrors', 'File upload failed! No file found!');
        }

        $this->validate($request, [
            'slug' => 'required|string',
            'en-name' => 'required|string',
            'trad-name' => 'required|string',
            'sim-name' => 'required|string',
        ]);

        $house = New App\AuctionHouse;

        $house->slug = $request->slug;
        $house->image_path = $alternative_path.$filename;;
        $house->tel_no = $request->tel_no;
        $house->fax_no = $request->fax_no;
        $house->email = $request->email;
        $house->status = $request->status;

        $house->save();

        $houseID = $house->id;

        $langs = config('app.supported_languages');

        foreach($langs as $lang) {

            $houseDetail = New App\AuctionHouseDetail;

            $name = $lang.'-name';
            $address = $lang.'-address';
            $office_hour = $lang.'-office_hour';

            $houseDetail->name = $request->$name;
            $houseDetail->address = $request->$address;
            $houseDetail->office_hour = $request->$office_hour;
            $houseDetail->auction_house_id = $houseID;
            $houseDetail->lang = $lang;

            $houseDetail->save();

        }

        return redirect('tvadmin/auction/house');

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

        if ($request->hasFile('uploaded_file')) {

            $uploaded_file = $request->file('uploaded_file');
            $mimeType = $uploaded_file->getMimeType();
            $validFileType = array('png', 'pdf', 'jpeg');

            foreach($validFileType AS $fileType) {

                if(strpos($mimeType, $fileType)) {
                    $isValidFile = TRUE;
                }

            }

            if($isValidFile) {

                $alternative_path = 'images/company_logo/';
                $destination_path = public_path().'/'.$alternative_path;
                $filename = $uploaded_file->getClientOriginalName();
                $fileExtension = $uploaded_file->getClientOriginalExtension();

                $uploaded_file->move($destination_path, $filename);

            } else {

                return redirect('tvadmin/photos/create')->with('fileerrors', 'File upload failed!');

            }

        }

        $house->slug = $request->slug;
        $house->image_path = $alternative_path.$filename;
        $house->tel_no = $request->tel_no;
        $house->fax_no = $request->fax_no;
        $house->email = $request->email;
        $house->status = $request->status;

        $houseDetails = $house->details;

        foreach($houseDetails as $houseDetail) {
            $lang = $houseDetail->lang;

            $name = $lang.'-name';
            $address = $lang.'-address';
            $office_hour = $lang.'-office_hour';

            $houseDetail->name = $request->$name;
            $houseDetail->address = $request->$address;
            $houseDetail->office_hour = $request->$office_hour;

            $houseDetail->save();
        }

        $house->save();

        return redirect('tvadmin/auction/house');
    }

    public function destroy($id)
    {
        $house = App\AuctionHouse::findOrFail($id);

        File::delete(asset($house->image_path));

        $house->delete();

        $houseDetails = $house->details;
        foreach($houseDetails as $houseDetail) {
            if($houseDetail->lang == 'trad') {
                $name = $houseDetail->name;
            }

            $houseDetail->delete();
        }

        return redirect('tvadmin/auction/house')->with('alert-warning', '"<b>'.$name.'<b>" have been removed');
    }

}
