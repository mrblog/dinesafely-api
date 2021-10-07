<?php

use App\Constants\ScoreConstants;

class ScoresTest extends TestCase
{

    const SCORE_POST_DATA = [
            ScoreConstants::EMAIL => 'theunderheardpress@gmail.com',
            ScoreConstants::HANDLE => 'Carl Johnson',
            ScoreConstants::PLACE_ID => 'ChIJOet126uMj4ARQL_qWEW12Jw',
            ScoreConstants::NAME => 'Bridges Restaurant',
            ScoreConstants::STAFF_MASKS => 1,
            ScoreConstants::CUSTOMER_MASKS => 1,
            ScoreConstants::OUTDOOR_SEATING => 1,
            ScoreConstants::VACCINE => 1,
            ScoreConstants::RATING => 3,
            ScoreConstants::IS_AFFILIATED => 0,
            ScoreConstants::NOTES => 'Outdoor and indoor seating. I felt safe.'
        ];

    protected function setUp() : void {
        parent::setUp();
        $path = 'db/testdata.sql';
        $sql = file_get_contents($path);
        DB::unprepared($sql);
    }

    public function testGetScores() {
        $testResult = $this->get('/v1/scores');

        //print("get_class: ".get_class($testResult));
        //print("get_class_methods: ".var_export(get_class_methods($testResult), true));

        $testResult->seeStatusCode(200);
        $testResult->seeJson([
            'success' => true
        ]);

        $expectedJson = '{"data":[{"created_at":"2021-09-15 17:40:00","customer_masks":1,"id":4,"is_affiliated":0,"lat":37.821117,"lng":-121.99794,"name":"Bridges","notes":"Bridges is ok","outdoor_seating":0,"place_id":"ChIJOet126uMj4ARQL_qWEW12Jw","published_at":"2021-09-30 19:52:49","rating":2,"staff_masks":1,"user_handle":"Joe Random","user_id":"joe@example.com","vaccine":0},{"created_at":"2021-09-18 14:50:00","customer_masks":0,"id":5,"is_affiliated":0,"lat":37.821129,"lng":-122.000305,"name":"The Peasant & The Pear","notes":"The Peasant & The Pear do an ok job","outdoor_seating":1,"place_id":"ChIJp2fEzamMj4ARliL8eEwHKYo","published_at":"2021-09-30 19:52:49","rating":2,"staff_masks":0,"user_handle":"Betty Borstein","user_id":"betty@example.com","vaccine":0},{"created_at":"2021-09-28 19:00:00","customer_masks":0,"id":6,"is_affiliated":0,"lat":37.822014,"lng":-122.000694,"name":"Revel Kitchen & Bar","notes":"Revel Kitchen & Bar too crowded","outdoor_seating":1,"place_id":"ChIJz2rJsKmMj4AR-gtLy4UsnH0","published_at":"2021-09-30 19:52:49","rating":1,"staff_masks":0,"user_handle":"Jerry Jackman","user_id":"jerry@example.com","vaccine":0},{"created_at":"2021-10-01 06:10:33","customer_masks":0,"id":7,"is_affiliated":0,"lat":37.813412,"lng":-121.996758,"name":"Luna Loca","notes":"","outdoor_seating":1,"place_id":"ChIJsf-R07OMj4ARY49JhQdBgww","published_at":"2021-10-01 06:24:10","rating":2,"staff_masks":1,"user_handle":"Drake","user_id":"ddr@drake.com","vaccine":0},{"created_at":"2021-10-01 19:46:52","customer_masks":0,"id":8,"is_affiliated":0,"lat":37.822906,"lng":-122.000671,"name":"Primo\'s Pizzeria & Pub","notes":"It\'s a little crowded but generally decent outdoor spacing","outdoor_seating":1,"place_id":"ChIJKfDOzqmMj4ARvBZlUVWRpGA","published_at":"2021-10-01 19:48:52","rating":2,"staff_masks":1,"user_handle":"David B","user_id":"david@bdt.com","vaccine":0}],"success":true}';

        $testResult->seeJsonEquals(json_decode($expectedJson, true, 512, JSON_OBJECT_AS_ARRAY));

    }

