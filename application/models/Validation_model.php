<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/*
 *
 * Auth model
 * Zorg voor inloggen en beveiliging user
 *
 *
*/

class Auth_model extends MY_Model
{
	public static $regexes = Array(
		'date' => "^[0-9]{1,2}[-/][0-9]{1,2}[-/][0-9]{4}\$",
		'amount' => "^[-]?[0-9]+\$",
		'number' => "^[-]?[0-9,]+\$",
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


	private $_errors = array();

	/*************************************************************************************************
	 * constructor
	 * @return void
	 *
	*/
	public function __construct()
	{
		// Call the Model constructor
		parent::__construct();

	}


	/*************************************************************************************************
	 * hash password for database reset
	 * @return string
	 *
	*/
	public function hashPassword( $string = '' )
	{
		$hash = password_hash( $string, PASSWORD_BCRYPT );
		return $hash;
	}




	/*************************************************************************************************
	 * get errors
	 * @return boolean, array
	 *
	*/
	public function errors()
	{
		if( count($this->_errors) > 0 )
			return $this->_errors;

		return false;
	}


}/* end of class */
