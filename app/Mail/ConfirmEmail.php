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
        $address = 'covidscore@bdt.com';
        $subject = 'Complete your covid score submission: '.$this->data['name'];
        $name = 'Covid Score';

        return $this->view('emails.confirm')
            ->text('emails.confirm_plain')
            ->from($address, $name)
            ->subject($subject)
            ->with($this->data);
    }
}
