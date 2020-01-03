<?php

namespace models\Api;


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
	protected $_api_token = NULL;
	protected $_api_username = 'sander@aberinghr.nl';
	protected $_api_password = '7/w0DE6xhB]]g$y$|cuz';
	protected $_api_url_base = 'https://connect.creditsafe.com/v1';
	
	protected $_kvknr = NULL;
	protected $_cs_id = NULL;


	/*
	 * @var array
	 */
	protected $_error = NULL;


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Init api call

	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
		
		//eerst token ophalen
		$this->_setApiToken();
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Api token instellen
	 * @return string|null
	 */
	public function companyReport( $refresh = false )
	{
		//check of ID al bekend is
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
				return $report;
		}
		
		//init
		$curl = curl_init( $this->_api_url_base . '/companies/' . $this->_cs_id );
		
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
		
		//call gelukt?
		if( $curl_info['http_code'] == 0 || $curl_info['http_code'] == 404 )
		{
			$this->_error[] = 'URL is ongeldig (2)';
			return NULL;
		}
		
		//token beschikbaar?
		if( isset($json->report) )
		{
			$json = json_encode($json->report);
			show($json);
			$this->_saveReport( $json );
		}
		else
		{
			if( isset($json->message) )
			{
				$this->_error[] = $json->message;
				if( isset($json->details)) $this->_error[] = $json->details;
			}
			elseif( isset($json->error) )
			{
				$this->_refreshApiToken();
				$this->_error[] = $json->error;
			}
			else
				$this->_error[] = 'Er is een onbekende fout opgetreden (2)';
		}
		
		curl_close($curl);
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
		
		$row = DBhelper::toRow($query);
		
		if( $row === NULL )
			return false;
		
		return $row['report'];
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
		
		$this->db_user->insert( 'creditsafe_reports', $insert );
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Api token instellen
	 * @return object
	 */
	public function searchCompany( $kvknr )
	{
		//safe
		$this->_kvknr = $kvknr;
		
		//check of ID al bekend is
		if( $this->_getCreditsafeIdFromDatabase( $kvknr ) )
			return $this;
		
		//init
		$curl = curl_init(  $this->_api_url_base . '/companies?regNo='.$kvknr.'&countries=NL&page=1&pageSize=10' );
		
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
		
		//call gelukt?
		if( $curl_info['http_code'] == 0 || $curl_info['http_code'] == 404 )
		{
			$this->_error[] = 'URL is ongeldig (2)';
			return NULL;
		}
		
		//token beschikbaar?
		if( isset($json->totalSize) )
		{
			if( $json->totalSize != 1 )
				$this->_error[] = 'KvK nummer leverde geen resultaten op';
			else
			{
				if(isset($json->companies[0]) && isset($json->companies[0]->id) )
				{
					$this->_cs_id = $json->companies[0]->id;
					$this->_saveCreditSafeId( $kvknr, $json->companies[0]->id);
				}
			}
		}
		else
		{
			if( isset($json->message) )
			{
				$this->_error[] = $json->message;
				if( isset($json->details)) $this->_error[] = $json->details;
			}
			elseif( isset($json->error) )
			{
				$this->_refreshApiToken();
				$this->_error[] = $json->error;
			}
			else
				$this->_error[] = 'Er is een onbekende fout opgetreden (2)';
		}
		
		curl_close($curl);
		
		return $this;
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
		$insert['kvknr'] = $kvknr;
		$insert['creditsafe_id'] = $creditsafe_id;
		$insert['user_id'] = $this->user->user_id;
		
		$this->db_user->insert( 'creditsafe_id', $insert );
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Api token instellen
	 * @return void
	 */
	public function _setApiToken()
	{
		//token is al ingesteld. Gelijk door
		if( $this->_api_token !== NULL )
			return NULL;
		
		//is er een key in geheugen?
		if( isset($_SESSION['credit_safe_token']) && isset($_SESSION['credit_safe_expires']) )
		{
			if( time() < $_SESSION['credit_safe_expires'] )
				$this->_api_token = $_SESSION['credit_safe_token'];
		}
		//nieuwe key ophalen
		else
		{
			$this->_refreshApiToken();
		}
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Api token ophalen
	 * @return void
	 */
	public function _refreshApiToken()
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
			$_SESSION['credit_safe_expires'] = time() + 60*59;//59 minuten erbij
			$_SESSION['credit_safe_token'] = $json->token;
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