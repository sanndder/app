<?php

namespace models\facturatie;

use models\Connector;
use models\forms\Validator;
use models\inleners\Inlener;
use models\pdf\PdfFactuur;
use models\pdf\PdfFactuurDefault;
use models\pdf\PdfFactuurUren;
use models\uitzenders\Uitzender;
use models\users\UserGroup;
use models\utils\DBhelper;
use models\utils\Tijdvak;
use models\verloning\Invoer;
use models\verloning\InvoerKm;
use models\verloning\InvoerUren;
use models\verloning\InvoerVergoedingen;
use models\werknemers\PlaatsingGroup;

if (!defined('BASEPATH'))exit('No direct script access allowed');


/*
 * Hoofdclass voor invoer verloning
 *
 *
 */

class FactuurFactory extends Connector
{
	private $_sessie_id = NULL;
	
	private $_inlener_factuurgegevens = NULL;
	private $_inlener_werknemers = NULL;
	
	private $_setting_split_project = 0;
	private $_setting_split_werknemer = 0;
	
	//array met alle ruwe data
	private $_invoer_array = NULL;
	
	//invoer object laden, wordt meerdere malen gebruikt
	private $invoer = NULL;
	
	protected $_inlener_id = NULL;
	protected $_uitzender_id = NULL;
	
	protected $_tijdvak = NULL;
	protected $_jaar = NULL;
	protected $_periode = NULL;
	
