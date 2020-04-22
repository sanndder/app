<?php

namespace models\facturatie;

use models\Connector;
use models\email\Email;
use models\file\Pdf;
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

if (!defined('BASEPATH'))exit('No direct script access allowed');


/*
 * Hoofdclass voor invoer verloning
 *
 *
 */

class Factuur extends Connector
{
	protected $_factuur_id = NULL;
	protected $_jaar = NULL;
	protected $_periode = NULL;
	
	protected $_uitzender_id = NULL;
	protected $_inlener_id = NULL;
	protected $_werknemer_id = NULL;
	protected $_zzp_id = NULL;
	
	protected $_periode_start = NULL;
	protected $_periode_einde = NULL;
	protected $_periode_dagen = NULL;
	
	protected $_kosten = false;
	
	protected $_error = NULL;
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * constructor
	 */
	public function __construct( $factuur_id = NULL  )
	{
		//call parent constructor for connecting to database
		parent::__construct();
		
		if( $factuur_id !== NULL )
			$this->setFactuurID( $factuur_id );
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * factuur ID
	 *
	 */
	public function setFactuurID( $factuur_id )
	{
		$this->_factuur_id = intval($factuur_id);
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * details
	 *
	 */
	public function details()
	{
		$query = $this->db_user->query( "SELECT *, DATEDIFF(verval_datum,factuur_datum) AS betaaltermijn FROM facturen WHERE factuur_id = $this->_factuur_id AND deleted = 0 AND concept = 0" );
		$details = DBhelper::toRow( $query, 'NULL' );
		
		if( $details !== NULL )
		{
			$details['bedrag_vrij'] = $details['bedrag_incl'] - $details['bedrag_grekening'];
		}
		
		return $details;
	}



	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * betaling toevoegen
	 *
	 */
	public function addBetaling($data)
	{
		$insert['factuur_id'] = $this->_factuur_id;
		$insert['type'] = $data['type'];
		$insert['user_id'] = $this->user->user_id;
		$insert['betaald_op'] = reverseDate($data['datum']);
		$insert['bedrag'] = prepareAmountForDatabase($data['bedrag']);
		
		$this->db_user->insert( 'facturen_betalingen', $insert );
		
		$this->_updateBedragenNaBetaling();
		
		return true;
	}


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * betalingen
	 *
	 */
	public function _updateBedragenNaBetaling()
	{
		$query = $this->db_user->query( "SELECT SUM(bedrag) AS betaald FROM facturen_betalingen WHERE factuur_id = $this->_factuur_id AND deleted = 0 ORDER BY betaald_op DESC" );
		$data = DBhelper::toRow( $query, 'NULL');
		
		if( $data !== NULL )
		{
			$this->db_user->query( "UPDATE facturen SET bedrag_openstaand = bedrag_incl - ".$data['betaald']." WHERE $this->_factuur_id LIMIT 1" );
		}
		
		$sql = "UPDATE facturen SET voldaan = 1 WHERE bedrag_openstaand = 0";
		$query = $this->db_user->query( $sql );
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * betalingen
	 *
	 */
	public function betalingen()
	{
		$query = $this->db_user->query( "SELECT * FROM facturen_betalingen WHERE factuur_id = $this->_factuur_id AND deleted = 0 ORDER BY betaald_op DESC" );
		return DBhelper::toArray( $query, 'id', 'NULL' );
	}
	
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * delete
	 * kosten en marge ook verwijderen
	 * invoer restten
	 * TODO beperkingen inbouwen (tijd user)
	 * TODO transaction van maken
	 */
	public function delete()
	{
		$this->db_user->query( "UPDATE facturen SET deleted = 1, deleted_on = NOW(), deleted_by = ? WHERE (factuur_id = $this->_factuur_id OR parent_id = $this->_factuur_id) AND  deleted = 0", array( $this->user->user_id ) );
		$this->db_user->query( "UPDATE facturen_kostenoverzicht SET deleted = 1, deleted_on = NOW(), deleted_by = ? WHERE factuur_id = $this->_factuur_id AND  deleted = 0", array( $this->user->user_id ) );
		$this->db_user->query( "UPDATE invoer_uren SET factuur_id = NULL WHERE factuur_id = $this->_factuur_id");
		$this->db_user->query( "UPDATE invoer_kilometers SET factuur_id = NULL WHERE factuur_id = $this->_factuur_id");
		$this->db_user->query( "UPDATE invoer_vergoedingen SET factuur_id = NULL WHERE factuur_id = $this->_factuur_id");
		$this->db_user->query( "UPDATE facturen_correcties SET factuur_id = NULL WHERE factuur_id = $this->_factuur_id");
	}


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * factuur naar inlener sturen
	 *
	 */
	public function email()
	{
		$factuur = $this->details();
		
		$email = new Email();
		
		$inlener = new Inlener( $factuur['inlener_id'] );
		$emailadressen = $inlener->emailadressen();
		$factuurgegevens = $inlener->factuurgegevens();
		
		$emailadressen['facturatie'] = ($emailadressen['facturatie'] === NULL ) ? $emailadressen['standaard'] : $emailadressen['facturatie'];
		
		
		$to['email'] = $emailadressen['facturatie'];
		$to['name'] = $inlener->bedrijfsnaam;
		
		if( $to['email'] == '' )
			return false;
		
		$email->to( $to );
		
		//cc naar factris
		if( ENVIRONMENT != 'development')
		{
			if( $factuurgegevens['factoring'] == 1 )
				$email->to( [ 'email' => 'facturen@factris.com', 'name' => 'Factris' ] );
		}
		
		$email->setSubject('Nieuwe factuur');
		$email->setTitel('Nieuwe factuur voor ' . $inlener->bedrijfsnaam);
		$email->setAttechment( 'facturen', 'factuur_id', $factuur, 'factuur_' . $factuur['factuur_nr'] . '.pdf' );
		$email->setBody('Er staat een nieuwe factuur voor u klaar. U vind de factuur als bijlage bij de email en in uw portal.
						<br /><br />
						<table>
						<tr><th style="padding-right: 20px; text-align: right">Factuur nr</th><th style="text-align: right">Bedrag Incl BTW</th></tr>
						<tr><td style="padding-right: 20px; text-align: right">'.$factuur['factuur_nr'].'</td><td style="text-align: right">€ '.number_format($factuur['bedrag_incl'],2,',','.').'</td></tr>
						</table>
						<br /><br />
						Met vriendelijke groet,<br />' . $this->werkgever->bedrijfsnaam());
		$email->useHtmlTemplate( 'default' );
		$email->delay( 0 );
		
		$email->send();
		
		$this->setSend();
		
		return true;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * set send
	 *
	 * @return bool
	 */
	public function setSend()
	{
		$update['send_on'] = date('Y-m-d H:i:s');
		$this->db_user->where( 'factuur_id', $this->_factuur_id);
		$this->db_user->update( 'facturen', $update );
		
		if( $this->db_user->affected_rows() != -1 )
			return true;
		
		return false;
	}

	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * set uplaoded to factoring
	 *
	 * @return bool
	 */
	public function setUploaded()
	{
		$update['to_factoring_on'] = date('Y-m-d H:i:s');
		$this->db_user->where( 'factuur_id', $this->_factuur_id);
		$this->db_user->update( 'facturen', $update );
		
		if( $this->db_user->affected_rows() != -1 )
			return true;
		
		return false;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * uitzender ID
	 *
	 * @return void
	 */
	public function setUitzender( $uitzender_id )
	{
		$this->_uitzender_id = intval($uitzender_id);
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * get uitzender ID
	 *
	 * @return int
	 */
	public function uitzender()
	{
		return $this->_uitzender_id;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * inlener ID
	 *
	 * @return void
	 */
	public function setInlener( $inlener_id )
	{
		$this->_inlener_id = intval($inlener_id);
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * get inlener ID
	 *
	 * @return int
	 */
	public function inlener()
	{
		return $this->_inlener_id;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * werknemer ID
	 *
	 * @return void
	 */
	public function setWerknemer( $werknemer_id )
	{
		$this->_werknemer_id = intval($werknemer_id);
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * zzp ID
	 *
	 * @return void
	 */
	public function setZZP( $zzp_id )
	{
		$this->_zzp_id = intval($zzp_id);
	}


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * details kosten ophalen
	 *
	 */
	public function kostendetails()
	{
		$query = $this->db_user->query( "SELECT * FROM facturen_kostenoverzicht WHERE factuur_id = $this->_factuur_id AND deleted = 0" );
		return DBhelper::toRow( $query, 'NULL' );
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 *  set kosten klaar om te downloaden
	 *
	 */
	public function kosten() :Factuur
	{
		$this->_kosten = true;
		return $this;
	}
	

	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * pdf bekijken
	 *
	 */
	public function view()
	{
		if( $this->_kosten )
		{
			$details = $this->kostendetails();
		}
		else
		{
			$details = $this->details();
		}
		
		$pdf = new Pdf( $details );
		$pdf->inline();
		
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