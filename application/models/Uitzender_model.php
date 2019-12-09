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
		$this->_redirect_url = 'welkom/uitzender';
		return true;
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