	protected $_error = NULL;
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * constructor
	 */
	public function __construct()
	{
		//call parent constructor for connecting to database
		parent::__construct();
		
		//start van de factuur sessie
		$this->_sessieStart();
		
		//invoer class laden
		$this->invoer = new Invoer();
	}


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Functie die alles aanstuurt
	 *
	 */
	public function run() :bool
	{
		//invoer ophalen en wegzetten in database

		$this->invoer->setTijdvak( $_POST );
		$this->invoer->setInlener( $_POST['inlener_id'] );
		$this->invoer->setUitzender( $this->_uitzender_id );
		
		//invoer ophalen en wegzetten in database
		if( !$this->_werknemersForInlener() )
		{
			$this->_sessieFinish( 'sessie afgebroken omdat er geen werknemers zijn gevonden' );
			return false;
		}
		
		//ruwe invoer ophalen en naar array
		if( !$this->_invoerToArray() )
		{
			$this->_sessieFinish( 'sessie afgebroken wegens fout in bij laden invoer in array' );
			return false;
		}
		
		//invoer groeperen en optellen, indien nogdig per project en/of werknemer
		
		//alles is klaar, beeindig de sessie
		$this->_sessieFinish();
		return true;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * invoer laden en naar array
	 * array is opgebouwd als volgt
	 * $_invoer[type(uren,km,vergoedingen,pensioen)][werknemer_id][row]
	 *
	 */
	public function _invoerToArray() :bool
	{
		//init
		$this->_invoer_array['uren'] = array();
		$this->_invoer_array['km'] = array();
		$this->_invoer_array['vergoedingen'] = array();
		$this->_invoer_array['pensioen'] = array();
		
		//invoer classes laden
		$invoerUren = new InvoerUren( $this->invoer );
		$invoerKm = new InvoerKm( $this->invoer );
		$invoervergoedingen = new InvoerVergoedingen( $this->invoer );
		
		//door werknemers lopen en invoer laden
		foreach( $this->_inlener_werknemers as $werknemer_id => $array )
		{
			//per werknemer loggen
			$this->_sessieLog( 'load', "input loop werknemer: $werknemer_id" );
			
			//werknemer ID instellen
			$invoerUren->setWerknemer( $werknemer_id );
			$invoerKm->setWerknemer( $werknemer_id );
			$invoervergoedingen->setWerknemer( $werknemer_id );
			
			//ureninvoer
			if( NULL !== $uren = $invoerUren->getWerknemerUrenRijen()  )
				$this->_invoer_array['uren'][$werknemer_id] = $uren;
			
			//km
			if( NULL !== $km = $invoerKm->getWerknemerKilometers()  )
				$this->_invoer_array['km'][$werknemer_id] = $km;
			
			//vergoedingen
			if( $vergoedingen = $invoervergoedingen->getWerknemerVergoedingen()  )
			{
				if( count($vergoedingen) !== 0 )
					$this->_invoer_array['vergoedingen'][$werknemer_id] = $vergoedingen;
			}
			
		}
		
		return true;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * alle werknemers die bij inlener zijn geplaatst ophalen
	 * TODO: aleen voor deze periode
	 * $werknemer['werknemer_id']
	 * $werknemer['naam']
	 */
	public function _werknemersForInlener() :bool
	{
		$werknemers = $this->invoer->listWerknemers();
		
		//stoppen bij lege lijst
		if(count($werknemers) === 0 )
			return  false;
		
		//extra sorteren omdat key niet werknemer_id is
		foreach( $werknemers as $array )
			$this->_inlener_werknemers[$array['werknemer_id']]['naam'] = $array['naam'];
		
		//log actie
		$this->_sessieLog( 'load', json_encode($this->_inlener_werknemers) );
		
		return true;
	}

	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Tijdvak info instellen
	 * TODO: controle op periodes
	 *
	 */
	public function setTijdvak( $data )
	{
		if( isset($data['tijdvak']) ) $this->_tijdvak = $data['tijdvak'];
		if( isset($data['periode']) ) $this->_periode = intval($data['periode']);
		if( isset($data['jaar']) ) $this->_jaar = intval($data['jaar']);
		
		//tijdvak invoer zelfde stellen
		$this->invoer->setTijdvak( $data );
		
		//log tijdvak
		$this->_sessieLog( 'setting', json_encode($data) );
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * uitzender ID
	 *
	 */
	public function setUitzender( $uitzender_id )
	{
		//voor deze class
		$this->_uitzender_id = intval($uitzender_id);
		
		//invoer ook vullen
		$this->invoer->setUitzender( $this->_uitzender_id );
		
		//alles voor de uitzender is geladen
		$this->_sessieLog( 'setting', "uitzender_id: $uitzender_id" );
	}
	

	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * inlener ID
	 *
	 */
	public function setInlener( $inlener_id )
	{
		//voor deze class
		$this->_inlener_id = intval($inlener_id);
		
		//invoer meenemen
		$this->invoer->setInlener( $this->_inlener_id );
		
		//inlener facturatiegegevens
		$inlener = new Inlener( $this->_inlener_id);
		$this->_inlener_factuurgegevens = $inlener->factuurgegevens();
		
		//alles voor de inlener is geladen
		$this->_sessieLog( 'setting', "inlener_id: $inlener_id" );
		
		//settings voor splitsen
		if( $this->_inlener_factuurgegevens['factuur_per_medewerker'] == 1 )
		{
			$this->_setting_split_werknemer = 1;
			$this->_sessieLog( 'setting', "split_werknemer: 1" );
		}
		
		if( $this->_inlener_factuurgegevens['factuur_per_project'] == 1 )
		{
			$this->_setting_split_project = 1;
			$this->_sessieLog( 'setting', "split_project: 1" );
		}
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * database opschonen
	 *
	 */
	static function clear()
	{
		if( ENVIRONMENT != 'development' )
			die('GEEN TOEGANG VOOR DEZE FUNCTiE');
		
		$CI =& get_instance();
		$db_user = $CI->db_user;
		
		$db_user->query( "TRUNCATE facturen" );
		$db_user->query( "TRUNCATE facturen_bijlages" );
		$db_user->query( "TRUNCATE facturen_cessie_tekst" );
		$db_user->query( "TRUNCATE facturen_log" );
		$db_user->query( "TRUNCATE facturen_regels" );
		$db_user->query( "TRUNCATE facturen_sessies" );
		$db_user->query( "TRUNCATE facturen_sessies_log" );
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Start factuur sessie
	 *
	 */
	public function _sessieStart()
	{
		$insert['user_id'] = $this->user->user_id;
		$insert['ip'] = $_SERVER['REMOTE_ADDR'];
		$insert['sessie_start'] = microtime(true);
		
		$this->db_user->insert( 'facturen_sessies', $insert );
		
		$this->_sessie_id = $this->db_user->insert_id();
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Start factuur sessie
	 *
	 */
	public function _sessieFinish( $abort = NULL )
	{
		if( $abort !== NULL )
			$this->_sessieLog( 'abort', $abort );
		
		$update['sessie_end'] = microtime(true);
		$this->db_user->where( 'sessie_id', $this->_sessie_id );
		$this->db_user->update( 'facturen_sessies', $update );
		
		//calc duration
		$this->db_user->query( "UPDATE facturen_sessies SET sessie_duration = sessie_end - sessie_start WHERE  sessie_id = $this->_sessie_id LIMIT 1" );
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Log sessie gegevens
	 *
	 */
	public function _sessieLog( $type, $message, $factuur_id = NULL, $flag = 0 )
	{
		$backtrace = debug_backtrace(0,2);
		
		if( isset($backtrace[1]['function']) )
			$insert['method'] = $backtrace[1]['function'];
		
		$insert['sessie_id'] = $this->_sessie_id;
		$insert['type'] = $type;
		$insert['message'] = $message;
		$insert['factuur_id'] = $factuur_id;
		$insert['flag'] = $flag;
		$insert['microtime'] = microtime(true);;
		
		@$this->db_user->insert( 'facturen_sessies_log', $insert );
		
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Toon errors
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