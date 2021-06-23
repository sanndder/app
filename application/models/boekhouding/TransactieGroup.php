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
	
	protected $_filter_inlener_id = NULL;
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
	 * betalingen zo veel mogelijk verwerken
	 *
	 */
	public function verwerken()
	{
		$sql = "SELECT * FROM bank_transacties WHERE verwerkt = 0 AND deleted = 0 AND hidden = 0";
		$query = $this->db_user->query( $sql );
		
		if( $query->num_rows() == 0 )
			return NULL;
		
		//ignore list ophalen
		$ignoreList = $this->_ignoreList();
		
		//init
		$hide = array();
		
		foreach( $query->result_array() as $row )
		{
			$omschrijving = strtolower( $row['omschrijving'] );
			$relatie = strtolower( $row['relatie'] );
			
			//eigen transacties
			if( strpos( $relatie, 'abering ' ) !== false )
				$hide[$row['transactie_id']] = 1;
			
			if( strpos( $relatie, 'flexxoffice ' ) !== false )
				$hide[$row['transactie_id']] = 1;
			
			if( strpos( $relatie, 'belastingdienst ' ) !== false )
				$hide[$row['transactie_id']] = 1;
			
			//eerst op woorden zoeken en verbergen
			if( strpos( $omschrijving, 'salaris ' ) !== false )
				$hide[$row['transactie_id']] = 1;
			
			//eerst op woorden zoeken en verbergen
			if( strpos( $omschrijving, 'marge ' ) !== false )
				$hide[$row['transactie_id']] = 1;
			
			if( $ignoreList !== NULL )
			{
				if( in_array( $row['relatie_iban'], $ignoreList ) )
					$hide[$row['transactie_id']] = 1;
			}
			
			//ING kosten
			if( strpos( $omschrijving, 'kosten zakelijk betalingsverkeer' ) !== false )
				$hide[$row['transactie_id']] = 1;
			
			//SEPA's ook niet
			if( preg_match("/SEPA[0-9]{8,}/i", $omschrijving) == 1)
				$hide[$row['transactie_id']] = 1;
			
			//aankoop/eind factris
			if( preg_match("/PN-[0-9]{4,}/i", $omschrijving) == 1)
				$hide[$row['transactie_id']] = 1;
			if( preg_match("/CN-[0-9]{4,}/i", $omschrijving) == 1)
				$hide[$row['transactie_id']] = 1;
		}
		
		if( count($hide) > 0 )
			$this->db_user->query( "UPDATE bank_transacties SET hidden = 1 WHERE deleted = 0 AND transactie_id IN (".array_keys_to_string($hide).")" );
		
		//koppel gelijk inleners
		$this->_koppelInleners();
		
	}

	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * inlener via koppelingen updaten
	 *
	 */
	private function _koppelInleners() :void
	{
		$sql = "UPDATE bank_transacties INNER JOIN bank_transacties_koppeling
				SET bank_transacties.inlener_id = bank_transacties_koppeling.inlener_id
				WHERE bank_transacties_koppeling.relatie_iban = bank_transacties.relatie_iban AND bank_transacties.verwerkt = 0 AND bank_transacties.inlener_id IS NULL";
		
		$query = $this->db_user->query( $sql );
		
		return;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * welke ibans negeren
	 *
	 */
	public function _ignoreList()
	{
		$query = $this->db_user->query( "SELECT koppeling_id, relatie_iban FROM bank_transacties_koppeling WHERE  action = 'ignore' AND deleted = 0 GROUP BY relatie_iban" );
		return DBhelper::toList( $query, array( 'koppeling_id' => 'relatie_iban') );
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
	 * filter inlener
	 *
	 */
	public function inlener( $inlener_id = NULL )
	{
		if( $inlener_id !== NULL ) $this->_filter_inlener_id = intval($inlener_id);
		return $this;
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
				WHERE bank_transacties.deleted = 0 AND hidden = 0";
		
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
		
		if( $this->_filter_inlener_id !== NULL )
			$sql .= " AND bank_transacties.inlener_id = $this->_filter_inlener_id ";

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
		
		
		$sql .= " ORDER BY bank_transacties.datum DESC, transactie_id DESC LIMIT $this->_limit";
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
		
		//automatisch verwerken
		$this->autoVerwerk();
		
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
	 *
	 * Automatisch koppelen
	 *
	 */
	public function autoVerwerk()
	{
		//voorfinanciering
		$sql = "SELECT transactie_id, bedrag, bank_transactiebestanden.grekening, omschrijving
				FROM bank_transacties
				LEFT JOIN bank_transactiebestanden ON bank_transacties.bestand_id = bank_transactiebestanden.bestand_id
				WHERE bank_transacties.deleted = 0 AND (omschrijving LIKE '%oorfinancie%' OR omschrijving LIKE 'terug%') AND verwerkt = 0 AND auto_koppeling IS NULL ";
		
		$query = $this->db_user->query( $sql );
		
		if( $query->num_rows() > 0 )
		{
			foreach( $query->result_array() as $row )
			{
				$omschrijving = str_replace( array('.','2021'), '', $row['omschrijving']);
				preg_match_all( '!\d{4,}!', $omschrijving, $matches );
				$factuur_nr = implode( ',', $matches[0] );
				
				$query = $this->db_user->query( "SELECT factuur_id, bedrag_incl FROM facturen WHERE deleted = 0 AND factuur_nr = '$factuur_nr' LIMIT 1" );
				$factuur = DBhelper::toRow( $query, 'NULL' );
				
				$auto_koppeling = 0;
				$verwerkt = 0;
				//boeken
				if( $factuur['bedrag_incl'] == abs($row['bedrag']) )
				{
					$transactie = new Transactie( $row['transactie_id'] );
					$transactie->koppelFactuur( $factuur['factuur_id'], 'voorfinanciering', $factuur['bedrag_incl'] );
					
					$verwerkt = 1;
					$auto_koppeling = 1;
				}
				
				$this->db_user->query( "UPDATE bank_transacties SET auto_koppeling = '$auto_koppeling', verwerkt = '$verwerkt' WHERE transactie_id = ".$row['transactie_id']." LIMIT 1" );
			}
		}
		
		//enkele betalingen
		$sql = "SELECT transactie_id, bedrag, bank_transactiebestanden.grekening, omschrijving, inlener_id
				FROM bank_transacties
				LEFT JOIN bank_transactiebestanden ON bank_transacties.bestand_id = bank_transactiebestanden.bestand_id
				WHERE bank_transacties.deleted = 0 AND inlener_id IS NOT NULL AND verwerkt = 0 AND auto_koppeling IS NULL ";
		
		$query = $this->db_user->query( $sql );
		
		if( $query->num_rows() > 0 )
		{
			foreach( $query->result_array() as $row )
			{
				$omschrijving = str_replace( array('.','2021', $row['inlener_id']), '', $row['omschrijving']);
				preg_match_all( '!\d{4,}!', $omschrijving, $matches );

				if(count($matches[0]) == 1)
				{
					$factuur_nr = $matches[0][0];
					$koppel = false;
					$verwerkt = 0;
					$auto_koppeling = 0;
					
					$query = $this->db_user->query( "SELECT factuur_id, bedrag_incl, bedrag_grekening, (bedrag_incl - bedrag_grekening) AS bedrag_vrij FROM facturen WHERE inlener_id = '" . $row['inlener_id'] . "' AND deleted = 0 AND factuur_nr = '$factuur_nr' LIMIT 1" );
					$factuur = DBhelper::toRow( $query, 'NULL' );
					
					if( $row['grekening'] == 0 )
					{
						if( abs(($factuur['bedrag_vrij']-$row['bedrag'])/$row['bedrag']) < 0.00001 )
							$koppel = true;
					}
					if( $row['grekening'] == 1 )
					{
						if( abs(($factuur['bedrag_grekening']-$row['bedrag'])/$row['bedrag']) < 0.00001 && $factuur['bedrag_grekening'] > 0 )
							$koppel = true;
					}
					
					if($koppel)
					{
						$transactie = new Transactie( $row['transactie_id'] );
						$transactie->koppelFactuur( $factuur['factuur_id'], 'betaling', $row['bedrag'] );
						
						$verwerkt = 1;
						$auto_koppeling = 1;
					}
					
					$this->db_user->query( "UPDATE bank_transacties SET auto_koppeling = '$auto_koppeling', verwerkt = '$verwerkt' WHERE transactie_id = ".$row['transactie_id']." LIMIT 1" );
				}
			}
		}
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