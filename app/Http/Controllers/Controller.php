<?php

namespace App\Http\Controllers;

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
}
