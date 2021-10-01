<?php


namespace App\Http\Controllers;

use App\Mail\ConfirmEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\TestEmail;

class EmailController extends Controller
{
    public function getTestEmail(Request $request)
    {
        $data = ['message' => 'This is a test!'];

        $result = Mail::to(env('DEBUG_EMAIL', 'mrblogdotorg@gmail.com'))->send(new TestEmail($data));

        return $this->generateSuccessResponse($result);
    }

    public function sendTestConfirmationEmail(Request $request)
    {
        $data = [
            'site_base_url' => env("APP_URL", "http://localhost:3000"),
            'token' => 'b80cad52f47fbb571208556c377f18d3',
            'name' => 'My favorite restaurant'
        ];
        $result = Mail::to(env('DEBUG_EMAIL', 'mrblogdotorg@gmail.com'))->send(new ConfirmEmail($data));

        return $this->generateSuccessResponse($result);
    }
}
