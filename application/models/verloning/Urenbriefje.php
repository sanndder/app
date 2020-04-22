<?php

namespace models\verloning;

use models\Connector;
use models\forms\Validator;
use models\pdf\PdfFactuurVerkoopUren;
use models\pdf\PdfUrenbriefje;
use models\utils\DBhelper;
use models\utils\Tijdvak;
use models\werknemers\Werknemer;
use models\werknemers\WerknemerGroup;

if (!defined('BASEPATH'))exit('No direct script access allowed');


/*
 * Urentypes class
 * Aanmaken, wijzigen en verwijderen urentypes
 *
 *
 */

class Urenbriefje extends Connector
{

	private $_werknemer_id = NULL;
	private $_tijdvak = NULL;
	private $_jaar = NULL;
	private $_periode = NULL;
	
	private $_pdf = NULL;
	
	/*
	 * @var array
	 */
	private $_error = NULL;
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * constructor
	 */
	public function __construct( $id = NULL )
	{
		//call parent constructor for connecting to database
		parent::__construct();
		
		if( $id != NULL )
			$this->setID( $id );
	}
	
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * set werknemer_id
	 *
	 */
	public function werknemer( $id ) :Urenbriefje
	{
		$this->_werknemer_id = intval($id);
		return $this;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * set tijdvak
	 *
	 */
	public function tijdvak( $tijdvak ) :Urenbriefje
	{
		$this->_tijdvak = $tijdvak;
		return $this;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * set jaar
	 *
	 */
	public function jaar( $jaar ) :Urenbriefje
	{
		$this->_jaar = $jaar;
		return $this;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * set periode
	 *
	 */
	public function periode( $periode ) :Urenbriefje
	{
		$this->_periode = $periode;
		return $this;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * pdf maken
	 *
	 */
	private function _generate()
	{
		$this->_pdf = new PdfUrenbriefje();
		
		if( $this->_tijdvak == 'w' )$titel = 'Week ';
		if( $this->_tijdvak == '4w' )$titel = 'Periode ';
		if( $this->_tijdvak == 'm' )$titel = 'Maand ';
		
		$titel .= $this->_periode . ' - ' . $this->_jaar . '.pdf';
		
		$this->_pdf->setFooter();
		$this->_pdf->setHeader();
		
		$werknemer = new Werknemer( $this->_werknemer_id );
		
		$invoer = new Invoer();
		$invoer->setTijdvak( ['tijdvak' => $this->_tijdvak, 'jaar' => $this->_jaar, 'periode' => $this->_periode ] );
		$invoer->setWerknemer( $this->_werknemer_id );
		$uren = $invoer->invoerUrenbriefje();
		
		$this->_pdf->setBody( $titel, $werknemer, $uren );
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * download inline
	 *
	 */
	public function inline()
	{
		$this->_generate();
		
		$this->_pdf->preview();
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