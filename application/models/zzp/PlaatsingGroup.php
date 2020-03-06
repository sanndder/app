<?php

namespace models\zzp;

use models\Connector;
use models\inleners\Inlener;
use models\utils\DBhelper;
use models\verloning\UrentypesGroup;

if (!defined('BASEPATH'))
	exit('No direct script access allowed');


/*
 * Werknemer class
 *
 *
 *
 */

class PlaatsingGroup extends Connector
{
	private $_zzp_id = NULL;
	private $_inlener_id = NULL;
	
	private $_error;
	
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
	/*
	 * set werknemer ID
	 */
	public function zzp( $zzp_id ) :PlaatsingGroup
	{
		$this->_zzp_id = intval($zzp_id);
		return $this;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * set werknemer ID
	 */
	public function inlener( $inlener_id ) :PlaatsingGroup
	{
		$this->_inlener_id = intval($inlener_id);
		return $this;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * get all
	 * @return array
	 */
	public function all() :?array
	{
		$sql = "SELECT zzp_inleners.*, inleners_bedrijfsgegevens.bedrijfsnaam AS inlener,
       				 	zzp_persoonsgegevens.achternaam, zzp_persoonsgegevens.tussenvoegsel, zzp_persoonsgegevens.voorletters, zzp_persoonsgegevens.voornaam,
      					zzp_bedrijfsgegevens.bedrijfsnaam
				FROM zzp_inleners
				LEFT JOIN inleners_bedrijfsgegevens ON zzp_inleners.inlener_id = inleners_bedrijfsgegevens.inlener_id
				LEFT JOIN zzp_bedrijfsgegevens ON zzp_inleners.zzp_id = zzp_bedrijfsgegevens.zzp_id
				LEFT JOIN zzp_persoonsgegevens ON zzp_inleners.zzp_id = zzp_persoonsgegevens.zzp_id
				WHERE zzp_inleners.deleted = 0 AND inleners_bedrijfsgegevens.deleted = 0 AND zzp_persoonsgegevens.deleted = 0 AND zzp_persoonsgegevens.deleted = 0";
		
		//voor werknemer
		if( $this->_zzp_id !== NULL )
			$sql .= " AND zzp_inleners.zzp_id = $this->_zzp_id ";
		
		//voor inlener
		if( $this->_inlener_id !== NULL )
			$sql .= " AND zzp_inleners.inlener_id = $this->_inlener_id ";
		
		//sort
		$sql .= " ORDER BY zzp_persoonsgegevens.achternaam ASC";
		
		$query = $this->db_user->query( $sql );
		
		if( $query->num_rows() == 0 )
			return NULL;
		
		$inlener = new Inlener( NULL );
		$urentypesGroup = new UrentypesGroup();
		
		foreach( $query->result_array() as $row )
		{
			//medewerker naam
			$row['naam'] = make_name($row);
			
			//urentypes
			$row['urentypes'] = $urentypesGroup->inlener( $row['inlener_id'] )->urentypesZzp( $row['zzp_id'], true );
			
			$data[$row['plaatsing_id']] = $row;
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