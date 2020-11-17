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
use Psr\Log\NullLogger;

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
	
	protected $_get_ids = NULL;
	
	protected $_uitzender_id = NULL;
	protected $_inlener_id = NULL;
	protected $_werknemer_id = NULL;
	protected $_zzp_id = NULL;
	
	protected $_jaren_array = NULL;
	
	protected $_filter_wachtrij = NULL;
	protected $_filter_factoring = 1;
	protected $_filter_geen_factoring = 1;
	protected $_filter_factuur_aangekocht = NULL;

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
	 * wel of geen wachtrij
	 *
	 * @return void
	 */
	public function setWachtrij( $val ) :FacturenGroup
	{
		if( $val !== 0 && $val !== 1 && $val !== NULL )
			die('Ongeldige instelling wachtrij filter');
			
		$this->_filter_wachtrij = $val;
		
		return $this;
	}



	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * set filter
	 *
	 * @return FacturenGroup
	 */
	public function filter( $filter ) :FacturenGroup
	{
		if( !isset($filter['factoring']) && isset($filter['geenfactoring']) )
			$this->_filter_factoring = 0;
		
		if( isset($filter['factoring']) && !isset($filter['geenfactoring']) )
			$this->_filter_geen_factoring = 0;
		
		if( isset($filter['inlener_id']) && $filter['inlener_id'] != '' && intval($filter['inlener_id']) > 0 )
			$this->_inlener_id = intval($filter['inlener_id']);
		
		if( isset($filter['uitzender_id']) && $filter['uitzender_id'] != '' && intval($filter['uitzender_id']) > 0 )
			$this->_uitzender_id = intval($filter['uitzender_id']);

		if( isset($filter['factuur_aangekocht']) )
			$this->_filter_factuur_aangekocht = intval($filter['factuur_aangekocht']);
			
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
	 * alleen verkoopfacturen
	 */
	public function facturen() :?array
	{
		$sql = "SELECT *, DATEDIFF(NOW(),facturen.verval_datum) AS verval_dagen,  DATEDIFF(facturen.verval_datum,facturen.factuur_datum) AS betaaltermijn
				FROM facturen
				WHERE facturen.deleted = 0 AND facturen.concept = 0 AND facturen.marge = 0 AND facturen.inlener_id = $this->_inlener_id
				ORDER BY jaar DESC, periode DESC";
		
		$query = $this->db_user->query( $sql );
		return DBhelper::toArray( $query, 'factuur_id', 'NULL' );
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * bepaalde fatcuren ophalen
	 */
	public function getIDS( $factuur_ids ) :FacturenGroup
	{
		if( is_array($factuur_ids) && count($factuur_ids) > 0)
			$this->_get_ids = $factuur_ids;
		
		return $this;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * alle	facturen, marge en kosten ophalen
	 * TODO beter maken
	 */
	public function facturenMatrix() :?array
	{
		//verkoop facturen
		$sql = "SELECT facturen.*, inleners_factuurgegevens.factoring, inleners_bedrijfsgegevens.bedrijfsnaam, inleners_bedrijfsgegevens.kvknr, inleners_bedrijfsgegevens.btwnr, inleners_projecten.omschrijving AS project, DATEDIFF(NOW(),facturen.verval_datum) AS verval_dagen,  DATEDIFF(facturen.verval_datum,facturen.factuur_datum) AS betaaltermijn
				FROM facturen
				LEFT JOIN inleners_bedrijfsgegevens ON inleners_bedrijfsgegevens.inlener_id = facturen.inlener_id
				LEFT JOIN inleners_projecten ON inleners_projecten.id = facturen.project_id
				LEFT JOIN inleners_factuurgegevens ON inleners_factuurgegevens.inlener_id = facturen.inlener_id
				WHERE inleners_bedrijfsgegevens.deleted = 0 AND inleners_factuurgegevens.deleted = 0 AND facturen.deleted = 0 AND facturen.concept = 0 AND facturen.marge = 0 AND facturen.voldaan = 0";
				
		if( $this->_uitzender_id != NULL )
			$sql .= " AND facturen.uitzender_id = $this->_uitzender_id ";
		
		if( $this->_inlener_id != NULL )
			$sql .= " AND facturen.inlener_id = $this->_inlener_id ";
		
		if( $this->_filter_geen_factoring == 1 && $this->_filter_factoring == 0 )
			$sql .= " AND inleners_factuurgegevens.factoring = 0";
		
		if( $this->_filter_wachtrij === 1 || $this->_filter_wachtrij === 0 )
			$sql .= " AND facturen.wachtrij = $this->_filter_wachtrij ";
		
		if( $this->_filter_geen_factoring == 0 && $this->_filter_factoring == 1 )
			$sql .= " AND inleners_factuurgegevens.factoring = 1 ";
		
		if( $this->_get_ids !== NULL )
			$sql .= " AND facturen.factuur_id IN (".implode(',',$this->_get_ids).")";

		
		$sql .= " ORDER BY jaar DESC, periode DESC, to_factoring_on, inleners_bedrijfsgegevens.bedrijfsnaam ";
		
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

		//TODO Dit moet anders, hij pakt alle facturen
		//aankoopbetalingen erbij wanneer nodig
		if( $this->_filter_factuur_aangekocht !== NULL )
		{
			$sql = "SELECT factuur_id, categorie_id FROM facturen_betalingen 
					WHERE factuur_id IN (" . array_keys_to_string($data) . ")
					AND deleted = 0 AND categorie_id = 2
					";

			$query = $this->db_user->query($sql);
			if ($query->num_rows() > 0)
			{
				foreach ($query->result_array() as $row)
					$betalingen[$row['factuur_id']] = $row['categorie_id'];
			}

			//nu opschonen
			foreach( $betalingen as $factuur_id => $categorie_id )
			{
				if( isset($data[$factuur_id]) && $this->_filter_factuur_aangekocht == 0)
				{
					unset($data[$factuur_id]);
				}
			}
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