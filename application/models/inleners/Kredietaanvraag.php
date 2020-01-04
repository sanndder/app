<?php

namespace models\Inleners;
use models\Connector;
use models\forms\Validator;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/*
 * Krediet aanvraag
 *
 * Kan uiteindelijk omgezet worden in een inlener
 *
 */
class Kredietaanvraag extends Connector {

	/*
	 * @var array
	 */
	private $_error = NULL;


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