<?php

namespace models\api;
use models\Connector;

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/*
 * KvK api
 *
 *
 */

class Kvk extends Connector
{

	private $_kvknr = NULL;
	
	/*
	 * @var array
	 */
	protected $_error = NULL;


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 *
	 *
	 */
	public function __construct( $kvknr )
	{
		parent::__construct();
		$this->_kvknr = intval( $kvknr );
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 *
	 *
	 */
	public function getSbiCodes()
	{
		$data = $this->fetchCompanyData();
		$result = array();

		//sbi
		if(isset($data['businessActivities']))
		{
			foreach($data['businessActivities'] as $a )
			{
				$sbi['sbi_code'] = $a['sbiCode'];
				$sbi['sbi_descrition'] = $a['sbiCodeDescription'];
				
				$result[] = $sbi;
			}
		
		}
		return $result;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 *
	 *
	 */
	public function fetchCompanyData()
	{
		
		$nr = str_replace(array(' ', '.', '-', ',', ', ','_'), '', trim($this->_kvknr));
		
		if( strlen($nr) != 8 )
			return false;
		
		$api_key = 'l7xxe2047a382bfa474892e419f57e416977';
		
		$ch = curl_init();
		$url = 'https://api.kvk.nl:443/api/v2/profile/companies';
		$queryParams = '?' . urlencode('kvkNumber') . '=' . urlencode($nr) . '&' . urlencode('apikey') . '=' . urlencode($api_key);
		curl_setopt($ch, CURLOPT_URL, $url . $queryParams);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
		$response = curl_exec($ch);
		curl_close($ch);
		
		$profile = json_decode($response, true);
		
		if( isset($profile['error']) )
			return $profile;
		
		//wanneer Legal Person, dan pagina 2 pakken
		if( $profile['data']['items'][0]['isLegalPerson'] == 1 && isset($profile['data']['nextLink']) )
		{
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $profile['data']['nextLink']);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($ch, CURLOPT_HEADER, FALSE);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
			$response = curl_exec($ch);
			curl_close($ch);
			
			$profile = json_decode($response, true);
			
		}
		return $profile['data']['items'][0];
	}
	

	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Toon errors
	 * @return boolean
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