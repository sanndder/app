<?php

namespace models\inleners;
use models\Connector;
use models\forms\Validator;
use models\utils\DBhelper;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/*
 * Krediet aanvraag
 *
 * Kan uiteindelijk omgezet worden in een inlener
 *
 */
class Kredietaanvraag extends Connector {

	private $_aanvraag_id = NULL;
	private $_inlener_id = NULL;
	
	/*
	 * @var array
	 */
	private $_error = NULL;


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * constructor
	 */
	public function __construct( $kredietaanvraag_id = NULL )
	{
		//call parent constructor for connecting to database
		parent::__construct();
		
		
		if( $kredietaanvraag_id !== NULL )
			$this->setID( $kredietaanvraag_id );
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Nieuwe aanvraag
	 *
	 * @return array
	 */
	public function setID( $aanvraag_id )
	{
		$this->_aanvraag_id = intval($aanvraag_id);
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Nieuwe aanvraag
	 *
	 * @return array
	 */
	public function add()
	{
		$validator = new Validator();
		$validator->table('inleners_kredietaanvragen')->input($_POST)->run();
		
		$input = $validator->data();
		
		//geen fouten, nieuwe insert doen wanneer er wijzigingen zijn
		if ( $validator->success() )
		{
			if( isset($this->uitzender->id) )
				$insert['uitzender_id'] = $this->uitzender->id;
			
			$insert['kredietlimiet_gewenst'] = intval(prepareAmountForDatabase($input['kredietlimiet']));
			$insert['kredietlimiet_toegekend'] = intval(prepareAmountForDatabase($input['kredietlimiet']));
			$insert['bedrijfsnaam'] = $input['bedrijfsnaam'];
			$insert['kvknr'] = $input['kvknr'];
			$insert['telefoon'] = $input['telefoon'];
			$insert['email'] = $input['email'];
			$insert['straat'] = $input['straat'];
			$insert['huisnummer'] = $input['huisnummer'];
			$insert['postcode'] = $input['postcode'];
			$insert['plaats'] = $input['plaats'];
			$insert['btwnr'] = $input['btwnr'];
			$insert['user_id'] = $this->user->user_id;
			
			$this->db_user->insert( 'inleners_kredietaanvragen', $insert );
			
			if( $this->db_user->insert_id() < 1 )
				$this->_error = 'Wegschrijven naar database mislukt!';
		}
		else
		{
			$this->_error = $validator->errors();
		}
		
		return $input;
	}



	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Haal gegevens aanvraag op
	 *
	 * @return array|void
	 */
	public function aanvraag()
	{
		$sql = "SELECT * FROM inleners_kredietaanvragen WHERE id = $this->_aanvraag_id AND deleted = 0 LIMIT 1";
		$query = $this->db_user->query( $sql );
		
		return DBhelper::toRow($query);
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Aanvraag afwijzen
	 * @return bool
	 */
	public function deny( $aanvraag_id )
	{
		//update aanvraag
		$update['kredietlimiet_toegekend'] = 0;
		$update['krediet_afgewezen'] = 1;
		$update['updated_by'] = $this->user->user_id;
		$update['updated_by'] = date('Y-m-d H:i:s');
		
		$this->db_user->where( 'id', $aanvraag_id );
		$this->db_user->update( 'inleners_kredietaanvragen', $update );
		
		if( $this->db_user->affected_rows() > 0 )
			return true;
		
		return false;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Accepteer aanvraag en zet zonodig gegevens door naar inlener
	 * TODO : Voor extra aanvraag nog aanpassen
	 * @return bool
	 */
	public function accept()
	{
		//bijbehorende aanvraag ophalen
		$aanvraag = $this->aanvraag();
		
		//inlener aanmaken indien nodig
		if( $aanvraag['inlener_id'] === NULL )
		{
			//waardes als post meegeven
			$limiet_toegekend = $_POST['toegekend'];
			unset($_POST['accept']);
			unset($_POST['toegekend']);
			
			$_POST['uitzender_id'] = $aanvraag['uitzender_id'];
			$_POST['bedrijfsnaam'] = $aanvraag['bedrijfsnaam'];
			$_POST['kvknr'] = $aanvraag['kvknr'];
			$_POST['btwnr'] = $aanvraag['btwnr'];
			$_POST['telefoon'] = $aanvraag['telefoon'];
			$_POST['straat'] = $aanvraag['straat'];
			$_POST['huisnummer'] = $aanvraag['huisnummer'];
			$_POST['postcode'] = $aanvraag['postcode'];
			$_POST['plaats'] = ucfirst(strtolower($aanvraag['plaats'] )); // mooier maken
			
			$inlener = new Inlener( NULL );
			$inlener->forceCheck();
			$inlener->setBedrijfsgegevens();
			
			//bij errors afkappen
			if( $inlener->errors() !== false )
			{
				$this->_error = $inlener->errors();
				return false;
			}
			
			//nieuw inlener id
			$this->_inlener_id = $inlener->inlener_id;
			$this->setKredietlimiet( $limiet_toegekend );
			$this->setKredietGebruik( 0 );
			
			$update['inlener_id'] = $inlener->inlener_id;
		}
		
		//update aanvraag
		$update['kredietlimiet_toegekend'] = prepareAmountForDatabase($limiet_toegekend);
		$update['krediet_afgewezen'] = 0;
		$update['updated_by'] = $this->user->user_id;
		$update['updated_by'] = date('Y-m-d H:i:s');
		
		$this->db_user->where( 'id', $aanvraag['id'] );
		$this->db_user->update( 'inleners_kredietaanvragen', $update );
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Kredietlimiet instellen
	 *
	 * @return void
	 */
	public function setKredietlimiet( $limiet )
	{
		//alles weg
		$this->delete_row( 'inleners_kredietgegevens', array('inlener_id' => $this->_inlener_id) );
		
		$insert['inlener_id'] = $this->_inlener_id;
		$insert['kredietlimiet'] = prepareAmountForDatabase($limiet);
		$insert['user_id'] = $this->user->user_id;
		
		$this->db_user->insert( 'inleners_kredietgegevens', $insert );
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Krediet gebruik
	 *
	 * @return void
	 */
	public function setKredietGebruik( $limiet )
	{
		//alles weg
		$this->delete_row( 'inleners_kredietgebruik', array('inlener_id' => $this->_inlener_id) );
		
		$insert['inlener_id'] = $this->_inlener_id;
		$insert['kredietgebruik'] = intval($limiet);
		$insert['user_id'] = $this->user->user_id;
		
		$this->db_user->insert( 'inleners_kredietgebruik', $insert );
	}
	

	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Toon errors
	 * @return array or boolean
	 */
	public function errors()
	{
		//output for debug
		if( isset($_GET['debug']) )
		{
			if( $this->_error === NULL )
				show('Geen errors');
			else
				show($this->_error);
		}

		if( $this->_error === NULL )
			return false;

		return $this->_error;
	}
}


?>