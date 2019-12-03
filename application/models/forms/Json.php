<?php

namespace models\forms;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/*
 * Validatie class
 *
 * Voor het valideren van input
 *
 */
class Json{

	/*
	 * @var string
	 */
	public $_table = NULL;

	/*
	 * @var json
	 */
	public $json = NULL;

	/*
	 * @var array
	 */
	private $_errors = array();

	/*
	 * Debug on/off
	 * @var boolean
	 */
	private $_debug = false;


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 *
	 *
	 */
	public function __construct()
	{
		//op dev server altijd debug
		if( ENVIRONMENT == 'development' )
			$this->_debug = true;

		return $this;
	}


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * set the table that the input is for
	 *
	 */
	public function table( $table )
	{
		$this->_table = trim($table);
		$this->_loadJson();

		return $this;
	}

	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * load the json file for the table
	 *
	 */
	private function _loadJson()
	{
		$file = file_get_contents('application/models/forms/json/' . $this->_table . '.json');
		$this->json = json_decode($file);

		//debug mode
		if( $this->_debug )
		{
			if(json_last_error() != 0 )
				show(json_last_error_msg());
		}
	}

	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * set the table that the input is for
	 *
	 */
	public function input( $input )
	{
		$this->_input = $input;
		return $this;
	}

	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * run the validation rules
	 *
	 */
	public function run()
	{
		//temp
		show($this->_input);

		//first remove fields that are not allowd
		$this->_removeForbiddenFiels();

		//cleanup input
		$this->_cleanupInput();

		//validate
		$this->_validateInput();

		return $this;
	}


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * cleanup fields
	 * Trim, strip tags, to lower or upper, remove spaces or characters
	 * By default always tim and striptags
	 *
	 */
	private function _cleanupInput()
	{
		foreach( $this->_input as $field => &$val )
		{
			$rules = $this->json->field->$field->rules;

			//trim
			if( !isset($rules->trim) || $rules->trim === true )
				$val = trim( $val );

			//striptags
			if( !isset($rules->striptags) || $rules->striptags === true )
				$val = strip_tags( $val );

			//to lower
			if( isset($rules->tolower) && $rules->tolower === true )
				$val = strtolower( $val );

			//to upper
			if( isset($rules->toupper) && $rules->toupper === true )
				$val = strtoupper( $val );

			//remove spaces
			if( isset($rules->removespaces) && $rules->removespaces === true )
				$val = str_replace( ' ', '', $val );

			//remove characters
			if( isset($rules->removechars) )
				$val = str_replace( explode(',',$rules->removechars), '', $val );

		}
	}


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * validate fields
	 * Run al sorts of validator tests
	 *
	 */
	private function _validateInput()
	{
		foreach( $this->_input as $field => $val )
		{
			//copy for cleaner code
			$rules = $this->json->field->$field->rules;

			//is field empty, but required?
			if( $val == '' && isset($rules->required) && $rules->required === true)
				$this->_errors[$field][] = sprintf( '%s is verplicht', $this->json->field->$field->label);

			//field is not empty
			if( $val != '' )
			{
				//is var type set
				if( isset($rules->type) )
				{
					//integer
					if( $rules->type == 'int' && !is_int($val) )
						$this->_errors[$field][] = sprintf( '<strong>%s</strong> moet een geheel getal zijn (geen decimalen)', $this->json->field->$field->label );

					//decimal
					if( strpos($rules->type, 'decimal' ) !==  false )
					{
						//NL format to International
						$val = prepareAmountForDatabase($val);
						if( !is_numeric($val) )
							$this->_errors[$field][] = sprintf( '<strong>%s</strong> moet een getal zijn', $this->json->field->$field->label );
					}

					//email
					if( $rules->type == 'email' && !Valid::email($val) )
						$this->_errors[$field][] = sprintf( '<strong>%s</strong> is geen geldig emailadres', $this->json->field->$field->label );

					//email
					if( $rules->type == 'iban' && !Valid::iban($val) )
						$this->_errors[$field][] = sprintf( '<strong>%s</strong> is geen geldig IBAN', $this->json->field->$field->label );

					//postcode
					if( $rules->type == 'postcode' && !Valid::postcode($val) )
						$this->_errors[$field][] = sprintf( '<strong>%s</strong> is geen geldige postcode', $this->json->field->$field->label );

					//btwnr
					if( $rules->type == 'btwnr' && !Valid::btwnr($val) )
						$this->_errors[$field][] = sprintf( '<strong>%s</strong> is geen geldig BTW nummer', $this->json->field->$field->label );

					//bsn
					if( $rules->type == 'bsn')
					{
						//add zero if needed
						if( strlen($val) == 8 )
							$this->_input[$field] = $val = '0'. $val;

						if( !Valid::bsn($val) )
							$this->_errors[$field][] = sprintf( '<strong>%s</strong> is geen geldig BSN nummer', $this->json->field->$field->label );

					}
				}


				//min length
				if( strlen($val) < $rules->minlength )
					$this->_errors[$field][] = sprintf( '<strong>%s</strong> is te kort en moet minstens %s tekens bevatten', $this->json->field->$field->label, $rules->minlength );

				//max length
				if( strlen($val) > $rules->maxlength )
					$this->_errors[$field][] = sprintf( '<strong>%s</strong> is te lang en mag maximaal %s tekens bevatten', $this->json->field->$field->label, $rules->maxlength );
			}



		}
	}


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * remove the fields that are not allow from the input array
	 *
	 */
	private function _removeForbiddenFiels()
	{
		foreach( $this->json->remove as $field )
		{
			unset( $this->_input[$field] );
		}
	}


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Return true if tere are no errors
	 *
	 */
	public function success()
	{
		//TEMP show result array
		show($this->_input);
		show($this->_errors);

		return $this->_success;
	}
}


?>