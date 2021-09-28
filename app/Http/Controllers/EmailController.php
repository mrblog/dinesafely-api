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

        Mail::to('mrblogdotorg@gmail.com')->send(new TestEmail($data));

        return $this->generateSuccessResponse([]);
    }
}
