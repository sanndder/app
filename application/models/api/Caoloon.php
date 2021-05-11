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

class Caoloon extends Connector
{

	/*
	 * @var array
	 */
	private $_api_key = 'Token 123151323f47b47fd57cb20782350def04b30443';
	private $_api_url = 'https://staging.caoloon.com/customer/api/caos/';
	
	//private $_api_key = 'Token 4436b6621feee47fdababa479413012104b34212';
	//private $_api_url = 'https://member.caoloon.com/customer/api/caos/';
	
	private $_curl_result = NULL;
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Init api call
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}



	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * data ophalen en in database updaten
	 *
	 */
	public function refreshCaoData()
	{
		$this->getApiData();
		
		if( $this->_curl_result === NULL )
			return false;
		
		$array = json_decode($this->_curl_result, true);
		
		foreach( $array as $cao )
		{
			$insert['cao_id'] = $cao['cao_id'];
			$insert['name'] = $cao['name'];
			$insert['short_name'] = $cao['short_name'];
			$insert['duration_start'] = $cao['duration_start'];
			$insert['duration_end'] = $cao['duration_end'];
			$insert['code'] = $cao['code'];
			$insert['sbi'] = $cao['sbi'];
			$insert['avv'] = $cao['avv'];
			$insert['html_cao_text'] = $cao['html_cao_text'];
			
			$this->db_user->insert('cao', $insert);
		}

	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * get data
	 *
	 */
	public function getApiData()
	{
		//init curl
		$curl = curl_init( $this->_api_url );
		
		//header met key
		$headers = array(
			'Authorization:' . $this->_api_key
		);
		
		//options meegeven
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		
		//result
		$this->_curl_result = curl_exec($curl);
		
		//en weer sluiten
		curl_close($curl);
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