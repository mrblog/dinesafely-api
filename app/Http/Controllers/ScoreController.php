<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ScoreController extends Controller {

    public function getAllScores(Request $request) {

        //$results = DB::select("SELECT * FROM place_score");
        $results = app('db')->select("SELECT * FROM place_score");

        //error_log("getAllCourses params: ".var_export($request->request->all(), TRUE));
        //error_log("getAllCourses version: ".$request->get('version'));
        return $this->generateSuccessResponse($results);
    }
}
