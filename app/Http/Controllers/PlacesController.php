<?php


namespace App\Http\Controllers;


use Illuminate\Http\Request;

/*\GooglePlace\Request::$api_key = 'AIzaSyCvRcIZTx6CAvTARdDabnnvsTRrqjFne54';*/

class PlacesController extends Controller
{
    public function getNearBy(Request $request) {

        $latitude = 37.821593;
        $longitude = -121.999961;
        $radius = 2500;
        $type = 'restaurant';

        $url = "https://maps.googleapis.com/maps/api/place/nearbysearch/json?location=".$latitude.",".$longitude."&radius=".$radius."&type=".$type."&key=AIzaSyCvRcIZTx6CAvTARdDabnnvsTRrqjFne54";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);
        $rawResults = json_decode($response);
        $results = $rawResults->results;
        curl_close($ch);

        /*error_log("getNearBy apiKey: ".\GooglePlace\Request::$api_key);
        $rankBy = new \GooglePlace\Services\Nearby([
                'location' => '37.821593,-121.999961',
                'radius' => '2500',
                'type' => 'restaurant'
            ]
        );
        $results = $rankBy->places();*/
        return $this->generateSuccessResponse($results);
    }
}
