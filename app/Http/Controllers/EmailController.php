<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\TestEmail;

class EmailController extends Controller
{
    public function getTestEmail(Request $request)
    {
        $data = ['message' => 'This is a test!'];

        $result = Mail::to(env(DEBUG_EMAIL, 'mrblogdotorg@gmail.com'))->send(new TestEmail($data));

        return $this->generateSuccessResponse($result);
    }
}
