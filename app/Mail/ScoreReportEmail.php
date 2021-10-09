<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;

class ScoreReportEmail extends \Illuminate\Mail\Mailable
{
    use Queueable, SerializesModels;

    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function build()
    {
        error_log("ScoreReportEmail data:".var_export($this->data, true));
        $address = env("MAIL_FROM_ADDRESS",'covidscore@bdt.com');
        $name = env('MAIL_FROM_NAME','Dine Safely');
        $subject = 'New score '. $this->data['action'] . ' by '.$this->data['email'];

        return $this->view('emails.score_report')
            ->text('emails.score_report_plain')
            ->from($address, $name)
            ->subject($subject)
            ->with($this->data);
    }
}
