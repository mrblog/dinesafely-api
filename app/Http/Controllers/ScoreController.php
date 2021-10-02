<?php

namespace App\Http\Controllers;

use App\Constants\ScoreConstants;
use App\Mail\ConfirmEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ScoreController extends Controller {

    public function getAllScores(Request $request) {

        //$results = DB::select("SELECT * FROM place_score");
        $results = app('db')->select("SELECT * FROM place_score");

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
        $email = $request->get(ScoreConstants::EMAIL);
        $token = openssl_random_pseudo_bytes(16);

        $token = bin2hex($token);

        app('db')->delete("DELETE FROM pending_score WHERE user_id = ? AND place_id = ?",
            [$request->get(ScoreConstants::EMAIL), $request->get(ScoreConstants::PLACE_ID)]
        );

        app('db')->insert("INSERT INTO pending_score (token,user_id,user_handle,place_id,staff_masks,customer_masks,outdoor_seating,vaccine,rating,is_affiliated,notes) VALUES (?,?,?,?,?,?,?,?,?,?,?)",
            [$token, $email,
                $request->get(ScoreConstants::HANDLE),
                $request->get(ScoreConstants::PLACE_ID),
                $request->get(ScoreConstants::STAFF_MASKS),
                $request->get(ScoreConstants::CUSTOMER_MASKS),
                $request->get(ScoreConstants::OUTDOOR_SEATING),
                $request->get(ScoreConstants::VACCINE),
                $request->get(ScoreConstants::RATING),
                $request->get(ScoreConstants::IS_AFFILIATED),
                $request->get(ScoreConstants::NOTES)
            ]
        );
        $results = new \stdClass();
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

        app('db')->insert("INSERT INTO place_score (user_id,user_handle,place_id,staff_masks,customer_masks,outdoor_seating,vaccine,rating,is_affiliated,notes,created_at) VALUES (?,?,?,?,?,?,?,?,?,?,?)",
            [
                $row->user_id,
                $row->user_handle,
                $row->place_id,
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

        $results = new \stdClass();
        return $this->generateSuccessResponse($results);
    }
}
