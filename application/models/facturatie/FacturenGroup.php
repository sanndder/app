<?php

namespace models\facturatie;

use models\Connector;
use models\forms\Validator;
use models\inleners\Inlener;
use models\pdf\PdfFactuur;
use models\pdf\PdfFactuurDefault;
use models\pdf\PdfFactuurUren;
use models\uitzenders\Uitzender;
use models\users\UserGroup;
use models\utils\DBhelper;
use models\utils\Tijdvak;
use models\werknemers\PlaatsingGroup;

if (!defined('BASEPATH'))exit('No direct script access allowed');


/*
 * Hoofdclass voor invoer verloning
 *
 *
 */

class FacturenGroup extends Connector
{
	protected $_jaar = NULL;
	protected $_periode = NULL;
	
	protected $_uitzender_id = NULL;
	protected $_inlener_id = NULL;
	protected $_werknemer_id = NULL;
	protected $_zzp_id = NULL;
	
	protected $_jaren_array = NULL;

	
	protected $_error = NULL;
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * constructor
	 */
	public function __construct()
	{
		//call parent constructor for connecting to database
		parent::__construct();
		
		$this->_jaar = date('Y');
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * uitzender ID
	 *
	 * @return void
	 */
	public function setUitzender( $uitzender_id ) :FacturenGroup
	{
		$this->_uitzender_id = intval($uitzender_id);
		return $this;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * get uitzender ID
	 *
	 * @return int
	 */
	public function uitzender()
	{
		return $this->_uitzender_id;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * inlener ID
	 *
	 * @return void
	 */
	public function setInlener( $inlener_id ) :FacturenGroup
	{
		$this->_inlener_id = intval($inlener_id);
		return $this;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * jaar
	 *
	 * @return void
	 */
	public function setJaar( $jaar ):FacturenGroup
	{
		$this->_jaar = intval($jaar);
		return $this;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * get inlener ID
	 *
	 * @return int
	 */
	public function inlener()
	{
		return $this->_inlener_id;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * werknemer ID
	 *
	 * @return void
	 */
	public function setWerknemer( $werknemer_id ) :FacturenGroup
	{
		$this->_werknemer_id = intval($werknemer_id);
		return $this;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * zzp ID
	 *
	 * @return void
	 */
	public function setZZP( $zzp_id ) :FacturenGroup
	{
		$this->_zzp_id = intval($zzp_id);
		return $this;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * jaren waarvoor er facturen zijn
	 *
	 *
	 */
	public function jarenArray() :?array
	{
		$sql = "SELECT DISTINCT jaar FROM facturen WHERE deleted = 0 AND concept = 0 ";
		
		if( $this->_uitzender_id != NULL )
			$sql .= " AND uitzender_id = $this->_uitzender_id ";
		
		if( $this->_inlener_id != NULL )
			$sql .= " AND inlener_id = $this->_inlener_id ";
		
		$sql .= " ORDER BY jaar DESC ";
		
		$query = $this->db_user->query( $sql );
		
		if( $query->num_rows() == 0 )
			return NULL;
		
		foreach( $query->result_array() as $row )
			$data[] = $row['jaar'];
		
		return $data;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * alle	facturen, marge en kosten ophalen
	 * TODO beter maken
	 */
	public function facturenMatrix() :?array
	{
		//verkoop facturen
		$sql = "SELECT facturen.*, inleners_factuurgegevens.factoring, inleners_bedrijfsgegevens.bedrijfsnaam, inleners_projecten.omschrijving AS project, DATEDIFF(NOW(),facturen.verval_datum) AS verval_dagen,  DATEDIFF(facturen.verval_datum,facturen.factuur_datum) AS betaaltermijn
				FROM facturen
				LEFT JOIN inleners_bedrijfsgegevens ON inleners_bedrijfsgegevens.inlener_id = facturen.inlener_id
				LEFT JOIN inleners_projecten ON inleners_projecten.id = facturen.project_id
				LEFT JOIN inleners_factuurgegevens ON inleners_factuurgegevens.inlener_id = facturen.inlener_id
				WHERE inleners_bedrijfsgegevens.deleted = 0 AND inleners_factuurgegevens.deleted = 0 AND facturen.deleted = 0 AND facturen.concept = 0 AND facturen.marge = 0 AND facturen.voldaan = 0";
				
		if( $this->_uitzender_id != NULL )
			$sql .= " AND facturen.uitzender_id = $this->_uitzender_id ";
		
		if( $this->_inlener_id != NULL )
			$sql .= " AND facturen.inlener_id = $this->_inlener_id ";
		
		$sql .= " ORDER BY jaar DESC, periode DESC ";
		
		$query = $this->db_user->query( $sql );
		
		if( $query->num_rows() == 0 )
			return NULL;
		
		foreach( $query->result_array() as $row )
		{
			if( $row['project_id'] !== NULL )
				$projecten[$row['project_id']] = 1;
			
			$data[$row['factuur_id']]['verkoop'] = $row;
		}
		
		//marge los erbij
		$sql = "SELECT * FROM facturen WHERE deleted = 0 AND concept = 0 AND parent_id IN (".array_keys_to_string($data).") ";
		$query = $this->db_user->query( $sql );
		
		if( $query->num_rows() > 0 )
		{
			foreach( $query->result_array() as $row )
				$data[$row['parent_id']]['marge'] = $row;
		}
		
		//kosten
		$sql = "SELECT * FROM facturen_kostenoverzicht WHERE deleted = 0 AND factuur_id IN (".array_keys_to_string($data).") ";
		$query = $this->db_user->query( $sql );
		
		if( $query->num_rows() > 0 )
		{
			foreach( $query->result_array() as $row )
				$data[$row['factuur_id']]['kosten'] = $row;
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