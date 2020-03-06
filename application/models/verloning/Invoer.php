<?php

namespace models\verloning;

use models\Connector;
use models\forms\Validator;
use models\inleners\Inlener;
use models\users\UserGroup;
use models\utils\DBhelper;
use models\utils\Tijdvak;

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
	 * tijdvak info voro kopieren
	 */
	public function tijdvakinfo()
	{
		$array['tijdvak'] = $this->_tijdvak;
		$array['jaar'] = $this->_jaar;
		$array['periode'] = $this->_periode;
		$array['periode'] = $this->_periode;
		$array['periode_start'] = $this->_periode_start;
		$array['periode_einde'] = $this->_periode_einde;
		
		return $array;
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
	 * TODO: alleen voor peridoe die gevraagd wordt, start/einde plaatsing meenemen
	 * @return array
	 */
	public function listWerknemers()
	{
		$werknemers = array();
		
		//plaatsingen eerst laden
		if( $this->user->werkgever_type == 'uitzenden' )
			$plaatsingGroup = new \models\werknemers\PlaatsingGroup();
		if( $this->user->werkgever_type == 'bemiddeling')
			$plaatsingGroup = new \models\zzp\PlaatsingGroup();
		
		
		$plaatsingen = $plaatsingGroup->inlener( $this->_inlener_id )->all();

		//niet verder
		if( count($plaatsingen) === 0 )
			return  $werknemers;
		
		//info filteren
		foreach( $plaatsingen as $plaatsing)
		{
			if( $this->user->werkgever_type == 'uitzenden' ) $werknemer['id'] = $plaatsing['werknemer_id'];
			if( $this->user->werkgever_type == 'bemiddeling') $werknemer['id'] = $plaatsing['zzp_id'];
			
			$werknemer['naam'] = $plaatsing['naam'];
			$werknemer['bedrijfsnaam'] = (isset($plaatsing['bedrijfsnaam'])) ? $plaatsing['bedrijfsnaam'] : NULL;
			
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
		//eerst werknemerlijst ophalen, KEY IS NIET WERKNEMER ID omdat json anders verkeerd sorteerd
		$werknemers = $this->listWerknemers();
		
		return $werknemers;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * geuploade bijalge naar de database
	 * 
	 */
	public function saveBijlageToDatabase( array $file_info ) :bool
	{

		$insert = $file_info;
		
		$insert['tijdvak'] = $this->_tijdvak;
		$insert['jaar'] = $this->_jaar;
		$insert['periode'] = $this->_periode;
		$insert['uitzender_id'] = $this->_uitzender_id;
		$insert['inlener_id'] = $this->_inlener_id;
		$insert['user_id'] = $this->user->id;

		$this->db_user->insert( 'invoer_bijlages', $insert );
		
		if( $this->db_user->insert_id() > 0 )
		{
			$this->_error[] = 'Wegschrijven naar database is mislukt';
			return true;
		}
		
		return false;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * get bijlages
	 * TODO: Icons verplaatsen naar helper
	 */
	public function getBijlages() :array
	{
		$sql = "SELECT * FROM invoer_bijlages WHERE uitzender_id = ? AND inlener_id = ? AND tijdvak = ? AND jaar = ? AND periode = ? AND deleted = 0 ORDER BY file_name_display";
		$query = $this->db_user->query( $sql, array( $this->_uitzender_id, $this->_inlener_id, $this->_tijdvak, $this->_jaar, $this->_periode ) );
		
		if( $query->num_rows() == 0 )
			return [];
		
		foreach( $query->result_array() as $row )
		{
			$row['icon'] = '';
			if( $row['file_ext'] == 'jpg' ) $row['icon'] = 'image.jpg';
			if( $row['file_ext'] == 'gif' ) $row['icon'] = 'image.jpg';
			if( $row['file_ext'] == 'png' ) $row['icon'] = 'image.jpg';
			if( $row['file_ext'] == 'pdf' ) $row['icon'] = 'pdf.svg';
			if( $row['file_ext'] == 'xls' ) $row['icon'] = 'excel.svg';
			if( $row['file_ext'] == 'xlsx' ) $row['icon'] = 'excel.svg';
			
			if( $row['project_id'] == NULL ) $row['project_naam'] = '';
			$row['file_size'] = size($row['file_size']);
			
			$date = new \DateTime($row['timestamp']);
			$row['timestamp'] = $date->format("d-m-Y \o\m H:i" );
			$data[] = $row;
		}

		$data = UserGroup::findUserNames($data);
		
		return $data;
	}


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Haal alle bijlages op
	 * @return ?array
	 */
	public function getBijlage( ?int $file_id ) :?array
	{
		$query = $this->db_user->query( "SELECT * FROM invoer_bijlages WHERE file_id = ? AND deleted = 0 LIMIT 1", array($file_id) );
		return DBhelper::toRow( $query, 'NULL' );
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * verwijder 1 bijlage
	 *
	 */
	public function delBijlage() :bool
	{
		$bijlage = $this->getBijlage( $_POST['file_id'] );
		
		//check
		if( $bijlage['inlener_id'] != $this->_inlener_id || $bijlage['uitzender_id'] != $this->_uitzender_id || $bijlage['tijdvak'] != $this->_tijdvak || $bijlage['jaar'] != $this->_jaar || $bijlage['periode'] != $this->_periode  )
			return false;
		
		$this->db_user->query( "UPDATE invoer_bijlages SET deleted = 1, deleted_on = NOW(), deleted_by = ? WHERE file_id = ?", array( $this->user->user_id, $_POST['file_id'] ) );
		
		if( $this->db_user->affected_rows() < 1 )
			return false;
		
		return true;
	}



	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * projecten voor inlener
	 *
	 */
	public function getProjecten()
	{
		$inlener = new Inlener( $this->_inlener_id);
		return $inlener->projecten();
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