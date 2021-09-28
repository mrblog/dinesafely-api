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
            'place_id' => 'required',
            'name' => 'required',
            'staff_masks' => 'required',
            'customer_masks' => 'required',
            'vaccine' => 'required',
            'rating' => 'required',
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

        app('db')->insert("INSERT INTO pending_score (token,user_id,place_id,staff_masks,customer_masks,vaccine,rating) VALUES (?,?,?,?,?,?,?)",
            [$token, $email,
            $request->get("place_id"),
            $request->get("staff_masks"),
            $request->get("customer_masks"),
            $request->get("vaccine"),
            $request->get("rating")]
        );
        $results = new \stdClass();
        $recipient = $email;
        $data = [
            'site_base_url' => env("APP_URL", "http://localhost:8080"),
            'name' => $request->get("name"),
            'token' => $token
        ];
        if (env('APP_ENV', "local") != "production") {
            error_log("postScore email: ".$email." token: ". $token);
            $recipient = env("DEBUG_EMAIL");
            if (!empty($recipient)) {
                Mail::to($recipient)->send(new ConfirmEmail($data));
            }
        } else {
            Mail::to($recipient)->send(new ConfirmEmail($data));
        }
        return $this->generateSuccessResponse($results);
    }
}
