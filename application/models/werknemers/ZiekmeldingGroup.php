<?php

namespace models\werknemers;

use models\Connector;
use models\users\UserGroup;
use models\utils\DBhelper;

if (!defined('BASEPATH'))
	exit('No direct script access allowed');


/*
 * Ziekmelding class
 *
 *
 *
 */

class ZiekmeldingGroup extends Connector
{
	/**
	 * @var int
	 */
	private $_werknemer_id = NULL;
	private $_uitzender_id = NULL;

	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * constructor
	 */
	public function __construct( $melding_id = NULL )
	{
		//call parent constructor for connecting to database
		parent::__construct();
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Set werknemer ID
	 */
	public function werknemer( $id = NULL )
	{
		$this->_werknemer_id = intval($id);
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Set uitzender ID
	 */
	public function uitzender( $id = NULL )
	{
		$this->_uitzender_id = intval($id);
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * get meldingen
	 *
	*/
	public function all() :?array
	{
		//Extra beveiliging vor ziekmeldingen
		if( $this->user->user_type == 'werknemer' && $this->_werknemer_id === NULL )
			die('Geen toegang (geen werknemer ID bekend)');
		
		if( $this->user->user_type == 'inlener')
			die('Geen toegang');
		
		if( $this->user->user_type == 'uitzender' && $this->_uitzender_id === NULL )
			die('Geen toegang (geen uitzender ID bekend)');
		
		$sql = "SELECT ziekmeldingen.*, wg.voorletters, wg.voornaam, wg.tussenvoegsel, wg.achternaam
				FROM ziekmeldingen
				LEFT JOIN werknemers_gegevens wg ON ziekmeldingen.werknemer_id = wg.werknemer_id
				WHERE ziekmeldingen.deleted = 0 AND wg.deleted = 0";
		
		if( $this->_werknemer_id !== NULL )
			$sql .= " AND ziekmeldingen.werknemer_id = $this->_werknemer_id ";
		
		$query = $this->db_user->query( $sql );
		
		if( $query->num_rows() == 0 )
			return NULL;
		
		foreach( $query->result_array() as $row )
		{
			$row['naam'] = make_name($row);
			
			if( $row['datum_eind_ziek'] === NULL )
				$data[0][] = $row;
			else
				$data[1][] = $row;
		}
		
		if(isset($data[0])) $data[0] = UserGroup::findUserNames($data[0]);
		if(isset($data[1])) $data[1] = UserGroup::findUserNames($data[1]);
		if( isset($_GET['show']))
			show($data);
		return $data;
	}

}


?>