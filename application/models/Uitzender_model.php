<?php

use models\forms\Validator;
use models\utils\DBhelper;

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/*
 * Uitzender class
 * Wordt met uitzender inloggen geladen en is overal beschikbaar
 *
 *
 */

class Uitzender_model extends MY_Model
{
	/*
	 * @var int
	 * uitzender id
	 */
	public $uitzender_id = NULL;
	
	public $_redirect_url = NULL;
	
	/*
	 * @var array
	 * entiteiteb
	 */
	
	private $_error = NULL;

	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * construct
	 *
	 */
	public function __construct()
	{
		parent::__construct();
		
		//uitzender id
		$this->uitzender_id = $_SESSION['logindata']['main']['uitzender_id'];
		
	}



	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Zijn er nog documenten/acties die de uitzender moet uitvoeren
	 *
	 * @return boolean
	 */
	public function blockAccess()
	{
		if( $this->acceptedAV() === false )
		{
			$this->_redirect_url = 'welkom/uitzender';
			return true;
		}
		
		return false;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * waar moeten we dan heen als er nog wat te doen is
	 *
	 * @return string
	 */
	public function redirectUrl()
	{
		return $this->_redirect_url;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Algemene voorwaarden accepteren
	 *
	 * @return boolean
	 */
	public function acceptedAV()
	{
		$sql = "SELECT id FROM uitzenders_av_accepted WHERE uitzender_id = $this->uitzender_id AND deleted = 0 LIMIT 1";
		$query = $this->db_user->query( $sql );
		
		if( $query->num_rows() == 0 )
			return false;
		return true;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Algemene voorwaarden accepteren
	 *
	 * @return boolean
	 */
	public function acceptAV()
	{
		$insert['av_id'] = $this->werkgever->AVID();
		$insert['uitzender_id'] = $this->uitzender_id;
		$insert['accepted_by'] = $this->user->user_id;
		$insert['accepted_ip'] = $_SERVER['REMOTE_ADDR'];
		$insert['accepted_device'] = $_SERVER['HTTP_USER_AGENT'];
		
		$this->db_user->insert( 'uitzenders_av_accepted', $insert );
		
		if( $this->db_user->insert_id() > 0 )
			return true;
		return false;
	}

	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Toon errors
	 * @return array|boolean
	 */
	public function errors()
	{
		//output for debug
		if( isset($_GET['debug']) )
		{
			if( $this->_error === NULL )
				show('Geen errors');
			else
				show($this->_error);
		}

		if( $this->_error === NULL )
			return false;

		return $this->_error;
	}

}


?>