<?php

namespace models\Forms;

if (!defined('BASEPATH'))
	exit('No direct script access allowed');


/*
 * Validatie class
 *
 * Voor het valideren van input
 *
 */

class Validator extends Json
{

	/*
	 * @var array
	 */
	private $_input = NULL;

	/*
	 * @var array
	 */
	private $_errors = array();

	/*
	 * @var array
	 */
	private $_success = true;

	/*
	 * Debug on/off
	 * @var boolean
	 */
	private $_debug = false;

	/*
	 * Debug on/off
	 * @var boolean
	 */
	private $_paterns = Array(
		'date' => "^[0-9]{1,2}[-/][0-9]{1,2}[-/][0-9]{4}\$",
		'amount' => "^[-]?[0-9]+\$",
		'number' => "^[-]?[0-9,]+\$^",
		'alfanum' => "^[0-9a-zA-Z ,.-_\\s\?\!]+\$",
		'not_empty' => "[a-z0-9A-Z]+",
		'words' => "^[A-Za-z]+[A-Za-z \\s]*\$",
		'phone' => "^[0-9]{10,11}\$",
		'zipcode' => "^[1-9][0-9]{3}[a-zA-Z]{2}\$",
		'plate' => "^([0-9a-zA-Z]{2}[-]){2}[0-9a-zA-Z]{2}\$",
		'price' => "^[0-9.,]*(([.,][-])|([.,][0-9]{2}))?\$",
		'2digitopt' => "^\d+(\,\d{2})?\$",
		'2digitforce' => "^\d+\,\d\d\$",
		'anything' => "^[\d\D]{1,}\$"
	);


	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 *
	 *
	 */
	public function __construct()
	{
		//call aprent
		parent::__construct();
	}


	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * set the table that the input is for
	 *
	 */
	public function input($input)
	{
		$this->_input = $input;
		return $this;
	}

	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * run the validation rules
	 *
	 */
	public function run()
	{
		//temp
		//show($this->_input);

		//first remove fields that are not allowd
		$this->_removeForbiddenFiels();

		//cleanup input
		$this->_cleanupInput();

		//validate
		$this->_validateInput();

		//zijn er errors, dan succes naar false
		if (count($this->_errors) != 0)
			$this->_success = false;

		return $this;
	}


	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * cleanup fields
	 * Trim, strip tags, to lower or upper, remove spaces or characters
	 * By default always tim and striptags
	 *
	 */
	private function _cleanupInput()
	{
		foreach ($this->_input as $field => &$val)
		{
			$rules = $this->json->field->$field->rules;

			//trim
			if (!isset($rules->trim) || $rules->trim === true)
				$val = trim($val);

			//striptags
			if (!isset($rules->striptags) || $rules->striptags === true)
				$val = strip_tags($val);

			//to lower
			if (isset($rules->tolower) && $rules->tolower === true)
				$val = strtolower($val);

			//to upper
			if (isset($rules->toupper) && $rules->toupper === true)
				$val = strtoupper($val);

			//remove spaces
			if (isset($rules->removespaces) && $rules->removespaces === true)
				$val = str_replace(' ', '', $val);

			//remove characters
			if (isset($rules->removechars))
				$val = str_replace(explode(',', $rules->removechars), '', $val);

			//factor
			if ( isset($rules->type) && $rules->type == 'factor')
				$val = prepareAmountForDatabase($val);

			//empty to NULL
			if( $val == '' ) $val = NULL;
		}
	}


	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * validate fields
	 * Run al sorts of validator tests
	 *
	 */
	private function _validateInput()
	{
		foreach ($this->_input as $field => $val)
		{
			//copy for cleaner code
			$rules = $this->json->field->$field->rules;

			//is field empty, but required?
			if ($val == '' && isset($rules->required) && $rules->required === true)
				$this->_errors[$field][] = sprintf('%s is verplicht', $this->json->field->$field->label);

			//field is not empty
			if ($val != '')
			{
				//is var type set
				if (isset($rules->type))
				{
					//integer
					if ($rules->type == 'int' && !preg_match ( $this->_paterns['number'], $val))
						$this->_errors[$field][] = sprintf('<strong>%s</strong> moet een geheel getal zijn (geen decimalen)', $this->json->field->$field->label);

					//decimal
					if (strpos($rules->type, 'decimal') !== false)
					{
						//NL format to International
						$val = prepareAmountForDatabase($val);
						if (!is_numeric($val))
							$this->_errors[$field][] = sprintf('<strong>%s</strong> moet een getal zijn', $this->json->field->$field->label);
					}

					//factor
					if ($rules->type == 'factor' && !Valid::factor($val))
						$this->_errors[$field][] = sprintf('<strong>%s</strong> is geen geldige factor', $this->json->field->$field->label);

					//email
					if ($rules->type == 'email' && !Valid::email($val))
						$this->_errors[$field][] = sprintf('<strong>%s</strong> is geen geldig emailadres', $this->json->field->$field->label);

					//iban
					if ($rules->type == 'iban' && !Valid::iban($val))
						$this->_errors[$field][] = sprintf('<strong>%s</strong> is geen geldig IBAN', $this->json->field->$field->label);

					//datum
					if ($rules->type == 'datum' && !Valid::date($val))
						$this->_errors[$field][] = sprintf('<strong>%s</strong> is geen geldige datum', $this->json->field->$field->label);

					//postcode
					if ($rules->type == 'postcode' && !Valid::postcode($val))
						$this->_errors[$field][] = sprintf('<strong>%s</strong> is geen geldige postcode', $this->json->field->$field->label);

					//btwnr
					if ($rules->type == 'btwnr' && !Valid::btwnr($val))
						$this->_errors[$field][] = sprintf('<strong>%s</strong> is geen geldig BTW nummer', $this->json->field->$field->label);

					//bsn
					if ($rules->type == 'bsn')
					{
						//add zero if needed
						if (strlen($val) == 8)
							$this->_input[$field] = $val = '0' . $val;

						if (!Valid::bsn($val))
							$this->_errors[$field][] = sprintf('<strong>%s</strong> is geen geldig BSN nummer', $this->json->field->$field->label);

					}
				}


				//min length
				if( isset($rules->minlength) )
				{
					if (strlen($val) < $rules->minlength)
						$this->_errors[$field][] = sprintf('<strong>%s</strong> is te kort en moet minstens %s tekens bevatten', $this->json->field->$field->label, $rules->minlength);
				}

				//max length
				if( isset($rules->maxlength) )
				{
					if (strlen($val) > $rules->maxlength)
						$this->_errors[$field][] = sprintf('<strong>%s</strong> is te lang en mag maximaal %s tekens bevatten', $this->json->field->$field->label, $rules->maxlength);
				}

				//min/max value onlu with int|decimal|factor
				if( isset($rules->type) && ( $rules->type == 'int' || $rules->type == 'decimal' || $rules->type == 'factor' ))
				{
					//max value
					if( isset($rules->maxval) )
					{
						if ( $val > $rules->maxval)
							$this->_errors[$field][] = sprintf('De waarde van <strong>%s</strong> is te hoog, maximaal toegestane waarde: %s', $this->json->field->$field->label, $rules->maxval);
					}

					//min value
					if( isset($rules->minval) )
					{
						if ( $val < $rules->minval)
							$this->_errors[$field][] = sprintf('De waarde van <strong>%s</strong> is te laag, minimale waarde: %s', $this->json->field->$field->label, $rules->minval);
					}
				}



				//check radio values
				if( isset($this->json->field->$field->radio) )
				{
					$radio = $this->json->field->$field->radio;
					if( !property_exists($radio->options, $val))
						$this->_errors[$field][] = sprintf('<strong>%s</strong> bevat ongeldige waardes', $this->json->field->$field->label);

				}
			}


		}
	}


	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * remove the fields that are not allow from the input array
	 *
	 */
	private function _removeForbiddenFiels()
	{
		foreach ($this->json->remove as $field)
		{
			unset($this->_input[$field]);
		}
	}

	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Return validated input data
	 *
	 */
	public function data()
	{
		return $this->_input;
	}

	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Toon errors
	 * @return array or boolean
	 */
	public function errors()
	{
		//output for debug
		if (isset($_GET['debug']))
		{
			if ($this->_errors === NULL)
				show('Geen errors');
			else
				show($this->_error);
		}

		if (count($this->_errors) == 0)
			return false;

		return $this->_errors;
	}

	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Return true if tere are no errors
	 *
	 */
	public function success()
	{
		//TEMP show result array
		//show($this->_input);
		//show($this->_errors);

		return $this->_success;
	}
}


?>