    public function testGetPendingScores() {
        $testResult = $this->get('/v1/scores/pending');

        //print("get_class: ".get_class($testResult));
        //print("get_class_methods: ".var_export(get_class_methods($testResult), true));

        $testResult->seeStatusCode(200);
        $testResult->seeJson([
            'success' => true
        ]);

        $expectedJson = '{"data":[{"notes":"","place_id":"ChIJhf0nNIDyj4ARJv44yv8mdqY","rating":1,"user_handle":"Sally Again","user_id":"sj@email.com"},{"notes":"","place_id":"ChIJsf-R07OMj4ARY49JhQdBgww","rating":-1,"user_handle":"Bobby","user_id":"bobby@email.com"},{"notes":"","place_id":"ChIJsf-R07OMj4ARY49JhQdBgww","rating":2,"user_handle":"Barry","user_id":"bbonds@email.com"},{"notes":"","place_id":"ChIJsf-R07OMj4ARY49JhQdBgww","rating":2,"user_handle":"Sue","user_id":"suzie@email.com"},{"notes":"Fined numerous times. Refused to close.","place_id":"ChIJPfToY6mMj4ARbbcGtQlQchE","rating":1,"user_handle":"Billy Jack","user_id":"bigbilly@emalservice.com"},{"notes":"It can get crowded","place_id":"ChIJuVid5KuMj4AR-Yl_7dIWs4M","rating":2,"user_handle":"Jane","user_id":"jjones@email.com"}],"success":true}';

        $testResult->seeJsonEquals(json_decode($expectedJson, true, 512, JSON_OBJECT_AS_ARRAY));

    }

    public function testPostScore() {

        $expectedJson = '{"data":{},"success":true}';

        $postResult = $this->json('POST', '/v1/place/score', self::SCORE_POST_DATA)
            ->seeJsonEquals(json_decode($expectedJson, true, 512, JSON_OBJECT_AS_ARRAY)
        );

        $results = app('db')->select("SELECT * FROM pending_score WHERE user_id = ? AND place_id = ?",
            [self::SCORE_POST_DATA[ScoreConstants::EMAIL], self::SCORE_POST_DATA[ScoreConstants::PLACE_ID]]
        );
        $this->assertEquals(1, count($results));
        $row = $results[0];

        $this->assertEquals(self::SCORE_POST_DATA[ScoreConstants::HANDLE], $row->user_handle);
        $this->assertEquals(self::SCORE_POST_DATA[ScoreConstants::PLACE_ID], $row->place_id);
        $this->assertEquals(self::SCORE_POST_DATA[ScoreConstants::STAFF_MASKS], $row->staff_masks);
        $this->assertEquals(self::SCORE_POST_DATA[ScoreConstants::CUSTOMER_MASKS], $row->customer_masks);
        $this->assertEquals(self::SCORE_POST_DATA[ScoreConstants::VACCINE], $row->vaccine);
        $this->assertEquals(self::SCORE_POST_DATA[ScoreConstants::OUTDOOR_SEATING], $row->outdoor_seating);
        $this->assertEquals(self::SCORE_POST_DATA[ScoreConstants::RATING], $row->rating);
        $this->assertEquals(self::SCORE_POST_DATA[ScoreConstants::IS_AFFILIATED], $row->is_affiliated);
        $this->assertEquals(self::SCORE_POST_DATA[ScoreConstants::NOTES], $row->notes);
    }

