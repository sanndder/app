<?php

namespace models\api;

use models\Connector;
use models\utils\DBhelper;

if (!defined('BASEPATH'))exit('No direct script access allowed');

/*
 * Verbinding maken met credit safe api
 *
 *
 */

class Bing extends Connector
{

	/*
	 * @var array
	 */
	private $_api_token = NULL;
	private $_country = 'NL';

	/*
	 * @var array
	 */
	private $_error = NULL;
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Init api call

	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
		
		//set token
		if( ENVIRONMENT == 'development' )
			$this->_api_token = 'AsB1dzJ5B-Fd6Gym0Q3awBymfNDVbVsIW4XorNnQETTJZxc8iVqtOQ51Wx6HRxsr';
		
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * get distanec
	 *
	 */
	public function distance( $location1, $location2, $mode = 'time' )
	{
		// URL of Bing Maps REST Services Locations API
		$baseURL = "http://dev.virtualearth.net/REST/V1/Routes/Driving";
		
		// Create variables for search parameters (encode all spaces by specifying '%20' in the URI)
		$location1 = str_ireplace(" ","%20",$location1);
		$location2 = str_ireplace(" ","%20",$location2);
		
		// Compose URI for Locations API request
		$findURL = $baseURL."/?wp.0=".$location1."&wp.1=".$location2."&output=json&key=".$this->_api_token . '&culture=nl-NL&optimize='.$mode .'&routeAttributes=routeSummariesOnly';
		
		// get the response from the Locations API and store it in a string
		@$output = file_get_contents($findURL);
		
		if( !isset($output) )
		{
			$this->_error['Ongeldige invoer'] = 'Ongeldige invoer';//als key om dubbel te voorkomen
			return false;
		}
		
		//deocde
		$array = json_decode( $output, true );

		//niks gevonden
		if( !isset($array['resourceSets'][0]['resources'][0]['travelDistance']) )
		{
			$this->_error['Geen route gevonden'] = 'Geen route gevonden';//als key om dubbel te voorkomen
			return false;
		}
		
		$return['distance'] = ceil($array['resourceSets'][0]['resources'][0]['travelDistance']);
		$return['link'] = "https://bing.com/maps/default.aspx?rtp=adr.$location1~adr.$location2&mode=d";
		
		return $return;
	}
	
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * auto suggest
	 *
	 */
	public function suggestLocations( $q )
	{
		// URL of Bing Maps REST Services Locations API
		$baseURL = "http://dev.virtualearth.net/REST/v1/Autosuggest";
		
		// Create variables for search parameters (encode all spaces by specifying '%20' in the URI)
		$addressLine = str_ireplace(" ","%20",$q);
		
		// Compose URI for Locations API request
		$findURL = $baseURL."/?q=".$addressLine."&output=json&key=".$this->_api_token . '&culture=nl-NL&countryFilter=NL&userCircularMapView=52.092876,5.104480,200';
		
		// get the response from the Locations API and store it in a string
		$output = file_get_contents($findURL);
		
		$array = json_decode( $output, true );

		if( !isset($array['resourceSets']) )
			return [];
		
		foreach( $array['resourceSets'] as $rs )
		{
			foreach( $rs['resources'][0]['value'] as $r )
			{
				$locations[] = str_ireplace(', nederland', '', $r['address']['formattedAddress']);
			}
		}
		
		return $locations;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Toon errors
	 * @return array|bool
	 */
	public function errors()
	{
		//output for debug
		if (isset($_GET['debug']))
		{
			if ($this->_error === NULL)
				show('Geen errors');
			else
				show($this->_error);
		}

		if ($this->_error === NULL)
			return false;

		return $this->_error;
	}
}


?>