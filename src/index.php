<?php
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', dirname(__FILE__) . '/error_log.txt');
error_reporting(E_ALL);

require 'lib/foursquare.php';

//echo Foursquare::getVenueByLatLng(29.671354,-95.320244);

$str = file_get_contents('foursquare3.json');
$json = json_decode($str, true); 
$venues = array();

foreach ($json as $data) {
    $venue = Foursquare::getCitgoVenue($data["lat"],$data["lon"], $data["id"], $data["name"]);
    array_push($venues, $venue);
}

echo json_encode($venues);

?>