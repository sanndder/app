<?php

namespace models\verloning;

use models\Connector;
use models\forms\Validator;
use models\utils\DBhelper;
use models\werknemers\WerknemerGroup;

if (!defined('BASEPATH'))exit('No direct script access allowed');


/*
 * Urentypes class
 * Aanmaken, wijzigen en verwijderen urentypes
 *
 *
 */

class UrenbriefjesGroup extends Connector
{

	private $_werknemer_id = NULL;
	
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * constructor
	 */
	public function __construct()
	{
		//call parent constructor for connecting to database
		parent::__construct();

	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * Loonstroken ophalen om alle periodes te bepalen waarvoor urenbriefjes zijn
	 * Beetje opschonen, onnodige info er uit
	 */
	public function all()
	{
		$loonstrokengroup = new LoonstrokenGroup();
		$loonstroken = $loonstrokengroup->werknemer( $this->_werknemer_id )->all();
		
		if( $loonstroken === NULL )
			return $loonstroken;
		
		//opschonen
		foreach( $loonstroken as $strook )
		{
			$urenbriefje['werknemer_id'] = $strook['werknemer_id'];
			$urenbriefje['date_start'] = $strook['date_start'];
			$urenbriefje['date_end'] = $strook['date_end'];
			$urenbriefje['tijdvak'] = $strook['tijdvak'];
			$urenbriefje['jaar'] = $strook['jaar'];
			$urenbriefje['periode'] = $strook['periode'];
			$urenbriefjes[] = $urenbriefje;
		}
		
		return $urenbriefjes;
	}
	
	
	

	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * set werknemer_id
	 *
	 */
	public function werknemer( $id ) :UrenbriefjesGroup
	{
		$this->_werknemer_id = intval($id);
		return $this;
	}
	
	

	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	*
	 * Toon errors
	 * @return array|boolean
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