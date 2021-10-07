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

        $expectedJson = '{"success":true,"data":[{"id":4,"user_id":"joe@example.com","user_handle":"Joe Random","place_id":"ChIJOet126uMj4ARQL_qWEW12Jw","staff_masks":1,"customer_masks":1,"outdoor_seating":0,"vaccine":0,"rating":2,"is_affiliated":0,"notes":"Bridges is ok","created_at":"2021-09-15 17:40:00","published_at":"2021-09-30 19:52:49"},{"id":5,"user_id":"betty@example.com","user_handle":"Betty Borstein","place_id":"ChIJp2fEzamMj4ARliL8eEwHKYo","staff_masks":0,"customer_masks":0,"outdoor_seating":1,"vaccine":0,"rating":2,"is_affiliated":0,"notes":"The Peasant & The Pear do an ok job","created_at":"2021-09-18 14:50:00","published_at":"2021-09-30 19:52:49"},{"id":6,"user_id":"jerry@example.com","user_handle":"Jerry Jackman","place_id":"ChIJz2rJsKmMj4AR-gtLy4UsnH0","staff_masks":0,"customer_masks":0,"outdoor_seating":1,"vaccine":0,"rating":1,"is_affiliated":0,"notes":"Revel Kitchen & Bar too crowded","created_at":"2021-09-28 19:00:00","published_at":"2021-09-30 19:52:49"},{"id":7,"user_id":"ddr@drake.com","user_handle":"Drake","place_id":"ChIJsf-R07OMj4ARY49JhQdBgww","staff_masks":1,"customer_masks":0,"outdoor_seating":1,"vaccine":0,"rating":2,"is_affiliated":0,"notes":"","created_at":"2021-10-01 06:10:33","published_at":"2021-10-01 06:24:10"},{"id":8,"user_id":"david@bdt.com","user_handle":"David B","place_id":"ChIJKfDOzqmMj4ARvBZlUVWRpGA","staff_masks":1,"customer_masks":0,"outdoor_seating":1,"vaccine":0,"rating":2,"is_affiliated":0,"notes":"It\'s a little crowded but generally decent outdoor spacing","created_at":"2021-10-01 19:46:52","published_at":"2021-10-01 19:48:52"}]}';

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
