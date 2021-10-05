<?php

namespace App\GooglePlacesApi;

class GooglePlacesApi
{
    public function __construct($key) {
        $this->apiKey = $key;
    }

    public function nearbySearch($location, $radius, $type) {
        $url = "https://maps.googleapis.com/maps/api/place/nearbysearch/json?location=" . urlencode($location) .
            "&radius=". $radius . "&type=" . $type . "&key=" . $this->apiKey;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response);
    }

    public function textSearch($input, $location, $radius, $type) {
        $url = "https://maps.googleapis.com/maps/api/place/textsearch/json?query=".urlencode($input)."&location=" . urlencode($location) . "&radius=" . $radius ."&type=".$type."&key=".$this->apiKey;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response);
    }

    public function placeDetails($place_id, $fields) {
        $url = "https://maps.googleapis.com/maps/api/place/details/json?place_id=" . $place_id .
            "&fields=". $fields . "&key=" . $this->apiKey;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response);
    }
}
