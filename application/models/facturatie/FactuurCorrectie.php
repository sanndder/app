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
	protected $_details = NULL;
	protected $_regels = NULL;
	
	protected $_inlener_id = NULL;
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
		if( $this->delete_row( 'facturen_regels', array('regel_id' => $regel_id) ) )
			return  $this->_calTotaal();
		
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
		
		if( $data['tijdvak'] == 'w' || $data['tijdvak'] == '4w' || $data['tijdvak'] == 'm ' )
			$update['tijdvak'] = $data['tijdvak'];
		if( $data['jaar'] >= date( 'Y' ) - 1 )
			$update['jaar'] = $data['jaar'];
		if( $data['periode'] > 0 )
			$update['periode'] = $data['periode'];
		
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
		if( isset( $data['regels'][0]['omschrijving'] ) && strlen( $data['regels'][0]['omschrijving'] ) > 0 && isset( $data['regels'][0]['bedrag'] ) && strlen( $data['regels'][0]['bedrag'] ) > 0 )
		{
			$insert['factuur_id'] = $this->_factuur_id;
			$insert['omschrijving'] = substr( $data['regels'][0]['omschrijving'], 0, 1000 );
			$insert['subtotaal_verkoop'] = prepareAmountForDatabase( $data['regels'][0]['bedrag'] );
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
				$update['omschrijving'] = substr( $arr['omschrijving'], 0, 1000 );
				$update['subtotaal_verkoop'] = prepareAmountForDatabase( $arr['bedrag'] );
				$update['user_id'] = $this->user->user_id;
				
				$this->db_user->where( 'regel_id', $regel_id );
				$this->db_user->where( 'factuur_id', $this->_factuur_id );
				$this->db_user->update( 'facturen_regels', $update );
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
	private	function _calTotaal(): bool
	{
		$regels = $this->regels();
		
		$totaal = 0;
		
		foreach( $regels as $regel )
			$totaal += $regel['subtotaal_verkoop'];
		
		$update['bedrag_excl'] = $totaal;
		$update['bedrag_btw'] = round($totaal*0.21,2);
		$update['bedrag_incl'] = round($update['bedrag_excl'] + $update['bedrag_btw'],2);
		$update['bedrag_openstaand'] = $update['bedrag_incl'];
		
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
	private	function _insert( $data ): bool
	{
		$insert['concept'] = 1;
		$insert['marge'] = 1;
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