<?php

namespace App\Http\Controllers;

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
            'email' => 'required',
            'handle' => 'required',
            'place_id' => 'required',
            'name' => 'required',
            'staff_masks' => 'required',
            'customer_masks' => 'required',
            'outdoor_seating' => 'required',
            'vaccine' => 'required',
            'rating' => 'required',
            'is_affiliated' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->generateErrorResponse($validator->errors()->all()[0]);
        }
        $email = $request->get("email");
        $token = openssl_random_pseudo_bytes(16);

        $token = bin2hex($token);

        app('db')->delete("DELETE FROM pending_score WHERE user_id = ? AND place_id = ?",
            [$request->get("email"), $request->get("place_id")]
        );

        app('db')->insert("INSERT INTO pending_score (token,user_id,user_handle,place_id,staff_masks,customer_masks,outdoor_seating,vaccine,rating,is_affiliated,notes) VALUES (?,?,?,?,?,?,?,?,?,?,?)",
            [$token, $email, $request->get("handle"),
                $request->get("place_id"),
                $request->get("staff_masks"),
                $request->get("customer_masks"),
                $request->get("outdoor_seating"),
                $request->get("vaccine"),
                $request->get("rating"),
                $request->get("is_affiliated"),
                $request->get("notes")
            ]
        );
        $results = new \stdClass();
        $recipient = $email;
        $data = [
            'site_base_url' => env("APP_URL", "http://localhost:3000"),
            'name' => $request->get("name"),
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
