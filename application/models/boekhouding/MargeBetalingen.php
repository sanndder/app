<?php

namespace models\boekhouding;

use models\Connector;
use models\file\Sepa;
use models\users\UserGroup;
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
	private $_betalingen = array();
	private $_totaal_bedrag = 0;
	private $_aantal_betalingen = 0;
	
	//array met ID's voor update database
	private $_facturen_in_sepa = array();

	
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
	 * sepabestanden ophalen
	 *
	 */
	public function sepaBestanden( $limit = 20 ) :?array
	{
		$query = $this->db_user->query( "SELECT * FROM marge_export_sepa WHERE  deleted = 0 ORDER BY timestamp DESC LIMIT $limit" );
		$data = DBhelper::toArray( $query, 'file_id', 'NULL' );
		
		$data = UserGroup::findUserNames($data);
		return $data;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * sepabestand ophalen
	 *
	 */
	public function sepaBestand( $file_id = 20 ) :?array
	{
		$query = $this->db_user->query( "SELECT * FROM marge_export_sepa WHERE file_id = $file_id AND deleted = 0 LIMIT 1" );
		return DBhelper::toRow( $query, 'NULL' );
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
				WHERE facturen.marge = 1 AND facturen.deleted = 0 AND facturen.concept = 0 AND facturen.bedrag_incl != 0
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
	 * facturen naar betalingen
	 *
	 */
	private function facturenNaarBetalingen( $facturen )
	{
		//hooste niveau is de periodes
		foreach( $facturen as $tijdvak => $tijdvakArray )
		{
			//jaar, verder niet van belang in de omschrijving
			foreach( $tijdvakArray as $jaar => $jaarArray )
			{
				//periode, wel in de omschrijving
				foreach( $jaarArray as $periode => $periodeArray )
				{
					//setup naam tijdvak
					if( $tijdvak == 'w' ) $tijdvakNaam = 'week ' . $periode;
					if( $tijdvak == '4w' ) $tijdvakNaam = 'periode ' . $periode;
					if( $tijdvak == 'm' ) $tijdvakNaam = Tijdvak::maandNaam( $periode );
					
					//per uitzender de regels maken
					foreach( $periodeArray as $uitzender_id => $uitzenderArray )
					{
						$iban = $uitzenderArray['iban'];
						$uitzender = $uitzenderArray['uitzender'];
						$i = 1; //telling op 1
						
						//extra check of er wel facturen zijn
						if( isset($uitzenderArray['facturen']) )
						{
							foreach( $uitzenderArray['facturen'] as $factuur_id => $factuur )
							{
								//default key
								$key = $uitzender_id . '-' . $i;
								
								//kijken of er nog ruimte in de omschijving is
								$extra_tekst = ' ' . $factuur['factuur_nr'];
								if( isset($this->_betalingen[$key]['omschrijving']) && (strlen($this->_betalingen[$key]['omschrijving']) + strlen($extra_tekst)) > 138 )
								{
									$i++;
									$key = $uitzender_id . '-' . $i;
								}
								
								//init
								if( !isset( $this->_betalingen[$key] ) )
								{
									$this->_betalingen[$key]['iban'] = $iban;
									$this->_betalingen[$key]['naam'] = $uitzender;
									$this->_betalingen[$key]['bedrag'] = 0;
									$this->_betalingen[$key]['omschrijving'] = 'marge ' . $tijdvakNaam ;
								}
								
								$bedrag = $factuur['bedrag_incl'] * -1; //bedrag omdraaien
								
								$this->_betalingen[$key]['omschrijving'] = $this->_betalingen[$key]['omschrijving'] . $extra_tekst;
								$this->_betalingen[$key]['bedrag'] += $bedrag;
								
								//voor het opslaan
								$this->_facturen_in_sepa[$key]['bedrag'] = $this->_betalingen[$key]['bedrag'];
								$this->_facturen_in_sepa[$key]['factuur'][$factuur_id] = 1;
							}
						}
					}
				}
			}
		}
		
		
		//nu controle of alle betalingen > 0 zijn
		foreach( $this->_betalingen as $id => $betaling )
		{
			//betaling is OK
			if( $betaling['bedrag'] > 0 )
			{
				$this->_aantal_betalingen++;
				$this->_totaal_bedrag +=  $betaling['bedrag'];
			}
			//mag niet mee
			else
			{
				unset($this->_betalingen[$id]);
				unset($this->_facturen_in_sepa[$id]);
			}
		}
	}


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * facturen op voldaan
	 *
	 */
	public function facturenVoldaan( $factuur_ids )
	{
		$facturen = $this->openstaandeMargefacturen( $factuur_ids );
		
		//geen facturen
		if( count($facturen) == 0 )
		{
			$this->_error[] = 'Geen margefacturen geselecteerd';
			return false;
		}

		//marge op voldaan
		$this->db_user->query( "UPDATE facturen SET voldaan = 1, voldaan_op = NOW() WHERE marge = 1 AND deleted = 0 AND factuur_id IN (".array_keys_to_string($factuur_ids).")");
		
		return true;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * sepa maken voor export
	 *
	 */
	public function generateSepa( $factuur_ids )
	{
		$facturen = $this->openstaandeMargefacturen( $factuur_ids );
		
		//geen facturen
		if( count($facturen) == 0 )
		{
			$this->_error[] = 'Geen margefacturen geselecteerd';
			return false;
		}
		
		//betalingen maken
		$this->facturenNaarBetalingen( $facturen );
		
		//check of er geldige betalingen zijn
		if( count($this->_betalingen) == 0 )
		{
			$this->_error[] = 'Selectie heeft niet geleid tot betalingen';
			return false;
		}
		
		//start sepa
		$sepa = new Sepa();
		$sepa->enkelvoudig()->uniqueID( 'marge' )->totaalbedrag( $this->_totaal_bedrag )->totaalentries( $this->_aantal_betalingen );
		$sepa->initSepa();
		
		//nu de betalingen
		foreach( $this->_betalingen as $key => $betaling )
			$sepa->addBetaling( $betaling['naam'], $betaling['bedrag'], $betaling['iban'], $betaling['omschrijving'] );
		
		//bestand maken
		$file_dir = 'margesepa/' . date('Y');
		$file_name = 'marge_sepa_' . date('Ymd_his') . '_' . uniqid(). '.xml';
		$file_name_display = 'marge_sepa_' . date('Ymd_his') . '.xml';
		
		//bestand aanmaken
		if( $sepa->sepaToFile( $file_dir, $file_name, $file_name_display ) )
		{
			//facturen op betaald
			foreach( $this->_facturen_in_sepa as $array )
			{
				foreach( $array['factuur'] as $factuur_id => $val )
				{
					$insert_batch[$factuur_id]['factuur_id'] = $factuur_id;
					$insert_batch[$factuur_id]['file_id'] = $sepa->sepaID();
				}
			}
			
			$this->db_user->insert_batch( 'marge_export_sepa_facturen', $insert_batch );
			
			//marge op voldaan
			$this->db_user->query( "UPDATE facturen SET voldaan = 1, voldaan_op = NOW() WHERE marge = 1 AND deleted = 0 AND factuur_id IN (".array_keys_to_string($insert_batch).")");
			
			if( $this->db_user->affected_rows() < 1 )
			{
				$this->_error[] = 'Fout bij updaten status facturen';
				return false;
			}
			
			return true;
			
		}
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