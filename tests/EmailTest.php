<?php

use App\Mail\ConfirmEmail;

class EmailTest extends TestCase
{

    public function testTemplates() {
        $data = [
            'site_base_url' => env("APP_URL", "http://localhost:3000"),
            'token' => 'b80cad52f47fbb571208556c377f18d3',
            'name' => 'My favorite restaurant'
        ];
        $mailable = new ConfirmEmail($data);

        $url = $data['site_base_url'] . "/confirm/" . $data['token'];
        $mailable->assertSeeInHtml($url);
        $mailable->assertSeeInText($url);
        $this->assertEquals('Complete your CovidScore submission: My favorite restaurant', $mailable->subject);
    }
}
