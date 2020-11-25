<?php

namespace models\boekhouding;

use models\Connector;
use models\utils\DBhelper;

if (!defined('BASEPATH'))exit('No direct script access allowed');


/*
 * Hoofdclass voor invoer verloning
 *
 *
 */

class TransactieGroup extends Connector
{
	
	protected $_limit = 50;
	protected $_count = 0;
	
	protected $_filter_van = NULL;
	protected $_filter_tot = NULL;
	protected $_filter_min = NULL;
	protected $_filter_max = NULL;
	protected $_filter_zoek = NULL;
	protected $_filter_bij = NULL;
	protected $_filter_af = NULL;
	protected $_filter_verwerkt = NULL;
	protected $_filter_onverwerkt = NULL;
	protected $_filter_grekening = NULL;
	
	protected $_error = NULL;
	
	
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
	 * Lijst met alle categorien
	 *
	 */
	public function listCategorien()
	{
		$query = $this->db_user->query( "SELECT categorie_id, categorie, factoring, factuur FROM bank_transacties_categorien WHERE deleted = 0 ORDER BY categorie" );
		if( $query->num_rows() == 0 )
			return NULL;
		
		return DBhelper::toArray( $query, 'categorie_id', 'NULL' );
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * Lijst met alle facturen
	 *
	 */
	public function limit( $limit )
	{
		$this->_limit = intval( $limit );
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * filter parameters
	 *
	 */
	public function filter( $param )
	{
		if( isset($param['bij']) ) $this->_filter_bij = $param['bij'];
		if( isset($param['af']) ) $this->_filter_af = $param['af'];
		
		if( isset($param['verwerkt']) ) $this->_filter_verwerkt = $param['verwerkt'];
		if( isset($param['onverwerkt']) ) $this->_filter_onverwerkt = $param['onverwerkt'];
		
		if( isset($param['min']) ) $this->_filter_min = floatval($param['min']);
		if( isset($param['max']) ) $this->_filter_max = floatval($param['max']);
		
		if( isset($param['grekening']) ) $this->_filter_grekening = $param['grekening'];
		if( isset($param['zoek']) && strlen($param['zoek']) > 0  ) $this->_filter_zoek = $param['zoek'];

		if( isset($param['van']) ) $this->_filter_van = reverseDate($param['van']);
		if( isset($param['tot']) ) $this->_filter_tot = reverseDate($param['tot']);
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * Lijst met alle facturen
	 *
	 */
	public function all()
	{
		$sql = "SELECT bank_transacties.*, bank_transactiebestanden.grekening FROM bank_transacties
				LEFT JOIN bank_transactiebestanden ON bank_transactiebestanden.bestand_id = bank_transacties.bestand_id
				LEFT JOIN bank_transacties_facturen ON bank_transacties_facturen.transactie_id = bank_transacties.transactie_id
				WHERE bank_transacties.deleted = 0 ";
		
		//filters
		
		if( $this->_filter_bij == 1 &&  $this->_filter_af == 0 )
			$sql .= " AND bank_transacties.bedrag > 0 ";
		
		if( $this->_filter_af == 1 && $this->_filter_bij == 0 )
			$sql .= " AND bank_transacties.bedrag < 0 ";
		
		if( $this->_filter_verwerkt == 1 && $this->_filter_onverwerkt == 0 )
			$sql .= " AND bank_transacties.verwerkt = 1 ";
		
		if( $this->_filter_verwerkt == 0 && $this->_filter_onverwerkt == 1 )
			$sql .= " AND bank_transacties.verwerkt = 0 ";
		
		if( $this->_filter_min !== NULL && strlen($this->_filter_min) > 0 && $this->_filter_min != 0)
			$sql .= " AND ABS(bank_transacties.bedrag) >= $this->_filter_min ";
		
		if( $this->_filter_max !== NULL && strlen($this->_filter_max) > 0 && $this->_filter_max != 0)
			$sql .= " AND ABS(bank_transacties.bedrag) <= $this->_filter_max ";
		
		if( $this->_filter_van !== NULL )
			$sql .= " AND bank_transacties.datum >= '$this->_filter_van' ";
		
		if( $this->_filter_tot !== NULL )
			$sql .= " AND bank_transacties.datum <= '$this->_filter_tot' ";
		

		if( $this->_filter_grekening !== NULL )
		{
			if( $this->_filter_grekening == 0 )
				$sql .= " AND bank_transactiebestanden.grekening = 0 ";
			if( $this->_filter_grekening == 1 )
				$sql .= " AND bank_transactiebestanden.grekening = 1 ";
		}
		
		
		if( $this->_filter_zoek !== NULL )
			$sql .= " AND (bank_transacties.omschrijving LIKE '%".$this->_filter_zoek."%'
						|| bank_transacties.relatie LIKE '%".$this->_filter_zoek."%'
						|| bank_transacties.opmerking LIKE '%".$this->_filter_zoek."%'
						|| bank_transacties.relatie_iban LIKE '%".$this->_filter_zoek."%' ) ";
		
		
		$sql .= " ORDER BY bank_transacties.datum DESC LIMIT $this->_limit";
		//show($sql);
		$query = $this->db_user->query( $sql );
		
		if( $query->num_rows() == 0 )
			return NULL;
		
		foreach( $query->result_array() as $row )
		{
			$row['datum_format'] = reverseDate( $row['datum']);
			$data[$row['datum'] . $row['transactie_id']] = $row;
		}
		
		if( is_array($data))
			$this->_count = count($data);
		
		return $data;
	}

	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * Tellen
	 *
	 */
	public function count()
	{
		return $this->_count;
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