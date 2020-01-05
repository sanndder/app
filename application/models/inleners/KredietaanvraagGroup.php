<?php

namespace models\Inleners;
use models\Connector;
use models\forms\Validator;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/*
 * Krediet aanvraag
 *
 * Kan uiteindelijk omgezet worden in een inlener
 *
 */
class KredietaanvraagGroup extends Connector {

	private $_inlener_id = NULL;
	private $_aanvraag_id;
	
	/*
	 * @var array
	 */
	private $_error = NULL;

	/* *----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * constructor
	 */
	public function __construct()
	{
		//call parent constructor for connecting to database
		parent::__construct();
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Alle openstaande aanvragen
	 *
	 * @return object
	 */
	public function inlener( $inlener_id )
	{
		$this->_inlener_id = intval($inlener_id);
		return $this;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Alle openstaande aanvragen
	 *
	 * @return object
	 */
	public function aanvraag( $aanvraag_id )
	{
		$this->_aanvraag_id = intval($aanvraag_id);
		return $this;
	}

	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Alle openstaande aanvragen
	 *
	 * @return array
	 */
	public function all()
	{
		$sql = "SELECT inleners_kredietaanvragen.*, uitzenders_bedrijfsgegevens.bedrijfsnaam AS uitzender
				FROM inleners_kredietaanvragen
				LEFT JOIN uitzenders_bedrijfsgegevens ON inleners_kredietaanvragen.uitzender_id = uitzenders_bedrijfsgegevens.uitzender_id
				WHERE ";
		
		//beveiligen
		if( $this->user->user_type == 'uitzender' )
			$sql .= " inleners_kredietaanvragen.uitzender_id = ".$this->uitzender->id." AND ";
		
		//zoekcriteria
		if( $this->_inlener_id !== NULL )
			$sql .= " inleners_kredietaanvragen.inlener_id = $this->_inlener_id ORDER BY inleners_kredietaanvragen.timestamp DESC LIMIT 5";
		elseif( $this->_aanvraag_id !== NULL )
			$sql .= " inleners_kredietaanvragen.id = $this->_aanvraag_id ORDER BY inleners_kredietaanvragen.timestamp DESC LIMIT 5";
		else
			$sql .= " krediet_afgewezen IS NULL  AND inleners_kredietaanvragen.deleted = 0 ";
		
		
			
		$query = $this->db_user->query( $sql );
		
		if( $query->num_rows() == 0 )
			return array();
		
		foreach( $query->result_array() as $row )
		{
			$row['complete'] = 0;
			$row['archief'] = 0;
			$row['krediet'] = 1;
			$data['-1' . $row['id']] = $row;
		}
		
		return $data;
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