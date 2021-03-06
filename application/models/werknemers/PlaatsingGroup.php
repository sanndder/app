<?php

namespace models\werknemers;

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
	private $_werknemer_id = NULL;
	private $_inlener_id = NULL;
	
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
	public function werknemer( $werknemer_id ) :PlaatsingGroup
	{
		$this->_werknemer_id = intval($werknemer_id);
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
	public function uurlonen() :?array
	{
		$sql = "SELECT werknemers_inleners.bruto_loon
				FROM werknemers_inleners
				WHERE werknemers_inleners.deleted = 0";
		
		//alleen voor 1 werknemer toestaan
		if( $this->_werknemer_id == NULL )
		{
			$this->_error[] = 'Stel werknemer ID in';
			return null;
		}
		
		//voor werknemer
		if( $this->_werknemer_id !== NULL )
			$sql .= " AND werknemers_inleners.werknemer_id = $this->_werknemer_id ";
		
		//sort
		$sql .= " ORDER BY werknemers_inleners.bruto_loon ASC";
		
		$query = $this->db_user->query( $sql );
		
		if( $query->num_rows() == 0 )
			return null;

		foreach( $query->result_array() as $row )
			$lonen[] = $row['bruto_loon'];

		return $lonen;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * get all
	 * @return array
	 */
	public function all() :array
	{
		$sql = "SELECT werknemers_inleners.*, inleners_bedrijfsgegevens.bedrijfsnaam AS inlener,
       					werknemers_gegevens.gb_datum, werknemers_gegevens.achternaam, werknemers_gegevens.tussenvoegsel, werknemers_gegevens.voorletters, werknemers_gegevens.voornaam,
       					cao.name AS cao, cao_jobs.name AS functie, REPLACE(LCASE(cao_salary_table.short_name), '_', ' ') AS loontabel, cao_salary_table.hours_in_workweek AS uren_werkweek
				FROM werknemers_inleners
				LEFT JOIN werknemers_status ON werknemers_status.werknemer_id = werknemers_inleners.werknemer_id
				LEFT JOIN inleners_bedrijfsgegevens ON werknemers_inleners.inlener_id = inleners_bedrijfsgegevens.inlener_id
				LEFT JOIN werknemers_gegevens ON werknemers_inleners.werknemer_id = werknemers_gegevens.werknemer_id
				LEFT JOIN cao ON cao.id = werknemers_inleners.cao_id_intern
				LEFT JOIN cao_jobs ON cao_jobs.id = werknemers_inleners.job_id_intern
				LEFT JOIN cao_salary_table ON cao_salary_table.salary_table_id = werknemers_inleners.loontabel_id_intern AND cao_salary_table.cao_id_intern = werknemers_inleners.cao_id_intern
				WHERE werknemers_inleners.deleted = 0 AND inleners_bedrijfsgegevens.deleted = 0 AND werknemers_gegevens.deleted = 0 AND werknemers_status.archief = 0";
		
		//voor werknemer
		if( $this->_werknemer_id !== NULL )
			$sql .= " AND werknemers_inleners.werknemer_id = $this->_werknemer_id ";
		
		//voor inlener
		if( $this->_inlener_id !== NULL )
			$sql .= " AND werknemers_inleners.inlener_id = $this->_inlener_id ";
		
		//sort
		$sql .= " ORDER BY werknemers_gegevens.achternaam ASC";
		
		$query = $this->db_user->query( $sql );
		
		if( $query->num_rows() == 0 )
			return array();
		
		$inlener = new Inlener( NULL );
		$urentypesGroup = new UrentypesGroup();
		
		foreach( $query->result_array() as $row )
		{
			//medewerker naam
			$row['naam'] = make_name($row);
			
			//factoren ophalen
			$row['factoren'] = $inlener->setID( $row['inlener_id'] )->factoren();
			
			//urentypes
			$row['urentypes'] = $urentypesGroup->inlener( $row['inlener_id'] )->urentypesWerknemer( $row['werknemer_id'], true );
			
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