<?php

namespace models\werknemers;

use models\Connector;
use models\forms\Valid;
use models\utils\DBhelper;

if (!defined('BASEPATH'))
	exit('No direct script access allowed');


/*
 * Ziekmelding class
 *
 *
 *
 */

class Ziekmelding extends Connector
{
	/**
	 * @var int
	 */
	private $_melding_id;
	private $_werknemer_id;
	private $_data;

	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * constructor
	 */
	public function __construct( $melding_id = NULL )
	{
		//call parent constructor for connecting to database
		parent::__construct();

		if( $melding_id !== NULL )
			$this->setID( $melding_id );
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Set ID
	 */
	public function setID( $melding_id = NULL )
	{
		$this->_melding_id = intval($melding_id);
	}


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Set werknemer ID
	 */
	public function werknemer( $werknemer_id = NULL )
	{
		$this->_werknemer_id = intval($werknemer_id);
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Nieuwe melding
	 *
	 */
	private function _validate() :bool
	{
		if( isset($this->_data['datum_melding']))
			$this->_data['datum_melding'] = reverseDate($this->_data['datum_melding']);
		
		if( isset($this->_data['datum_start_ziek']))
			$this->_data['datum_start_ziek'] = reverseDate($this->_data['datum_start_ziek']);
		
		if( !isset($this->_data['datum_melding']) || !Valid::date($this->_data['datum_melding']) )
			$this->_error[] = 'Ongeldige datum melding';
		
		if( !isset($this->_data['datum_start_ziek']) || !Valid::date($this->_data['datum_start_ziek']) )
			$this->_error[] = 'Ongeldige datum start ziekteverzuim';
		
		if( !isset($this->_data['ongeval']) || ($this->_data['ongeval'] != 0 && $this->_data['ongeval'] != 1) )
			$this->_error[] = 'Geef aan of er sprake is van een ongeval';
		
		if( isset($this->_data['uren_eerste_verzuimdag']))
		{
			if( $this->_data['uren_eerste_verzuimdag'] < 0 || $this->_data['uren_eerste_verzuimdag'] > 8 )
				$this->_error[] = 'Ongeldige waarde uren eerste verzuimdag';
			
			if( $this->_data['uren_eerste_verzuimdag'] == 0 )
				$this->_data['uren_eerste_verzuimdag'] = NULL;
		}
		
		if( $this->_werknemer_id === NULL )
			$this->_error[] = 'Ongeldig werknemer ID';
		
		
		if( $this->_error === NULL )
			return true;
		
		return false;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Nieuwe melding
	 *
	 */
	public function set( $post ) :bool
	{
		$this->_data = $post;
		
		if( !$this->_validate() )
			return false;
		
		$insert['werknemer_id'] = $this->_werknemer_id;
		$insert['user_id'] = $this->user->user_id;
		$insert['datum_melding'] = $this->_data['datum_melding'];
		$insert['datum_start_ziek'] = $this->_data['datum_start_ziek'];
		$insert['ongeval'] = $this->_data['ongeval'];
		$insert['uren_eerste_verzuimdag'] = $this->_data['uren_eerste_verzuimdag'];
		$insert['opmerking'] = $this->_data['opmerking'];
		
		$this->db_user->insert( 'ziekmeldingen', $insert );
		
		if( $this->db_user->insert_id() > 0 )
			return true;
		
		$this->_error[] = 'Fout bij opslaan';
		return false;
	}

}


?>