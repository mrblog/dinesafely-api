<?php


namespace App\Http\Controllers;


use Illuminate\Http\Request;

class CityController extends Controller
{

    public function getCities(Request $request) {
        $queryString = $request->get("q");
        if (empty($queryString)) {
            return $this->generateErrorResponse("query parameter required", 400);
        }
        $results = app('db')->select("SELECT full_city AS label,lat,lng FROM city WHERE city_lower LIKE ?", [$queryString."%"]);

        //error_log("getAllCourses params: ".var_export($request->request->all(), TRUE));
        //error_log("getAllCourses version: ".$request->get('version'));
        return $this->generateSuccessResponse($results);
    }
}
