<?php

namespace models\boekhouding;

use models\Connector;
use models\file\Sepa;
use models\utils\DBhelper;
use models\utils\Tijdvak;

if (!defined('BASEPATH'))exit('No direct script access allowed');


/*
 *Omzet data
 *
 *
 */

class MargeBetalingen extends Connector
{
	protected $_error = NULL;

	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * constructor
	 */
	public function __construct()
	{
		//call parent constructor for connecting to database
		parent::__construct();

	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * alle openstaande facturen gegroepeerd per uitzender
	 *
	 */
	public function openstaandeMargefacturen( $factuur_ids = NULL )
	{
		$sql = "SELECT uitzenders_bedrijfsgegevens.bedrijfsnaam, inleners_bedrijfsgegevens.bedrijfsnaam AS inlener,
      			facturen.factuur_id, facturen.tijdvak, facturen.periode, facturen.jaar, facturen.uitzender_id, facturen.bedrag_incl, factuur_nr, uitzenders_factuurgegevens.iban
				FROM facturen
				LEFT JOIN uitzenders_bedrijfsgegevens ON facturen.uitzender_id = uitzenders_bedrijfsgegevens.uitzender_id
				LEFT JOIN uitzenders_factuurgegevens ON uitzenders_factuurgegevens.uitzender_id = facturen.uitzender_id
				LEFT JOIN inleners_bedrijfsgegevens ON facturen.inlener_id = inleners_bedrijfsgegevens.inlener_id
				WHERE facturen.marge = 1 AND facturen.deleted = 0 AND facturen.concept = 0
				AND facturen.voldaan = 0 AND uitzenders_bedrijfsgegevens.deleted = 0 AND inleners_bedrijfsgegevens.deleted = 0 AND uitzenders_factuurgegevens.deleted = 0 ";
		
		if( $factuur_ids !== NULL && count($factuur_ids) > 0 )
			$sql .= " AND factuur_id IN (".array_keys_to_string($factuur_ids).") ";

		$sql .= " ORDER BY jaar DESC, periode DESC";
		
		$query = $this->db_user->query( $sql );
		
		if( $query->num_rows() == 0 )
			return array();
		
		foreach( $query->result_array() as $row )
		{
			$data[$row['tijdvak']][$row['jaar']][$row['periode']][$row['uitzender_id']]['uitzender'] = $row['bedrijfsnaam'];
			$data[$row['tijdvak']][$row['jaar']][$row['periode']][$row['uitzender_id']]['iban'] = $row['iban'];
			$data[$row['tijdvak']][$row['jaar']][$row['periode']][$row['uitzender_id']]['facturen'][$row['factuur_id']] = $row;
		}
		
		return $data;
	}

	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * sepa maken voor export
	 *
	 */
	public function generateSepa( $factuur_ids )
	{
		$facturen = $this->openstaandeMargefacturen( $factuur_ids );
		
		if( count($facturen) == 0 )
		{
			$this->_error[] = 'Geen margefacturen geselecteerd';
			return false;
		}
		
		//start sepa
		$sepa = new Sepa();
		$sepa->enkelvoudig()->uniqueID( 'marge' );
		$sepa->initSepa();
		
		//nu de betalingen
		$sepa->addBetaling( 'H.S. Meijering', 0.01, 'NL96SNSB0821159593', 'test' );
		
		//totalen invoeren
		$sepa->finalizeSepa();
		
		show($facturen);
		
		return false;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
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