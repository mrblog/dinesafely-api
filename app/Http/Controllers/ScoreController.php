<?php

namespace App\Http\Controllers;

use App\Constants\ScoreConstants;
use App\GooglePlacesApi\GooglePlacesApi;
use App\Mail\ConfirmEmail;
use App\Mail\ScoreReportEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ScoreController extends Controller {

    protected $placesService;
    public function __construct(GooglePlacesApi $placesService){
        $this->placesService = $placesService;
    }

    protected function placeDetails($place_id) {
        $rawResults = $this->placesService->placeDetails($place_id, 'place_id,formatted_address,name,geometry,icon,type');

        $place_details = new \stdClass();
        $place_details->name = '?';
        $place_details->formatted_address = '?';
        if (property_exists($rawResults, "status")
            && $rawResults->status == "OK"
            && property_exists($rawResults, "result")) {
            $place_details = $rawResults->result;
        }
        return $place_details;
    }

    public function getAllScores(Request $request) {

        //$results = DB::select("SELECT * FROM place_score");
        $results = app('db')->select("SELECT * FROM place_score");

        //error_log("getAllCourses params: ".var_export($request->request->all(), TRUE));
        //error_log("getAllCourses version: ".$request->get('version'));
        return $this->generateSuccessResponse($results);
    }

    public function getAllPendingScores(Request $request) {

        //$results = DB::select("SELECT * FROM place_score");
        $results = app('db')->select("SELECT user_id,user_handle,place_id,name,rating,notes FROM pending_score");

        //error_log("getAllCourses params: ".var_export($request->request->all(), TRUE));
        //error_log("getAllCourses version: ".$request->get('version'));
        return $this->generateSuccessResponse($results);
    }

    public function postScore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            ScoreConstants::EMAIL => 'required',
            ScoreConstants::HANDLE => 'required',
            ScoreConstants::PLACE_ID => 'required',
            ScoreConstants::NAME => 'required',
            ScoreConstants::STAFF_MASKS => 'required',
            ScoreConstants::CUSTOMER_MASKS => 'required',
            ScoreConstants::OUTDOOR_SEATING => 'required',
            ScoreConstants::VACCINE => 'required',
            ScoreConstants::RATING => 'required',
            ScoreConstants::IS_AFFILIATED => 'required'
        ]);
        if ($validator->fails()) {
            return $this->generateErrorResponse($validator->errors()->all()[0]);
        }
        $email = strtolower($request->get(ScoreConstants::EMAIL));
        $token = openssl_random_pseudo_bytes(16);

        $token = bin2hex($token);

        $placeDetails = $this->placeDetails($request->get(ScoreConstants::PLACE_ID));
        app('db')->delete("DELETE FROM pending_score WHERE user_id = ? AND place_id = ?",
            [$email, $request->get(ScoreConstants::PLACE_ID)]
        );

        app('db')->insert("INSERT INTO pending_score (token,user_id,user_handle,place_id,name,lat,lng,staff_masks,customer_masks,outdoor_seating,vaccine,rating,is_affiliated,notes) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)",
            [$token, $email,
                $request->get(ScoreConstants::HANDLE),
                $request->get(ScoreConstants::PLACE_ID),
                $placeDetails->name,
                $placeDetails->geometry->location->lat,
                $placeDetails->geometry->location->lng,
                $request->get(ScoreConstants::STAFF_MASKS),
                $request->get(ScoreConstants::CUSTOMER_MASKS),
                $request->get(ScoreConstants::OUTDOOR_SEATING),
                $request->get(ScoreConstants::VACCINE),
                $request->get(ScoreConstants::RATING),
                $request->get(ScoreConstants::IS_AFFILIATED),
                $request->get(ScoreConstants::NOTES)
            ]
        );
        $recipient = $email;
        $data = [
            'site_base_url' => env("APP_URL", "http://localhost:3000"),
            'name' => $request->get(ScoreConstants::NAME),
            'token' => $token
        ];
        if (env('APP_ENV', "local") != "production") {
            error_log("postScore email: ".$email." token: ". $token);
            $recipient = env("DEBUG_EMAIL");
            error_log("DEBUG_EMAIL: ".$recipient);
        }
        if (!empty($recipient)) {
            error_log("sending confirmation to: ".$recipient);
            Mail::to($recipient)->send(new ConfirmEmail($data));
        }
        $admin_email = env("ADMIN_EMAIL");
        if (!empty($admin_email)) {
            $place_details = $this->placeDetails($request->get(ScoreConstants::PLACE_ID));
            $report_data = [
                'action' => 'pending',
                'email' => $email,
                'name' => $request->get(ScoreConstants::HANDLE),
                'place_id' => $request->get(ScoreConstants::PLACE_ID),
                'name' => $request->get(ScoreConstants::NAME),
                'formatted_address' => $place_details->formatted_address,
                'rating' => $request->get(ScoreConstants::RATING),
                'notes' => $request->get(ScoreConstants::NOTES)
            ];
            try {
                Mail::to($admin_email)->send(new ScoreReportEmail($report_data));
            }
            catch (Exception $e) {
                error_log("Mail Exception: ".$e->getMessage());
            }
        }
        $results = new \stdClass();
        return $this->generateSuccessResponse($results);
    }

    public function confirmScore(Request $request, $token)
    {

        $results = app('db')->select("SELECT * FROM pending_score WHERE token = ?", [$token]);
        if (count($results) == 0) {
            return $this->generateErrorResponse("Invalid token");
        }

        $row = $results[0];

        app('db')->delete("DELETE FROM place_score WHERE user_id = ? AND place_id = ?",
            [$row->user_id, $row->place_id]
        );

        app('db')->insert("INSERT INTO place_score (user_id,user_handle,place_id,name,lat,lng,staff_masks,customer_masks,outdoor_seating,vaccine,rating,is_affiliated,notes,created_at) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)",
            [
                $row->user_id,
                $row->user_handle,
                $row->place_id,
                $row->name,
                $row->lat,
                $row->lng,
                $row->staff_masks,
                $row->customer_masks,
                $row->outdoor_seating,
                $row->vaccine,
                $row->rating,
                $row->is_affiliated,
                $row->notes,
                $row->created_at,
            ]
        );
        app('db')->delete("DELETE FROM pending_score WHERE token = ?", [$token]);

        app('db')->delete("DELETE FROM pending_score WHERE user_id = ? AND place_id = ?", [$row->user_id, $row->place_id]);

        $admin_email = env("ADMIN_EMAIL");
        if (!empty($admin_email)) {
            $place_details = $this->placeDetails($row->place_id);
            $report_data = [
                'action' => 'posted',
                'email' => $row->user_id,
                'name' => $row->user_handle,
                'place_id' => $row->place_id,
                'name' => $place_details->name,
                'formatted_address' => $place_details->formatted_address,
                'rating' => $row->rating,
                'notes' => $row->notes
            ];
            try {
                Mail::to($admin_email)->send(new ScoreReportEmail($report_data));
            }
            catch (Exception $e) {
                error_log("Mail Exception: ".$e->getMessage());
            }
        }
        $results = new \stdClass();
        return $this->generateSuccessResponse($results);
    }

    public function addLocation(Request $request, $secret) {
        if ($secret != 'sheeple') {
            return $this->generateErrorResponse("Invalid secret");
        }
        foreach (['pending_score', 'place_score'] as $table) {
            $results = app('db')->select("SELECT place_id,name,lat,lng FROM ".$table." WHERE lat=0.0 OR lng=0.0");
            foreach ($results as $row) {
                $rawResults = $this->placesService->placeDetails($row->place_id, 'place_id,name,geometry');

                /*
                 *  "geometry": {
            "location": {
              "lat": 37.822015,
              "lng": -122.000692
            },
                 */
                if (property_exists($rawResults, "status")
                    && $rawResults->status == "OK") {
                    $place_details = $rawResults->result;
                    app('db')->update("UPDATE ".$table." SET name = ?, lat = ?, lng = ? WHERE place_id=?", [
                        $place_details->name,
                        $place_details->geometry->location->lat,
                        $place_details->geometry->location->lng,
                        $row->place_id
                    ]);
                } else {
                    return $this->generateErrorResponse("unable to update place_id: " . $row->place_id." in ".$table);
            }

            }
        }
        $results = new \stdClass();
        return $this->generateSuccessResponse($results);
    }
}
