<?php

namespace models\debiteurbeheer;

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

class OpenstaandeFacturen extends Connector
{
	protected $_totaal = 0;
	protected $_totaal_uitzender = array();

	protected $_tijdvak = NULL;
	protected $_jaar = NULL;
	protected $_periode = NULL;
	
	protected $_uitzender_id = NULL;
	protected $_inlener_id = NULL;
	
	protected $_sort_datum_desc = false;
	protected $_sort_datum_asc = false;
	protected $_sort_bedrag_desc = false;
	protected $_sort_bedrag_asc = false;
	
	protected $_group_inlener = false;
	protected $_group_uitzender = false;
	
	protected $_filter_factoring = false;
	protected $_filter_geen_factoring = false;
	protected $__filter_wachtrij = false;
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
	 *
	 * alleen verkoopfacturen
	 *
	 */
	public function eigenvermogen() :?array
	{
		$sql = "SELECT ib.inlener_id, ib.bedrijfsnaam, facturen.factuur_id, facturen.factuur_nr, facturen.bedrag_incl, facturen.factuur_datum, facturen.verval_datum, facturen.jaar, facturen.periode, facturen.bedrag_openstaand,
       					factoring_facturen_regels.factuur_nr AS koppel_nr, DATEDIFF(NOW(),facturen.verval_datum) AS betaaltermijn, facturen.uitzender_id
				FROM facturen
				LEFT JOIN inleners_bedrijfsgegevens ib ON facturen.inlener_id = ib.inlener_id
				LEFT JOIN factoring_facturen_regels ON facturen.factuur_nr = factoring_facturen_regels.factuur_nr
				WHERE ib.deleted = 0 AND facturen.deleted = 0 AND marge = 0 AND concept = 0 AND voldaan = 0 AND factuur_datum <= '2021-06-18' AND jaar > 2020
				AND factoring_facturen_regels.factuur_nr IS NULL AND bedrag_voorfinanciering IS NULL AND bedrag_incl != 0 AND (facturen.inlener_id NOT IN(3078) || DATEDIFF(NOW(),facturen.verval_datum) < 0 )
				ORDER BY bedrijfsnaam, facturen.factuur_nr ASC";
		
		$query = $this->db_user->query( $sql );
		
		
		
		foreach( $query->result_array() as $row )
		{
			if( !isset($data[$row['inlener_id']]['totaal']))
			{
				$data[$row['inlener_id']]['totaal'] = 0;
				$data[$row['inlener_id']]['inlener'] = $row['bedrijfsnaam'];
				$data[$row['inlener_id']]['inlener_id'] = $row['inlener_id'];
			}
			if(!isset($this->_totaal_uitzender[$row['uitzender_id']]))
				$this->_totaal_uitzender[$row['uitzender_id']] = 0;
			
			$data[$row['inlener_id']]['facturen'][] = $row;


			if( $row['bedrag_incl'] < 0 )
			{
				$this->_totaal_uitzender[$row['uitzender_id']] -= $row['bedrag_openstaand'];
				$this->_totaal -= $row['bedrag_openstaand'];
				$data[$row['inlener_id']]['totaal'] -= $row['bedrag_openstaand'];
			}
			else
			{
				$this->_totaal_uitzender[$row['uitzender_id']] += $row['bedrag_openstaand'];
				$this->_totaal += $row['bedrag_openstaand'];
				$data[$row['inlener_id']]['totaal'] += $row['bedrag_openstaand'];
			}
		}
		
		return $data;
	}
	
	
	public function totaaleigenvermogen()
	{
		return $this->_totaal;
	}


	public function totaaluitzenders()
	{
		return $this->_totaal_uitzender;
	}


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * alleen verkoopfacturen
	 */
	public function facturen( $param ) :?array
	{
		$sql = "SELECT facturen.*, (facturen.bedrag_incl - facturen.bedrag_grekening) AS bedrag_lopend, DATEDIFF(voldaan_op,factuur_datum) AS opengestaan, DATEDIFF(verval_datum,factuur_datum) AS betaaltermijn,
       					DATEDIFF(NOW(),facturen.verval_datum) AS verval_dagen, inleners_bedrijfsgegevens.bedrijfsnaam AS inlener
				FROM facturen
				LEFT JOIN inleners_bedrijfsgegevens ON inleners_bedrijfsgegevens.inlener_id = facturen.inlener_id
				WHERE facturen.deleted = 0 AND facturen.concept = 0 AND facturen.marge = 0
					AND inleners_bedrijfsgegevens.deleted = 0 AND voldaan = 0 LIMIT 50";
		
		$query = $this->db_user->query( $sql );
		return DBhelper::toArray( $query, 'factuur_id', 'NULL' );
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
				WHERE inleners_bedrijfsgegevens.deleted = 0 AND inleners_factuurgegevens.deleted = 0 AND facturen.deleted = 0 AND facturen.concept = 0 AND facturen.marge = 0";
				
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
			if(isset($betalingen) && is_array($betalingen))
			{
				foreach ($betalingen as $factuur_id => $categorie_id)
				{
					if (isset($data[$factuur_id]) && $this->_filter_factuur_aangekocht == 0)
					{
						unset($data[$factuur_id]);
					}
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