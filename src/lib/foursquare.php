<?php

/*	
* 	Foursquare
*/

define('__ROOT__', dirname(dirname(__FILE__))); 
require __ROOT__.'/config.php';
require __ROOT__.'/vendor/FoursquareAPI.class.php';

class Foursquare
{
	public static function getCitgoVenue($lat, $lng, $id, $locationName){
		$response = self::getVenueByLatLng($lat, $lng);
		$response_decoded = json_decode($response);
		$objVenue = new Venue();
		$objVenue->id = $id;

		if ($response_decoded->meta->code == "200") {
			foreach ($response_decoded->response->venues as $venue) {
				if (isset($venue->name) && stripos($venue->name, $locationName) !== false){
					$objVenue->venue_id = $venue->id;

					if (isset($venue->location->address))
						$objVenue->address = $venue->location->address;
					if (isset($venue->location->city))
						$objVenue->city = $venue->location->city;
					if (isset($venue->location->state))
						$objVenue->state = $venue->location->state;

					$objVenue->foursquare_url = "https://foursquare.com/v/".$venue->name."/".$venue->id;
					$objVenue->verified = $venue->verified;
				}
			}
		}
		return $objVenue;
	}

	public static function getVenueByLatLng($lat, $lng){
		$foursquare = new FoursquareAPI(FOURSQUARE_CLIENT_KEY, FOURSQUARE_CLIENT_SECRET);
		
		list($lat,$lng) = array($lat, $lng);
		
		// Prepare parameters
		$params = array("ll"=>"$lat,$lng", "categoryId"=>"4bf58dd8d48988d113951735", "radius"=>"800");

		// Perform a request to a public resource
		$response = $foursquare->GetPublic("venues/search",$params);
		return $response;
	}
}

/*
* 	Venue Entity
*/
class Venue
{
	public $id = "";
	public $venue_id = "";
	public $address = "";
	public $city = "";
	public $state = "";
	public $foursquare_url = "";
	public $verified = "";
}

?>