    public function testPostScoreAndConfirm() {
        $this->testPostScore();
        $results = app('db')->select("SELECT * FROM pending_score WHERE user_id = ? AND place_id = ?",
            [self::SCORE_POST_DATA[ScoreConstants::EMAIL], self::SCORE_POST_DATA[ScoreConstants::PLACE_ID]]
        );
        $this->assertEquals(1, count($results));
        $row = $results[0];

        $token = $row->token;
        $expectedJson = '{"data":{},"success":true}';

        $putResult = $this->json('PUT', '/v1/place/score/token/'.$token)
            ->seeJsonEquals(json_decode($expectedJson, true, 512, JSON_OBJECT_AS_ARRAY)
            );

        $results = app('db')->select("SELECT * FROM place_score WHERE user_id = ? AND place_id = ?",
            [self::SCORE_POST_DATA[ScoreConstants::EMAIL], self::SCORE_POST_DATA[ScoreConstants::PLACE_ID]]
        );
        $this->assertEquals(1, count($results));
        $row = $results[0];
        $this->assertEquals(self::SCORE_POST_DATA[ScoreConstants::HANDLE], $row->user_handle);
        $this->assertEquals(self::SCORE_POST_DATA[ScoreConstants::PLACE_ID], $row->place_id);
        $this->assertEquals(self::SCORE_POST_DATA[ScoreConstants::STAFF_MASKS], $row->staff_masks);
        $this->assertEquals(self::SCORE_POST_DATA[ScoreConstants::CUSTOMER_MASKS], $row->customer_masks);
        $this->assertEquals(self::SCORE_POST_DATA[ScoreConstants::VACCINE], $row->vaccine);
        $this->assertEquals(self::SCORE_POST_DATA[ScoreConstants::OUTDOOR_SEATING], $row->outdoor_seating);
        $this->assertEquals(self::SCORE_POST_DATA[ScoreConstants::RATING], $row->rating);
        $this->assertEquals(self::SCORE_POST_DATA[ScoreConstants::IS_AFFILIATED], $row->is_affiliated);
        $this->assertEquals(self::SCORE_POST_DATA[ScoreConstants::NOTES], $row->notes);
    }

    public function testPostScoreAndFind() {
        $this->testPostScoreAndConfirm();

        $testResult = $this->get('/v1/places/find?location=37.821593,-121.999961&input=bridges');
        $testResult->seeStatusCode(200);
        $testResult->seeJson([
            'success' => true
        ]);

        $content = $testResult->response->getContent();
        $resultContent = json_decode($content);
        $this->assertEquals(1, count($resultContent->data));
        //var_export($resultContent->data[0]);
        $this->assertTrue(property_exists( $resultContent->data[0], 'scores'));

        $this->assertEquals(1, $resultContent->data[0]->scores->staff_masks);
        $this->assertEquals(1, $resultContent->data[0]->scores->customer_masks);
        $this->assertEquals(0.5, $resultContent->data[0]->scores->vaccine);
        $this->assertEquals(0.5, $resultContent->data[0]->scores->outdoor_seating);
        $this->assertEquals(2.5, $resultContent->data[0]->scores->rating);
        $this->assertEquals(2, $resultContent->data[0]->scores->count);
    }

    public function testCities() {
        $testResult = $this->get('/v1/cities?q=dan');
        $testResult->seeStatusCode(200);
        $testResult->seeJson([
            'success' => true,
            'label' => 'Dana, IA'
        ]);

        $content = $testResult->response->getContent();
        $resultContent = json_decode($content);
        //var_export($resultContent);
        $this->assertEquals(43, count($resultContent->data));

        $testResult = $this->get('/v1/cities?q=danv');
        $testResult->seeStatusCode(200);
        $testResult->seeJson([
            'success' => true,
            'label' => 'Danville, CA'
        ]);
        $testResult->seeJson([
            'label' => 'Danvers, IL'
        ]);
        $content = $testResult->response->getContent();
        $resultContent = json_decode($content);
        //var_export($resultContent);
        $this->assertEquals(17, count($resultContent->data));
    }

    public function testNearby() {
        $testResult = $this->get('/v1/places/nearby?location=37.821593,-121.999961');
        $testResult->seeStatusCode(200);
        $testResult->seeJson([
            'success' => true
        ]);

        $content = $testResult->response->getContent();
        $resultContent = json_decode($content);
        $this->assertTrue(count($resultContent->data) > 0);

        foreach ($resultContent->data as $place) {
            if ($place->place_id = 'ChIJz2rJsKmMj4AR-gtLy4UsnH0') {
                $this->assertEquals("Revel Kitchen & Bar", $place->name);
                $this->assertTrue(property_exists( $place, 'scores'));
                $this->assertEquals(0, $place->scores->staff_masks);
                $this->assertEquals(0, $place->scores->customer_masks);
                $this->assertEquals(1, $place->scores->outdoor_seating);
                $this->assertEquals(0, $place->scores->vaccine);
                $this->assertEquals(1, $place->scores->rating);
                $this->assertEquals("Sep 28, 2021", $place->scores->most_recent);
                $this->assertEquals(1, $place->scores->count);
                break;
            }

        }
    }
}
