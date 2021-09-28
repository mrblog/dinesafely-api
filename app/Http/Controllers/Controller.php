<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    //
    protected function generateSuccessResponse($r) {
        return response()->json(['success' => TRUE, 'data' => $r])->header('Content-Type', "application/json");
    }

    protected function generateErrorResponse($msg, $code=400) {
        return response()->json(['success' => FALSE, 'error' => $msg], $code)->header('Content-Type', "application/json");
    }

    protected function getIp(Request $request) {
        foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key){
            if ($request->hasHeader($key) === true){
                foreach (explode(',', $request->header($key)) as $ip){
                    $ip = trim($ip); // just to be safe
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false){
                        return $ip;
                    }
                }
            }
        }
    }
}
