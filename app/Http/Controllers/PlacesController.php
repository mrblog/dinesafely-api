<?php


namespace App\Http\Controllers;


use Illuminate\Http\Request;

class PlacesController extends Controller
{

    private function scoredResults($placesResults) {
        if (count($placesResults) == 0) {
            return $placesResults;
        }
        $results = array();
        $placeIds = array();
        foreach ($placesResults as $place) {
            $placeIds[] = "'".$place->place_id."'";
        }
        //error_log("scoredResults placeIds: ".var_export($placeIds, true));

        /*$scores = app('db')->select("SELECT place_id,AVG(staff_masks) AS staff_masks,
                   AVG(customer_masks) AS customer_masks,
                   AVG(vaccine) AS vaccine,
                   AVG(rating) FROM place_score WHERE place_id=? AND published=1");*/
        $query = "SELECT place_id,staff_masks,customer_masks,outdoor_seating,vaccine,rating,created_at FROM place_score WHERE place_id IN (".implode(",",$placeIds).")";
        //error_log("scoredResults query: ".$query);
        $scores = app('db')->select($query);
        foreach ($placesResults as $place) {
            $staff_masks = 0.;
            $outdoor_seating = 0.;
            $customer_masks = 0.;
            $vaccine = 0.;
            $rating = 0.;
            $count = 0;
            $most_recent = 0;
            foreach ($scores as $score) {
                if ($score->place_id == $place->place_id) {
                    $staff_masks += $score->staff_masks;
                    $customer_masks += $score->customer_masks;
                    $outdoor_seating += $score->outdoor_seating;
                    $vaccine += $score->vaccine;
                    $rating += $score->rating;
                    $rating_time = strtotime($score->created_at);
                    if ($rating_time > $most_recent) {
                        $most_recent = $rating_time;
                    }
                    $count++;
                }
            }
            $outPlace = $place;
            if ($count > 0) {
                $outPlace->scores = [
                    ScoreConstants::STAFF_MASKS  =>  $staff_masks,
                    ScoreConstants::CUSTOMER_MASKS  =>  $customer_masks,
                    ScoreConstants::OUTDOOR_SEATING  =>  $outdoor_seating,
                    ScoreConstants::VACCINE  =>  $vaccine,
                    ScoreConstants::RATING  =>  $rating,
                    ScoreConstants::MOST_RECENT  =>  date('M d, Y', $most_recent),
                    ScoreConstants::COUNT  =>  $count
                ];
            }
            $results[] = $outPlace;
        }
        return $results;
    }

    private function handlePlacesResponse($rawResults) {
        if (property_exists($rawResults, "status")) {
            if ($rawResults->status == "OK" || $rawResults->status == "ZERO_RESULTS") {
                if (property_exists($rawResults, "results")) {
                    $placesResults = $rawResults->results;
                } else if (property_exists($rawResults, "candidates")) {
                    $placesResults = $rawResults->candidates;
                } else {
                    error_log("handlePlacesResponse rawResults:".var_export($rawResults, true));
                    return $this->generateErrorResponse("No candidates or results", 500);
                }
                $results = $this->scoredResults($placesResults);
                return $this->generateSuccessResponse($results);
            } else {
                error_log("handlePlacesResponse rawResults:".var_export($rawResults, true));
                $msg = $rawResults->status;
                if (property_exists($rawResults,"error_message")) {
                    $msg = $rawResults->status.": ".$rawResults->error_message;
                }
                return $this->generateErrorResponse($msg, 500);
            }
        } else {
            error_log("handlePlacesResponse rawResults:".var_export($rawResults, true));
            return $this->generateErrorResponse("Unexpected places response", 500);
        }
    }

    public function getNearBy(Request $request) {

        /*$latitude = 37.821593;
        $longitude = -121.999961;*/

        $location = $request->get(PlaceParams::LOCATION);
        $radius = 5000;
        $type = 'restaurant';
        // $rankby = 'distance'; //this didn't work very well
        $key = env("GOOGLE_API_KEY");
        $url = "https://maps.googleapis.com/maps/api/place/nearbysearch/json?location=" . urlencode($location) .
            "&radius=". $radius . "&type=" . $type . "&key=" . $key;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);
        curl_close($ch);

        $rawResults = json_decode($response);
        return $this->handlePlacesResponse($rawResults);
    }

    public function findPlaces(Request $request) {
        $input = $request->get(PlaceParams::INPUT);
        if (empty($input)) {
            return $this->generateErrorResponse("input parameter required", 400);
        }
        $location = $request->get(PlaceParams::LOCATION);
        $radius = 10000;
        $type = 'restaurant';
        $key = env("GOOGLE_API_KEY");
        $url = "https://maps.googleapis.com/maps/api/place/textsearch/json?query=".urlencode($input)."&location=" . urlencode($location) . "&radius=" . $radius ."&type=".$type."&key=".$key;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);
        curl_close($ch);

        $rawResults = json_decode($response);
        return $this->handlePlacesResponse($rawResults);
    }

}

