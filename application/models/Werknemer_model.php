<?php

use models\documenten\DocumentFactory;
use models\documenten\Template;
use models\utils\DBhelper;

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/*
 * Werknemer class
 * Wordt met werknemer inloggen geladen en is overal beschikbaar
 *
 *
 */

class Werknemer_model extends MY_Model
{
	/*
	 * @var int
	 * werknemer id
	 */
	public $werknemer_id = NULL;
	public $id = NULL;
	public $vcu = false;
	
	public $_redirect_url = NULL;
	
	//templates van documenten die getekend moeten worden
	private $_blocked_template_ids = NULL;
	
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

		//werknemer id
		if( isset( $_SESSION['logindata']['override']['werknemer_id'] ) )
			$this->werknemer_id = $_SESSION['logindata']['override']['werknemer_id'];
		else
			$this->werknemer_id = $_SESSION['logindata']['main']['werknemer_id'];

		$this->id = $this->werknemer_id;
		
		$this->_loadInfo();
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * gegevens over de werknemer laden
	 *
	 * @return string
	 */
	public function _loadInfo()
	{
		//vcu
		$query = $this->db_user->query( "SELECT werknemer_id FROM werknemers_vcu WHERE werknemer_id = $this->werknemer_id AND deleted = 0 LIMIT 1" );
		if( $query->num_rows() > 0 )
			$this->vcu = true;
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
	 *
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