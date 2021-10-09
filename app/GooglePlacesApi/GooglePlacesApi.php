<?php

namespace App\GooglePlacesApi;

abstract class GooglePlacesApi
{
    abstract function nearbySearch($location, $radius, $type);
    abstract function textSearch($input, $location, $radius, $type);
    abstract function placeDetails($place_id, $fields);
}
