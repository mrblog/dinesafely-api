<?php

namespace App\GooglePlacesApi;

class GooglePlacesApiMock extends GooglePlacesApi
{
    /**
     * @throws \Exception
     */
    private function executeApi($params, $endpoint) {
        $google_api_results_php = "tests/testdata/google_api_results.php";
        $google_api_results = [];
        if (file_exists($google_api_results_php)) {
            include $google_api_results_php;
        }
        $testing_key =  $endpoint . "::" . $params;
        if (key_exists($testing_key, $google_api_results)) {
            error_log("mocking: ".$testing_key);
            return $google_api_results[$testing_key];
        } else {
            error_log("no such key: ".$testing_key);
            throw new \Exception("no such key: ".$testing_key);
        }
    }

    public function nearbySearch($location, $radius, $type) {
        $params = "location=" . urlencode($location) . "&radius=". $radius . "&type=" . $type;
        return $this->executeApi($params, 'nearbysearch');
    }

    public function textSearch($input, $location, $radius, $type) {
        $params = "query=".urlencode($input)."&location=" . urlencode($location) . "&radius=" . $radius ."&type=".$type;
        return $this->executeApi($params, 'textsearch');
    }

    public function placeDetails($place_id, $fields) {
        $params = "place_id=" . $place_id . "&fields=". $fields;
        return $this->executeApi($params, 'details');
    }
}
