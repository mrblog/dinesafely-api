<?php


namespace App\Http\Controllers;


use App\Constants\ScoreConstants;
use App\Constants\PlaceParamsConstants;
use App\GooglePlacesApi\GooglePlacesApi;
use Illuminate\Http\Request;

class PlacesController extends Controller
{

    protected $placesService;
    public function __construct(GooglePlacesApi $placesService){
        $this->placesService = $placesService;
    }


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
                    ScoreConstants::STAFF_MASKS  =>  1.0 * $staff_masks / $count,
                    ScoreConstants::CUSTOMER_MASKS  =>  1.0 * $customer_masks / $count,
                    ScoreConstants::OUTDOOR_SEATING  =>  1.0 * $outdoor_seating / $count,
                    ScoreConstants::VACCINE  =>  1.0 * $vaccine / $count,
                    ScoreConstants::RATING  =>  1.0 * $rating / $count,
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
                } else if (property_exists($rawResults, "result")) {
                    $results = $this->scoredResults([$rawResults->result]);
                    return $this->generateSuccessResponse($results[0]);
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

        $location = $request->get(PlaceParamsConstants::LOCATION);
        $radius = 5000;
        $type = 'restaurant';
        // $rankby = 'distance'; //this didn't work very well
        $rawResults = $this->placesService->nearbySearch($location, $radius, $type);
        return $this->handlePlacesResponse($rawResults);
    }

    public function findPlaces(Request $request) {
        $input = $request->get(PlaceParamsConstants::INPUT);
        if (empty($input)) {
            return $this->generateErrorResponse("input parameter required", 400);
        }
        $location = $request->get(PlaceParamsConstants::LOCATION);
        $radius = 10000;
        $type = 'restaurant';
        $rawResults = $this->placesService->textSearch($input, $location, $radius, $type);
        return $this->handlePlacesResponse($rawResults);
    }

    public function placeDetails(Request $request, $place_id) {
        $rawResults = $this->placesService->placeDetails($place_id, 'place_id,formatted_address,name,icon,type');
        return $this->handlePlacesResponse($rawResults);
    }
}

