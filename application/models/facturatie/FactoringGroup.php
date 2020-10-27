<?php

namespace models\facturatie;

use models\Connector;
use models\utils\DBhelper;
use function GuzzleHttp\Psr7\build_query;

if (!defined('BASEPATH'))exit('No direct script access allowed');


/*
 * Hoofdclass voor invoer verloning
 *
 *
 */

class FactoringGroup extends Connector
{
	
	protected $_limit = 50;
	protected $_count = 0;
	
	protected $_filter_van = NULL;
	protected $_filter_tot = NULL;
	protected $_filter_omschrijving = NULL;
	protected $_filter_nr = NULL;
	protected $_filter_aankoop = NULL;
	protected $_filter_eind = NULL;
	protected $_filter_compleet = NULL;
	protected $_filter_incompleet = NULL;
	protected $_filter_factuur = NULL;
	
	
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
		if( isset($param['aankoop']) ) $this->_filter_aankoop = 1;
		if( isset($param['eind']) ) $this->_filter_eind = 1;
		if( isset($param['compleet']) ) $this->_filter_compleet = 1;
		if( isset($param['incompleet']) ) $this->_filter_incompleet = 1;

		if( isset($param['nr']) && strlen($param['nr']) > 0 ) $this->_filter_nr = $param['nr'];
		if( isset($param['zoek']) && strlen($param['zoek']) > 0  ) $this->_filter_omschrijving = $param['zoek'];

		if( isset($param['van']) ) $this->_filter_van = reverseDate($param['van']);
		if( isset($param['tot']) ) $this->_filter_tot = reverseDate($param['tot']);
		
		if( isset($param['factuur']) ) $this->_filter_factuur = $param['factuur'];
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * Lijst met alle facturen
	 *
	 */
	public function all()
	{
		$sql = "SELECT factoring_facturen.* FROM factoring_facturen
				LEFT JOIN factoring_facturen_regels ON factoring_facturen_regels.factuur_id = factoring_facturen.factuur_id
				WHERE factoring_facturen.deleted = 0 ";
		
		//filters
		if( $this->_filter_aankoop === NULL )
			$sql .= " AND factoring_facturen.file_name_display LIKE '%eind%' ";
		
		if( $this->_filter_eind === NULL )
			$sql .= " AND factoring_facturen.file_name_display LIKE '%aankoop%' ";
		
		if( $this->_filter_compleet === NULL )
			$sql .= " AND factoring_facturen.compleet = 0 ";
		
		if( $this->_filter_incompleet === NULL )
			$sql .= " AND factoring_facturen.compleet = 1 ";
		
		if( $this->_filter_van !== NULL )
			$sql .= " AND factoring_facturen.factuur_datum >= '$this->_filter_van' ";
		
		if( $this->_filter_tot !== NULL )
			$sql .= " AND factoring_facturen.factuur_datum <= '$this->_filter_tot' ";

		if( $this->_filter_nr !== NULL )
			$sql .= " AND (factoring_facturen_regels.factuur_nr = $this->_filter_nr AND factoring_facturen_regels.deleted = 0 ) ";
		
		if( $this->_filter_omschrijving !== NULL )
			$sql .= " AND (factoring_facturen_regels.omschrijving LIKE '%".$this->_filter_omschrijving."%' AND factoring_facturen_regels.deleted = 0 ) ";
		
		if( $this->_filter_factuur !== NULL )
			$sql .= " AND (factoring_facturen.file_name_display LIKE '%".$this->_filter_factuur."%' ) ";
		
		$sql .= " ORDER BY factoring_facturen.compleet, factoring_facturen.factuur_datum DESC, factoring_facturen.timestamp DESC LIMIT $this->_limit";
		
		$query = $this->db_user->query( $sql );
		$data = DBhelper::toArray( $query, 'factuur_id', 'NULL' );
		
		if( is_array($data))
			$this->_count = count($data);
		
		return $data;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * string van de filter query maken
	 *
	 */
	public function filterQuery()
	{
		$filter = array();
		
		if( $this->_filter_aankoop !== NULL ) $filter['aankoop'] = $this->_filter_aankoop;
		if( $this->_filter_eind !== NULL ) $filter['eind'] = $this->_filter_eind;
		if( $this->_filter_compleet !== NULL ) $filter['compleet'] = $this->_filter_compleet;
		if( $this->_filter_incompleet !== NULL ) $filter['incompleet'] = $this->_filter_incompleet;
		if( $this->_filter_van !== NULL ) $filter['van'] = $this->_filter_van;
		if( $this->_filter_tot !== NULL ) $filter['van'] = $this->_filter_tot;
		if( $this->_filter_nr !== NULL ) $filter['nr'] = $this->_filter_nr;
		if( $this->_filter_omschrijving !== NULL ) $filter['zoek'] = $this->_filter_omschrijving;
		
		if( count($filter) > 0 )
			return http_build_query($filter);
		else
			return '';
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