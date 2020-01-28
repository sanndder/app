<?php

namespace models\verloning;

use models\Connector;
use models\forms\Validator;
use models\utils\DBhelper;
use models\utils\Tijdvak;
use models\werknemers\PlaatsingGroup;

if (!defined('BASEPATH'))exit('No direct script access allowed');


/*
 * Hoofdclass voor invoer verloning
 *
 *
 */

class Invoer extends Connector
{
	protected $_tijdvak = NULL;
	protected $_jaar = NULL;
	protected $_periode = NULL;
	
	protected $_uitzender_id = NULL;
	protected $_inlener_id = NULL;
	protected $_werknemer_id = NULL;
	protected $_zzp_id = NULL;
	
	protected $_periode_start = NULL;
	protected $_periode_einde = NULL;
	protected $_periode_dagen = NULL;
	
	protected $_error = NULL;
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * constructor
	 */
	public function __construct()
	{
		//call parent constructor for connecting to database
		parent::__construct();
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
	 * Tijdvak info instellen
	 * TODO: controle op periodes
	 * @return void
	 */
	public function setTijdvak( $data )
	{
		if( isset($data['tijdvak']) ) $this->_tijdvak = $data['tijdvak'];
		if( isset($data['periode']) ) $this->_periode = intval($data['periode']);
		if( isset($data['jaar']) ) $this->_jaar = intval($data['jaar']);
		
		$tijdvak = new Tijdvak( $this->_tijdvak, $this->_jaar, $this->_periode  );
		
		$this->_periode_start = $tijdvak->startDatum();
		$this->_periode_einde = $tijdvak->eindDatum();
		$this->_periode_dagen = $tijdvak->dagen();
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * get start
	 */
	public function getPeriodeStart()
	{
		return $this->_periode_start;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * get einde
	 */
	public function getPeriodeEinde()
	{
		return $this->_periode_einde;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Lijst met werknemerrs plus was basic info
	 * TODO: uitbreiden met overzicht data
	 * @return array
	 */
	public function listWerknemers()
	{
		$werknemers = array();
		
		//plaatsingen eerst laden
		$plaatsingGroup = new PlaatsingGroup();
		$plaatsingen = $plaatsingGroup->inlener( $this->_inlener_id )->all();
		
		//niet verder
		if( count($plaatsingen) === 0 )
			return  $werknemers;
		
		//info filteren
		foreach( $plaatsingen as $plaatsing)
		{
			$werknemer['werknemer_id'] = $plaatsing['werknemer_id'];
			$werknemer['naam'] = $plaatsing['naam'];
			$werknemer['start_plaatsing'] = $plaatsing['start_plaatsing'];
			$werknemer['einde_plaatsing'] = $plaatsing['einde_plaatsing'];
			$werknemer['block'] = 0;
			$werknemer['block_msg'] = '';
			
			$werknemers[] = $werknemer;
		}
		
		return $werknemers;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Overzicht data ophalen
	 * TODO: uitbreiden met overzicht data
	 * @return array
	 */
	public function getWerknemerOverzicht()
	{
		//eerst werknemerlijst ophalen, KEY IS NIET WERKNEMER ID omdat json adners verkeerd sorteerd
		$werknemers = $this->listWerknemers();
		
		return $werknemers;
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