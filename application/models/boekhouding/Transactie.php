<?php

namespace models\boekhouding;

use models\Connector;
use models\facturatie\Factuur;
use models\facturatie\FactuurBetaling;
use models\inleners\InlenerGroup;
use models\uitzenders\Uitzender;
use models\uitzenders\UitzenderGroup;
use models\utils\DBhelper;

if( !defined( 'BASEPATH' ) )
	exit( 'No direct script access allowed' );

/*
 * Hoofdclass voor invoer verloning
 *
 *
 */

class Transactie extends Connector
{
	private $_transactie_id = NULL;
	private $_transactie = NULL;
	private $_categorie_id = NULL;
	protected $_error = NULL;
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 * /*
	 * constructor
	 */
	public function __construct( $id = NULL )
	{
		//call parent constructor for connecting to database
		parent::__construct();
		
		if( $id !== NULL && intval( $id ) != 0 )
			$this->setId( $id );
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * Details
	 *
	 */
	public function details()
	{
		//onverwerkt bedrag uitrekenen
		$this->_updateOnverwerkteBedragen();
		
		$sql = "SELECT bank_transacties.*, inleners_bedrijfsgegevens.bedrijfsnaam AS inlener, btc.factuur AS cat_factuur, btc.factoring AS cat_factoring, bank_transactiebestanden.grekening
				FROM bank_transacties
				LEFT JOIN bank_transactiebestanden ON bank_transactiebestanden.bestand_id = bank_transacties.bestand_id
				LEFT JOIN inleners_bedrijfsgegevens ON inleners_bedrijfsgegevens.inlener_id = bank_transacties.inlener_id
				LEFT JOIN bank_transacties_categorien btc ON bank_transacties.categorie_id = btc.categorie_id
				WHERE bank_transacties.transactie_id = $this->_transactie_id AND bank_transacties.deleted = 0 
					AND ( inleners_bedrijfsgegevens.deleted = 0 OR bank_transacties.inlener_id IS NULL )
				LIMIT 1";
		
		$query = $this->db_user->query( $sql );
		$this->_transactie = DBhelper::toRow( $query, 'NULL' );

		//afbreken wanneer niet gevonden
		if( $this->_transactie === NULL )
			return $this->_transactie;
		
		//volledig verwerkt?
		if( $this->_transactie['bedrag_onverwerkt'] == 0 )
		{
			$this->_transactie['verwerkt'] = 1;
			$this->db_user->query( "UPDATE bank_transacties SET verwerkt = 1 WHERE transactie_id = $this->_transactie_id LIMIT 1" );
		}
		else
		{
			$this->_transactie['verwerkt'] = 0;
			$this->db_user->query( "UPDATE bank_transacties SET verwerkt = 0 WHERE transactie_id = $this->_transactie_id LIMIT 1" );
		}
		
		//datum al aaanpassen voor javascript
		$this->_transactie['datum_format'] = reverseDate( $this->_transactie['datum'] );
		$this->_categorie_id = $this->_transactie['categorie_id'];
		$this->_transactie['factuur_nrs'] = '';
		$this->_transactie['suggest_inlener_id'] = NULL;
		
		//factuurnummers uit de omschrijving vissen
		if( $this->_transactie['omschrijving'] !== NULL && $this->_transactie['omschrijving'] != '' )
		{
			if( strpos( $this->_transactie['omschrijving'], 'oorfina') > 0 )
			{
				$omschrijving = str_replace( array('.','2021'), '', $this->_transactie['omschrijving']);
				preg_match_all( '!\d{3,}!', $omschrijving, $matches );
				$this->_transactie['factuur_nrs'] = implode( ',', $matches[0] );
			}
			else
			{
				$omschrijving = str_replace( array('.','2021',$this->_transactie['inlener_id']), '', $this->_transactie['omschrijving']);
				preg_match_all( '!\d+!', $omschrijving, $matches );
				$this->_transactie['factuur_nrs'] = implode( ',', $matches[0] );
			}
			
			
			/*
			$parts = explode( ' ', $this->_transactie['omschrijving'] );
			if( is_array($parts) && count($parts) > 0 )
			{
				foreach( $parts as $word )
				{
					if( is_numeric($word) )
						$nr[] = $word;
				}
				
				$this->_transactie['factuur_nrs'] = implode(',', $nr);
			}*/
		}
		
		//zoek inlener erbij
		if( $this->_transactie['inlener_id'] === NULL )
			$this->_transactie['suggest_inlener_id'] = $this->_zoekInlener();
		
		return $this->_transactie;
	}


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * onverwerkt bedrag updaten
	 *
	 */
	private function _updateOnverwerkteBedragen()
	{
		$sql = "SELECT SUM(bedrag) AS bedrag FROM facturen_betalingen
    			LEFT JOIN facturen_betalingen_categorien ON facturen_betalingen_categorien.categorie_id = facturen_betalingen.categorie_id
				WHERE facturen_betalingen.deleted = 0 AND facturen_betalingen_categorien.deleted = 0
				AND facturen_betalingen.transactie_id = $this->_transactie_id";
		
		$query = $this->db_user->query( $sql );
		if( $query->num_rows() == 0 )
			$row['bedrag'] = 0;
		else
			$row = $query->row_array();

		$verwerkt = $row['bedrag'];
		if($verwerkt === NULL )
			$verwerkt = 0;
			
		$this->db_user->query( "UPDATE bank_transacties SET bedrag_onverwerkt = (bedrag - $verwerkt) WHERE transactie_id = $this->_transactie_id LIMIT 1" );
		
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * inlener bij naam zoeken
	 *
	 */
	private function _zoekInlener()
	{
		$zoek = str_replace( array('BV'), '', $this->_transactie['relatie']);
		
		$query = $this->db_user->query( "SELECT inlener_id, bedrijfsnaam FROM inleners_bedrijfsgegevens WHERE deleted = 0 AND bedrijfsnaam LIKE '%".$zoek."%' LIMIT 1" );
		return DBhelper::toRow( $query, 'NULL', 'inlener_id' );
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * facturen die bij transactie
	 *
	 */
	public function facturen()
	{
		$sql = "SELECT facturen_betalingen.bedrag, facturen.factuur_id, facturen.factuur_nr, facturen.marge, facturen.bedrag_incl, facturen.bedrag_openstaand,
       					inleners_bedrijfsgegevens.bedrijfsnaam AS inlener, uitzenders_bedrijfsgegevens.bedrijfsnaam AS uitzender
				FROM facturen_betalingen
					LEFT JOIN facturen ON facturen_betalingen.factuur_id = facturen.factuur_id
					LEFT JOIN inleners_bedrijfsgegevens ON facturen.inlener_id = inleners_bedrijfsgegevens.inlener_id
					LEFT JOIN uitzenders_bedrijfsgegevens ON facturen.uitzender_id = uitzenders_bedrijfsgegevens.uitzender_id
				WHERE facturen_betalingen.transactie_id = $this->_transactie_id AND facturen_betalingen.deleted = 0";
		
		$query = $this->db_user->query( $sql );
		
		return DBhelper::toArray( $query, 'factuur_id', 'NULL' );
		
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * probeer transactie te koppelen
	 *
	 */
/*
	private function _searchKoppeling()
	{
		//koppeling maar 1x proberen
		if( $this->_transactie['auto_koppeling'] !== NULL )
			return false;

		//salaris betaling?
		if( strpos( $this->_transactie['omschrijving'], 'salaris' ) !== false  )
		{
			$update['categorie_id'] = 5;
			$update['auto_koppeling'] = 1;
			
			$this->db_user->where( 'transactie_id', $this->_transactie_id );
			$this->db_user->update( 'bank_transacties', $update );
			if( $this->db_user->affected_rows() != -1 )
			{
				$this->_categorie_id = $this->_transactie['categorie_id'] = $update['categorie_id'];
				return true;
			}
		}
		//geen salaris
		$query = $this->db_user->query( "SELECT relatie_iban, uitzender_id, inlener_id, categorie_id FROM bank_transacties_koppeling WHERE deleted = 0 AND relatie_iban = ? ", array( $this->_transactie['relatie_iban'] ) );
		
		//niks gevonden
		if( $query->num_rows() == 0 )
			return false;
		
		$categorien = [];
		$inlener = NULL;
		$uitzender = NULL;
		
		foreach( $query->result_array() as $row )
		{
			$categorien[$row['categorie_id']] = 1;
			if( $row['uitzender_id'] !== NULL ) $uitzender[$row['uitzender_id']] = 1;
			if( $row['inlener_id'] !== NULL ) $inlener[$row['inlener_id']] = 1;
		}
		
		//relatie koppelen
		if( $uitzender !== NULL && count($uitzender) == 1 )
		{
			$updateU['uitzender_id'] = key( $uitzender );
			$this->db_user->where( 'transactie_id', $this->_transactie_id );
			$this->db_user->update( 'bank_transacties', $updateU );
			
			$this->_transactie['uitzender_id'] = $updateU['uitzender_id'];
			$this->_transactie['uitzender'] = UitzenderGroup::bedrijfsnaam($updateU['uitzender_id']);
		}
		
		if( $inlener !== NULL && count($inlener) == 1 )
		{
			$updateI['inlener_id'] = key( $inlener );
			$this->db_user->where( 'transactie_id', $this->_transactie_id );
			$this->db_user->update( 'bank_transacties', $updateI );
			
			$this->_transactie['inlener_id'] = $updateI['inlener_id'];
			$this->_transactie['inlener'] = InlenerGroup::bedrijfsnaam($updateI['inlener_id']);
		}
		
		//wannneer er maar 1 categorie is, die instellen
		if( count( $categorien ) != 1 )
			return false;
		
		$update['categorie_id'] = key( $categorien );
		$update['auto_koppeling'] = 1;
		
		$this->db_user->where( 'transactie_id', $this->_transactie_id );
		$this->db_user->update( 'bank_transacties', $update );
		
		if( $this->db_user->affected_rows() != -1 )
		{
			$this->_categorie_id = $this->_transactie['categorie_id'] = $update['categorie_id'];
			
			//wanneer we de categorie weten verder kijken
			$this->koppelTransactie();
			
			return true;
		}
		
		return false;
	}*/
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * transacties negeren
	 *
	 */
	public function ignore( $all = false )
	{
		//details laden
		$this->details();
	
		$update['hidden'] = 1;
		$this->db_user->where( 'transactie_id', $this->_transactie_id );
		$this->db_user->update( 'bank_transacties', $update );
		
		$this->_log( 'Transactie genegeerd' );
		
		if( $all == 'false' )
		{
			$response['status'] = 'success';
		}
		else
		{
			//aanmaken in negeerlijst
			$insert['action'] = 'ignore';
			$insert['relatie'] = $this->_transactie['relatie'];
			$insert['relatie_iban'] = $this->_transactie['relatie_iban'];
			$this->db_user->insert( 'bank_transacties_koppeling', $insert );
			
			$response['status'] = 'success';
			
			$this->_updateIgnoreTransacties();
		}
		
		return $response;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * update transacties na ignore all
	 *
	 */
	public function _updateIgnoreTransacties()
	{
		$this->db_user->query( "UPDATE bank_transacties SET hidden = 1 WHERE deleted = 0 AND verwerkt = 0 AND relatie_iban = '".$this->_transactie['relatie_iban']."'" );
		$this->_log( 'Alle transacties met IBAN '.$this->_transactie['relatie_iban'].' genegeerd. ('. $this->db_user->affected_rows() . ' transacties)' );
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * iban aan inlener koppelen
	 *
	 */
	public function linkIban( $inlener_id = NULL )
	{
		if( $inlener_id === NULL )
		{
			$response['status'] = 'error';
			$response['error'] = 'Geen inlener geselecteerd';
			return $response;
		}
		
		//details laden
		$this->details();
		
		//transactie updaten
		$update['inlener_id'] = $inlener_id;
		$this->db_user->where( 'transactie_id', $this->_transactie_id );
		$this->db_user->update( 'bank_transacties', $update );
		
		//koppeling opslaan
		$insert['action'] = 'betaling';
		$insert['inlener_id'] = $inlener_id;
		$insert['relatie'] = $this->_transactie['relatie'];
		$insert['relatie_iban'] = $this->_transactie['relatie_iban'];
		$this->db_user->insert( 'bank_transacties_koppeling', $insert );
		
		$this->_log( 'Inlener '.$inlener_id.' gekoppeld aan IBAN' . $this->_transactie['relatie_iban'] );
		
		$this->_updateInlenerTransacties( $inlener_id );
		
		$response['status'] = 'success';
		return $response;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * koppeling weer weghalen
	 *
	 */
	public function unlinkIban( $inlener_id = NULL )
	{
		//details laden
		$this->details();
		
		//transactie updaten
		$update['inlener_id'] = NULL;
		$this->db_user->where( 'transactie_id', $this->_transactie_id );
		$this->db_user->update( 'bank_transacties', $update );
		
		$this->_log( 'Koppeling '.$inlener_id.' aan IBAN' . $this->_transactie['relatie_iban'] . ' verwijderd' );
		
		$this->db_user->query( "UPDATE bank_transacties_koppeling SET deleted = 1, deleted_on = NOW(), deleted_by = " . $this->user->user_id . " WHERE inlener_id = $inlener_id AND deleted = 0 LIMIT 1" );
		
		$this->_updateInlenerTransacties( NULL );
		
		if( $this->db_user->affected_rows() != -1 )
		{
			$response['status'] = 'success';
			return $response;
		}
		
		$response['status'] = 'error';
		$response['error'] = 'Koppeling kon niet worden verwijderd';
		return $response;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * alle transactie nalopen voor koppeling
	 *
	 */
	private function _updateInlenerTransacties( $inlener_id )
	{
		if( $inlener_id === NULL )
		{
			$this->db_user->query( "UPDATE bank_transacties SET inlener_id = ?  WHERE deleted = 0 AND verwerkt = 0 AND relatie_iban = '" . $this->_transactie['relatie_iban'] . "'", array($inlener_id) );
			$this->_log( 'Alle onverwerkte transacties met IBAN '.$this->_transactie['relatie_iban'].' verwijder van inlener '.$inlener_id.'. ('. $this->db_user->affected_rows() . ' transacties)' );
		}
		else
		{
			$this->db_user->query( "UPDATE bank_transacties SET inlener_id = $inlener_id WHERE deleted = 0 AND verwerkt = 0 AND relatie_iban = '" . $this->_transactie['relatie_iban'] . "'" );
			$this->_log( 'Alle transacties met IBAN '.$this->_transactie['relatie_iban'].' gekoppeld aan inlener '.$inlener_id.'. ('. $this->db_user->affected_rows() . ' transacties)' );
			
		}
	}


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * facturen aan bij transactie verwijderen
	 *
	 */
	public function ontkoppelFactuur( $factuur_id )
	{
		$query = $this->db_user->query( "SELECT id FROM facturen_betalingen WHERE factuur_id = ".intval($factuur_id)." AND transactie_id = $this->_transactie_id AND deleted = 0 LIMIT 1" );
		$betaling_id = DBhelper::toRow( $query, 'NULL', 'id' );
		
		if( $betaling_id !== NULL )
		{
			$factuur = new Factuur( $factuur_id );
			$factuur->delBetaling($betaling_id);
			
			$this->_log( 'Factuur '.$factuur_id.' verwijderd' );
			
			$response['status'] = 'success';
			return $response;
		}
		
		$response['status'] = 'error';
		$response['error'] = 'Koppeling kon niet worden verwijderd';
		return $response;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * facturen aan transactie koppelen
	 *
	 */
	public function koppelFactuur( $factuur_id, $screentype = 'betaling', $afteboeken_bedrag )
	{
		//details laden
		$this->details();

		$factuur = new Factuur( $factuur_id );
		$factuur->betalingen();
		
		//gewone betaling
		$voorfinanciering = false;
		if( $this->_transactie['grekening'] == 1 )
		{
			$open = $factuur->openG();
			$grekening = false;
		}
		else
		{
			$open = $factuur->openVrij();
			$grekening = true;
		}
		
				//voorfinanciering
		if( $screentype == 'voorfinanciering' )
		{
			$grekening = false;
			$voorfinanciering = true;
			$open = $factuur->totaalbedrag();
		}

		//welke bedrag moet er af
		if( $this->_transactie['bedrag_onverwerkt'] >= $open && $open > 0 )
		{
			$totaalOpen = $factuur->openG() + $factuur->openVrij();
		
			if( abs(($totaalOpen-$this->_transactie['bedrag_onverwerkt'])/$this->_transactie['bedrag_onverwerkt']) < 0.0001 )
				$bedrag = $this->_transactie['bedrag_onverwerkt'];
			else
				$bedrag = $open;
		}
		
		else
			$bedrag = $this->_transactie['bedrag_onverwerkt'];
		
		$bedrag = prepareAmountForDatabase($afteboeken_bedrag);
		
		if( $bedrag == 0 )
		{
			$response['status'] = 'error';
			$response['errors'] = 'Bedrag is 0';
		}

		$betaling = new FactuurBetaling();
		$betaling->bedrag( $bedrag )
			->grekening( $grekening )
			->voorfinanciering( $voorfinanciering )
			->datum( reverseDate( $this->_transactie['datum'] ) )
			->tansactieID( $this->_transactie['transactie_id'] );
		
		if( $betaling->valid() )
		{
			$factuur->addBetaling( $betaling );
			$factuur->delBetaling( $factuur->getBetalingID() );
			$this->_log( 'Factuur ' . $factuur_id . ' gekoppeld' );
			$response['status'] = 'success';
			$response['bedrag'] = $bedrag;
		} else
		{
			$response['status'] = 'error';
			$response['factuur_nr'] = $factuur->nr();
			$response['errors'] = $betaling->errors();
		}
		
		return $response;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * probeer transactie te koppelen
	 *
	 */
	public function koppelTransactie()
	{
		//details laden
		if( $this->_transactie === NULL )
			$this->details();
	}
	
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * als on/verwerkt markeren
	 *
	 */
	public function setVerwerkt( $val )
	{
		if( $this->_transactie_id === NULL )
		{
			$this->_error[] = 'Ongeldig ID';
			return false;
		}
		
		$update['verwerkt'] = $val;
		$this->db_user->where( 'transactie_id', $this->_transactie_id );
		$this->db_user->update( 'bank_transacties', $update );
		
		if( $this->db_user->affected_rows() != -1 )
			return true;
		
		return false;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * Opmerking opslaan
	 *
	 */
	public function setOpmerking( $opmerking )
	{
		
		if( $this->_transactie_id === NULL )
		{
			$this->_error[] = 'Ongeldig ID';
			return false;
		}
		
		$update['opmerking'] = $opmerking;
		$this->db_user->where( 'transactie_id', $this->_transactie_id );
		$this->db_user->update( 'bank_transacties', $update );
		
		if( $this->db_user->affected_rows() != -1 )
			return true;
		
		return false;
	}

	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * Set ID
	 *
	 */
	public function setId( $id )
	{
		return $this->_transactie_id = intval( $id );
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * log  actie
	 *
	 */
	private function _log( $action = NULL, $info = NULL ): void
	{
		if( $action === NULL )
			return;
		
		$insert['transactie_id'] = $this->_transactie_id;
		$insert['action'] = $action;
		//$insert['info'] = $info;
		$insert['user_id'] = $this->user->id;
		
		$this->db_user->insert( 'bank_transacties_log', $insert );
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 * /*
	 * Toon errors
	 * @return array|boolean
	 */
	public function errors()
	{
		//output for debug
		if( isset( $_GET['debug'] ) )
		{
			if( $this->_error === NULL )
				show( 'Geen errors' );
			else
				show( $this->_error );
		}
		
		if( $this->_error === NULL )
			return false;
		
		return $this->_error;
	}
}

?>