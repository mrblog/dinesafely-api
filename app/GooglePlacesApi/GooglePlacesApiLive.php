<?php

namespace App\GooglePlacesApi;

use Cache;

class GooglePlacesApiLive extends GooglePlacesApi
{
    const BASE_PLACES_API_URL = 'https://maps.googleapis.com/maps/api/place';
    const CACHE_EXPIRY = 1440; // minutes

    public function __construct($key) {
        $this->apiKey = $key;
    }

    private function executeApi($params, $endpoint) {
        $cache_key = __CLASS__ . "::" . $endpoint . "::" . $params;
        if (Cache::has($cache_key)) {
            error_log("cache hit: " . $cache_key);
            $value = Cache::get($cache_key);
            return unserialize($value);
        }
        $url = self::BASE_PLACES_API_URL . "/". $endpoint. "/json?" . $params . "&key=" . $this->apiKey;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);
        curl_close($ch);
        $rawResults = json_decode($response);
        if ($rawResults->status == "OK" || $rawResults->status == "ZERO_RESULTS") {
            Cache::put($cache_key, serialize($rawResults), self::CACHE_EXPIRY);
        }
        if (env("APP_ENV") === "testing") {
            $google_api_results_php = "tests/testdata/google_api_results.php";
            $google_api_results = [];
            if (file_exists($google_api_results_php)) {
                include $google_api_results_php;
            }
            $testing_key =  $endpoint . "::" . $params;
            if (!key_exists($testing_key, $google_api_results)) {
                $google_api_results[$testing_key] = $rawResults;
            }
            file_put_contents(
                $google_api_results_php,
                "<?php\n\$google_api_results = ".var_export($google_api_results, true).";\n"
            );
        }
        return $rawResults;
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
