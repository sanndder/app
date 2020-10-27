<?php

namespace models\facturatie;

use models\Connector;
use models\file\Pdf;
use models\inleners\Inlener;
use models\pdf\PdfFactuur;
use models\pdf\PdfFactuurMarge;
use models\pdf\PdfFactuurVerkoopUren;
use models\pdf\PdfFactuurZzp;
use models\uitzenders\Uitzender;
use models\utils\DBhelper;
use models\utils\Tijdvak;
use models\werknemers\PlaatsingGroup;
use models\werknemers\WerknemerGroup;
use models\zzp\Zzp;

if( !defined( 'BASEPATH' ) )
	exit( 'No direct script access allowed' );

/*
 * Hoofdclass voor invoer verloning
 *
 *
 */

class FactuurCorrectie extends Connector
{
	protected $_factuur_id = NULL;
	protected $_factuur = NULL;
	protected $_details = NULL;
	protected $_regels = NULL;
	
	protected $_inlener_id = NULL;
	protected $_inlener = NULL;
	protected $_inlener_factuurgegevens = NULL;
	protected $_btw_verleggen = 0;
	protected $_uitzender_id = NULL;
	
	protected $_tijdvak = NULL;
	protected $_jaar = NULL;
	protected $_periode = NULL;
	
	protected $_error = NULL;
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * constructor
	 *
	 */
	public function __construct( $factuur_id = NULL )
	{
		//call parent constructor for connecting to database
		parent::__construct();
		
		if( $factuur_id !== NULL )
			$this->setID( $factuur_id );
		
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * set ID
	 *
	 */
	public function setID( $factuur_id = NULL )
	{
		if( intval( $factuur_id ) != 0 )
			$this->_factuur_id = intval( $factuur_id );
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * get ID
	 *
	 */
	public function ID()
	{
		return $this->_factuur_id;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * get details
	 *
	 */
	public function details()
	{
		if( $this->_factuur_id === NULL )
			return NULL;
		
		$sql = "SELECT * FROM facturen WHERE factuur_id = $this->_factuur_id LIMIT 1";
		$query = $this->db_user->query( $sql );
		
		$this->_details = DBhelper::toRow( $query, 'NULL' );
		return $this->_details;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * get regels
	 *
	 */
	public function regels()
	{
		if( $this->_factuur_id === NULL )
			return NULL;
		
		$sql = "SELECT * FROM facturen_regels WHERE factuur_id = $this->_factuur_id AND deleted = 0";
		$query = $this->db_user->query( $sql );
		
		$this->_regels = DBhelper::toArray( $query, 'regel_id', 'NULL' );
		return $this->_regels;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * save
	 *
	 */
	public function deleteRegel( $regel_id ): bool
	{
		if( $this->delete_row( 'facturen_regels', array( 'regel_id' => $regel_id ) ) )
			return $this->_calTotaal();
		
		$this->_error[] = 'Regel kon niet worden verwijderd (7)';
		return false;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * save
	 *
	 */
	public function set( $data ): bool
	{
		//nog geen factuur
		if( $this->_factuur_id === NULL && isset( $data['uitzender_id'] ) )
		{
			//check uitzender
			if( intval( $data['uitzender_id'] ) == 0 )
			{
				
				$this->_error[] = 'Selecteer een uitzender (3)';
				return false;
			}
			
			//nieuwe factuur
			return $this->_insert( $data );
		}
		
		//updaten
		if( $this->_factuur_id !== NULL )
		{
			//factuur gegevens voor inlener ID
			$this->_factuur = $this->details();
			
			//wanneer inlener, dan gegevens laden
			if( $this->_factuur['inlener_id'] !== NULL )
			{
				$this->_inlener = new Inlener( $this->_factuur['inlener_id'] );
				$this->_inlener_factuurgegevens = $this->_inlener->factuurgegevens();
				$this->_btw_verleggen = $this->_inlener_factuurgegevens['btw_verleggen'];
			}
			
			if( !isset( $data['regels'] ) )
				return $this->_updateDetails( $data );
			else
				return $this->_updateRegels( $data );
		}
		
		$this->_error[] = 'Factuur kon niet worden opgeslagen (4)';
		return false;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * update detials
	 *
	 */
	private function _updateDetails( $data ): bool
	{
		$update['uitzender_id'] = intval( $data['uitzender_id'] );
	
		if( $data['factuur_datum'] != '' )
		{
			$update['factuur_datum'] = reverseDate( $data['factuur_datum'] );
			
			$date = new \DateTime( $update['factuur_datum'] );
			$date->add(new \DateInterval('P30D'));
			$update['verval_datum'] = $date->format('Y-m-d');
		}

		if( $data['tijdvak'] == 'w' || $data['tijdvak'] == '4w' || $data['tijdvak'] == 'm' )
			$update['tijdvak'] = $data['tijdvak'];
		if( $data['jaar'] >= date( 'Y' ) - 1 )
			$update['jaar'] = $data['jaar'];
		if( $data['periode'] > 0 )
			$update['periode'] = $data['periode'];
		if( isset( $data['inlener_id'] ) )
		{
			$update['inlener_id'] = intval( $data['inlener_id'] );
			
			//moet er nog cessie tekst bij
			if( $this->_inlener_factuurgegevens['factoring'] == 1 )
			{
				$query = $this->db_user->query( "SELECT id FROM facturen_cessie_tekst WHERE factuur_id = $this->_factuur_id" );
				
				if( $query->num_rows() == 0 )
				{
					//cessietekst erbij
					$factoring = $this->werkgever->factoring();
					
					$insert_cessie['iban_factoring'] = $factoring['iban'];
					$insert_cessie['cessie_tekst'] = $factoring['cessie_tekst'];
					$insert_cessie['factuur_id'] = $this->_factuur_id;
					$this->db_user->insert( 'facturen_cessie_tekst', $insert_cessie );
				}
			}
		}
		
		$this->db_user->where( 'factuur_id', $this->_factuur_id );
		$this->db_user->update( 'facturen', $update );
		
		if( $this->db_user->affected_rows() != -1 )
			return true;
		
		$this->_error[] = 'Fout bij updaten details (5)';
		return false;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * update
	 *
	 */
	private function _updateRegels( $data ): bool
	{
		//insert
		if( isset( $data['regels'][0]['omschrijving'] ) && strlen( $data['regels'][0]['omschrijving'] ) > 0 )
		{
			$insert['factuur_id'] = $this->_factuur_id;
			$insert['uitkeren_werknemer'] = 1;
			$insert['omschrijving'] = substr( $data['regels'][0]['omschrijving'], 0, 1000 );
			
			$insert['uren_decimaal'] = prepareAmountForDatabase( $data['regels'][0]['uren_decimaal'] );
			$insert['uren_aantal'] = d2h( $insert['uren_decimaal'] );
			
			$insert['verkooptarief'] = prepareAmountForDatabase( $data['regels'][0]['verkooptarief'] );
			$insert['bruto_uurloon'] = prepareAmountForDatabase( $data['regels'][0]['bruto_uurloon'] );
			$insert['factor'] = $data['regels'][0]['factor'];
			$insert['percentage'] = prepareAmountForDatabase( $data['regels'][0]['percentage'] );
			
			//uitrekenen
			$insert['subtotaal_verkoop'] = round( $insert['uren_decimaal'] * $insert['verkooptarief'], 2 );
			$insert['subtotaal_kosten'] = round( $insert['uren_decimaal'] * $insert['bruto_uurloon'] * $insert['factor'] * ( $insert['percentage'] / 100 ), 2 );
			$insert['user_id'] = $this->user->user_id;
			
			$this->db_user->insert( 'facturen_regels', $insert );
			
			unset( $data['regels'][0]['omschrijving'] );
			unset( $data['regels'][0]['bedrag'] );
		}
		
		//update de rest
		if( count( $data['regels'] ) > 0 )
		{
			foreach( $data['regels'] as $regel_id => $arr )
			{
				if( $regel_id > 0 )
				{
					$update['omschrijving'] = substr( $arr['omschrijving'], 0, 1000 );
					
					$update['uren_decimaal'] = prepareAmountForDatabase( $arr['uren_decimaal'] );
					$update['uren_aantal'] = d2h( $update['uren_decimaal'] );
					
					$update['verkooptarief'] = prepareAmountForDatabase( $arr['verkooptarief'] );
					$update['bruto_uurloon'] = prepareAmountForDatabase( $arr['bruto_uurloon'] );
					$update['factor'] = $arr['factor'];
					$update['percentage'] = prepareAmountForDatabase( $arr['percentage'] );
					
					//uitrekenen
					$update['subtotaal_verkoop'] = round( $update['uren_decimaal'] * $update['verkooptarief'], 2 );
					$update['subtotaal_kosten'] = round( $update['uren_decimaal'] * $update['bruto_uurloon'] * $update['factor'] * ( $update['percentage'] / 100 ), 2 );
					
					$update['user_id'] = $this->user->user_id;
					
					$this->db_user->where( 'regel_id', $regel_id );
					$this->db_user->where( 'factuur_id', $this->_factuur_id );
					$this->db_user->update( 'facturen_regels', $update );
				}
			}
		}
		
		//hertellen
		return $this->_calTotaal();
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * insert
	 *
	 */
	private function _calTotaal(): bool
	{
		$regels = $this->regels();
		
		$totaal_verkoop = 0;
		$totaal_kosten = 0;
		
		foreach( $regels as $regel )
		{
			$totaal_verkoop += $regel['subtotaal_verkoop'];
			$totaal_kosten += $regel['subtotaal_kosten'];
		}
		
		$update['bedrag_excl'] = $totaal_verkoop;
		
		if( $this->_btw_verleggen == 0 )
			$update['bedrag_btw'] = round( $totaal_verkoop * 0.21, 2 );
		else
			$update['bedrag_btw'] = NULL;
		
		$update['bedrag_incl'] = round( $update['bedrag_excl'] + $update['bedrag_btw'], 2 );
		$update['bedrag_openstaand'] = $update['bedrag_incl'];
		
		$update['kosten_excl'] = $totaal_kosten;
		
		if( $this->_btw_verleggen == 0 )
			$update['kosten_btw'] = round( $totaal_kosten * 0.21, 2 );
		else
			$update['kosten_btw'] = NULL;
		
		$update['kosten_incl'] = round( $update['kosten_excl'] + $update['kosten_btw'], 2 );
		
		$this->db_user->where( 'factuur_id', $this->_factuur_id );
		$this->db_user->update( 'facturen', $update );
		
		if( $this->db_user->affected_rows() != -1 )
			return true;
		
		$this->_error[] = 'Fout bij optellen bedragen (6)';
		return false;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * insert
	 *
	 */
	private function _insert( $data ): bool
	{
		$insert['concept'] = 1;
		$insert['marge'] = 0;
		$insert['correctie'] = 1;
		$insert['uitzender_id'] = intval( $data['uitzender_id'] );
		$insert['user_id'] = $this->user->user_id;
		
		$this->db_user->insert( 'facturen', $insert );
		
		if( $this->db_user->insert_id() > 0 )
		{
			$this->_factuur_id = $this->db_user->insert_id();
			return true;
		}
		
		$this->_error[] = 'Fout bij toeveoegen factuur (1)';
		return false;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * Toon errors
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