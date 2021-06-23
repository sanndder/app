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

class VoorfinancieringOverzicht extends Connector
{
	
	private $_openstaande_financiering = 0;
	private $_terug_te_betalen = 0;
	private $_facturen = NULL;
	
	protected $_error = NULL;
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 * /*
	 * constructor
	 */
	public function __construct()
	{
		//call parent constructor for connecting to database
		parent::__construct();
		
		//facturen in de voorfinanciering
		$this->_getFacturen();
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * facturen in de voorfinanciering ophalen
	 *
	 */
	private function _getFacturen()
	{
		$sql = "SELECT facturen.factuur_id, facturen.factuur_nr, facturen.factuur_datum,  facturen.factuur_datum, facturen.verval_datum, facturen.tijdvak, facturen.jaar, facturen.periode, facturen.bedrag_incl,
       				facturen.bedrag_voorfinanciering, facturen.bedrag_openstaand, facturen.inlener_id, facturen.voldaan,
       				inleners_bedrijfsgegevens.bedrijfsnaam AS inlener,  DATEDIFF(NOW(),facturen.verval_datum) AS verval_dagen
				FROM facturen
				LEFT JOIN inleners_bedrijfsgegevens ON facturen.inlener_id = inleners_bedrijfsgegevens.inlener_id
				WHERE facturen.deleted = 0 AND facturen.concept = 0 AND facturen.bedrag_voorfinanciering IS NOT NULL
				AND inleners_bedrijfsgegevens.deleted = 0 AND facturen.jaar > 2020
				ORDER BY inlener, factuur_nr";
		
		$query = $this->db_user->query( $sql );
		
		if( $query->num_rows() == 0 )
			return NULL;
		
		foreach( $query->result_array() as $row )
		{
			if( $row['voldaan'] == 0 )
			{
				$this->_facturen[$row['voldaan']][$row['factuur_id']] = $row;
				$this->_openstaande_financiering += $row['bedrag_voorfinanciering'];
			}
			if( $row['voldaan'] == 1 )
			{
				if( $row['voldaan'] == 1 && $row['bedrag_voorfinanciering'] != 0 )
				{
					$this->_openstaande_financiering += $row['bedrag_voorfinanciering'];
					$this->_facturen[$row['voldaan']][$row['factuur_id']] = $row;
					$this->_terug_te_betalen += $row['bedrag_voorfinanciering'];
				}
			}
		}
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * hoeveel moet er terug
	 *
	 */
	public function facturen()
	{
		return $this->_facturen;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * hoeveel moet er terug
	 *
	 */
	public function terugtebetalen()
	{
		return $this->_terug_te_betalen;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * hoeveel moet er terug
	 *
	 */
	public function openstaandefinanciering()
	{
		return $this->_openstaande_financiering;
	}
}
	
?>