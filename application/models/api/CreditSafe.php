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

class CreditSafe extends Connector
{

	/*
	 * @var array
	 */
	private $_api_token = NULL;
	private $_api_username = 'sander@aberinghr.nl';
	private $_api_password = '7/w0DE6xhB]]g$y$|cuz';
	private $_api_url_base = 'https://connect.creditsafe.com/v1';
	
	private $_kvknr = NULL;
	private $_cs_id = NULL;


	/*
	 * @var array
	 */
	private $_report_date = NULL;
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Init api call

	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}
	
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Api token instellen
	 * @return string|boolean
	 */
	public function _getReportFromDatabase()
	{
		$sql = "SELECT * FROM creditsafe_reports WHERE kvknr = ? AND deleted = 0 LIMIT 1";
		$query = $this->db_user->query( $sql, array($this->_kvknr) );

		$row = DBhelper::toRow($query, 'NULL');

		if( $row === NULL )
			return false;
		
		$this->_report_date = $row['timestamp'];
		
		return $row['report'];
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Rapport datum
	 * @return string
	 */
	public function reportDate()
	{
		return $this->_report_date;	
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Api token instellen
	 * @return void
	 */
	public function _saveReport( $report )
	{
		//delete all previous
		$this->db_user->query( "UPDATE creditsafe_reports SET deleted = 1, deleted_by = ".$this->user->user_id.", deleted_on = NOW() WHERE kvknr = ?", array( $this->_kvknr ) );
		
		$insert['kvknr'] = $this->_kvknr;
		$insert['creditsafe_id'] = $this->_cs_id;
		$insert['report'] = $report;
		$insert['user_id'] = $this->user->user_id;
		
		$object = json_decode($report);
		$insert['limiet'] = $object->companySummary->creditRating->creditLimit->value;
		
		$this->db_user->insert( 'creditsafe_reports', $insert );
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Api token instellen
	 * @return array|null
	 */
	public function companyReport( $kvknr, $refresh = false )
	{
		//make available
		$this->_kvknr = $kvknr;

		//wanneer Creditsafe ID nog niet bekend is, dan moeten we die ophalen
		if( !$this->_getCreditsafeIdFromDatabase( $kvknr ) )
		{
			if( $this->searchCompany($kvknr) === NULL )
				return NULL;
		}
		
		//check of ID bekend is
		if( $this->_cs_id === NULL )
		{
			$this->_error[] = 'Geen Creditsafe ID bekend';
			return NULL;
		}
		//is er een report in de database
		if( $refresh === false )
		{
			$report = $this->_getReportFromDatabase();
			if( $report !== false )
				return json_decode($report,true);
		}
		
		//eerst token ophalen
		$this->_setApiToken();
		
		//init
		$curl = curl_init( $this->_api_url_base . '/companies/' . $this->_cs_id . '?language=nl' );
		
		//juiste header
		$headers = array(
			'Content-Type: application/json',
			'Authorization: ' .$this->_api_token
		);
		
		//options
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		
		$json = curl_exec($curl);
		$json = json_decode($json);
		$curl_info = curl_getinfo($curl);
		
		curl_close($curl);
		
		$json = $this->_processResult( $json, $curl_info );
		
		if( $json !== false )
		{
			if( isset($json->report) )
			{
				$json = json_encode( $json->report );
				$this->_saveReport( $json );
				$this->_report_date = date('Y-m-d H:i:s');
				return json_decode($json,true);
			}
			else
			{
				$this->_error[] = 'Er is geen rapport gevonden';
				return NULL;
			}
		}
		
		return NULL;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Korte info van company ophalen
	 * @return array?
	 */
	public function searchCompany( $kvknr )
	{
		//safe
		$this->_kvknr = $kvknr;
		
		//eerst token ophalen
		$this->_setApiToken();
		
		//init
		$curl = curl_init(  $this->_api_url_base . '/companies?regNo='.$kvknr.'&countries=NL&page=1&pageSize=10&' );
		
		//juiste header
		$headers = array(
			'Content-Type: application/json',
			'Authorization: ' .$this->_api_token
		);
		
		//options
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		
		$json = curl_exec($curl);
		$json = json_decode($json);
		$curl_info = curl_getinfo($curl);

		curl_close($curl);
		
		$json = $this->_processResult( $json, $curl_info );
		
		//TODO: ondersteuning voor meerdere vestigingen
		if( $json !== false )
		{
			if( isset($json->totalSize) )
			{
				if( $json->totalSize == 0 )
				{
					$this->_error[] = 'KvK nummer leverde geen resultaten op';
					return NULL;
				}
				else
				{
					if(isset($json->companies[0]) && isset($json->companies[0]->id) )
					{
						$this->_cs_id = $json->companies[0]->id;
						$this->_saveCreditSafeId( $kvknr, $json->companies[0]->id );
						return json_decode(json_encode($json->companies[0],true));
					}
				}
			}
		}
		
		return NULL;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Api token ophalen
	 * @return string|bool
	 */
	public function _processResult( $json, $curl_info )
	{
		//call gelukt?
		if( $curl_info['http_code'] == 0 || $curl_info['http_code'] == 404 )
		{
			$this->_error[] = 'URL is ongeldig (2)';
			return false;
		}
		
		if( isset($json->message ) )
		{
			$this->_error[] = $json->message;
			//extra info ?
			if( isset($json->details))
				$this->_error[] = $json->details;
			
			return false;
		}
		elseif( isset($json->error) )
		{
			$this->_error[] = $json->error;
			return false;
		}

		//geen erros, dan json terug
		return $json;
	}


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Opgezochte id's opslaan voor hergebruik
	 * @return bool
	 */
	public function _getCreditsafeIdFromDatabase( $kvknr )
	{
		$sql = "SELECT creditsafe_id FROM creditsafe_id WHERE kvknr = ? LIMIT 1";
		$query = $this->db_user->query( $sql, array($kvknr) );

		if( $query->num_rows() > 0 )
		{
			$data = $query->row_array();
			$this->_cs_id = $data['creditsafe_id'];
			
			return true;
		}
		
		return false;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Opgezochte id's opslaan voor hergebruik
	 * @return void
	 */
	public function _saveCreditSafeId( $kvknr, $creditsafe_id )
	{
		$query = $this->db_user->query( "SELECT * FROM creditsafe_id WHERE kvknr = ? AND creditsafe_id = ? LIMIT 1", array( $kvknr, $creditsafe_id ) );
		
		if( $query->num_rows() == 0 )
		{
			$insert['kvknr'] = $kvknr;
			$insert['creditsafe_id'] = $creditsafe_id;
			$insert['user_id'] = $this->user->user_id;
			
			$this->db_user->insert( 'creditsafe_id', $insert );
		}
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Api token ophalen
	 * @return void
	 */
	public function _setApiToken()
	{
		//init
		$curl = curl_init( $this->_api_url_base . '/authenticate' );
		
		//juiste header
		$headers = array('Content-Type: application/json');
		
		$array['username'] = $this->_api_username;
		$array['password'] = $this->_api_password;
		
		$data = json_encode($array, JSON_UNESCAPED_SLASHES);
		
		//options
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		
		$json = curl_exec($curl);
		$json = json_decode($json);
		$curl_info = curl_getinfo($curl);
		
		//call gelukt?
		if( $curl_info['http_code'] == 0 || $curl_info['http_code'] == 404 )
		{
			$this->_error[] = 'URL is ongeldig (1)';
			return NULL;
		}
		
		//token beschikbaar?
		if( isset($json->token) )
		{
			$this->_api_token = $json->token;
		}
		else
		{
			if( isset($json->message))
				$this->_error[] = $json->message;
			else
				$this->_error[] = 'Er is een onbekende fout opgetreden';
		}

		curl_close($curl);
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * get token
	 * @return string
	 */
	public function token()
	{
		return $this->_api_token;
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