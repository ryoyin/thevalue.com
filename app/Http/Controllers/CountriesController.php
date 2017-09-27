<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App;


class CountriesController extends Controller
{
    // php artisan tinker
    // $controller = app()->make('App\Http\Controllers\CountriesController');
    // app()->call([$controller, 'import']);
    public function import()
    {
        // name,alpha-2,alpha-3,country-code,iso_3166-2,region,sub-region,region-code,sub-region-code

        $countries = json_decode(Storage::disk('local')->get('countries/countries.json'), true);

//        dd($countries);

        foreach($countries as $country) {
            $newCountry = New App\Model\Country;

            $newCountry->name = $country['name'];
            $newCountry->alpha_2 = $country['alpha-2'];
            $newCountry->alpha_3 = $country['alpha-3'];
            $newCountry->country_code = $country['country-code'];
            $newCountry->iso_3166_2 = $country['iso_3166-2'];
            $newCountry->region = $country['region'];
            $newCountry->sub_region = $country['sub-region'];
            $newCountry->region_code = $country['region-code'];
            $newCountry->sub_region_code = $country['sub-region-code'];

            $newCountry->save();
        }
    }

    // php artisan tinker
    // $controller = app()->make('App\Http\Controllers\CountriesController');
    // app()->call([$controller, 'importAuctionLocationForChristie']);
    public function importAuctionLocationForChristie()
    {
        $location = array(
            'Amsterdam' => 'Netherlands',
            'Beaune' => 'France',
            'Belgium' => 'Belgium',
            'Dubai' => 'United Arab Emirates',
            'Geneva' => 'Switzerland',
            'Germany' => 'Germany',
            'Glasgow' => 'United Kingdom',
            'Hong Kong' => 'China',
            'London' => 'United Kingdom',
            'London, South Kensington' => 'United Kingdom',
            'Los Angeles' => 'United States',
            'Madrid' => 'Spain',
            'Mallorca' => 'Spain',
            'Melbourne' => 'Australia',
            'Milan' => 'Italy',
            'Monaco' => 'Monaco',
            'Mumbai' => 'India',
            'New York' => 'United States',
            'Paris' => 'France',
            'Rome' => 'Italy',
            'Shanghai' => 'China',
            'Singapore' => 'Singapore',
            'Sydney' => 'Australia',
            'Taipei' => 'Taiwan',
            'Tel Aviv' => 'Israel',
            'Zurich' => 'Switzerland'
        );

        foreach($location as $locationName => $countryName) {

            echo "Import location: ".$locationName." Country: ".$countryName."\n";

            $this->importAuctionLocation($countryName, $locationName);
        }

    }

    public function importAuctionLocation($countryName, $locationName)
    {

        $countryName = trim($countryName);
        $locationName = trim($locationName);

        $country = $this->getCountryByCountryName($countryName);

        // dd($country);

        $location = New App\Model\AuctionLocation;
        $location->name = $locationName;
        $location->country_id = $country->id;
        $location->save();

    }

    public function getCountryByCountryName($countryName)
    {
        return App\Model\Country::where('name', $countryName)->first();
    }
}
