<?php


namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ConfirmEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function build()
    {
        error_log("ConfirmEmail data:".var_export($this->data, true));
        $address = env("MAIL_FROM_ADDRESS",'covidscore@bdt.com');
        $name = env('MAIL_FROM_NAME','Dine Safely');
        $app_name = env('APP_NAME', 'Dine Safely');
        $subject = 'Complete your '. $app_name . ' submission: '.$this->data['name'];

        return $this->view('emails.confirm')
            ->text('emails.confirm_plain')
            ->from($address, $name)
            ->subject($subject)
            ->with($this->data);
    }
}
