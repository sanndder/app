<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/*
 * Auth model
 * Zorg voor inloggen en beveiliging user
*/

class User_model extends MY_Model
{

	public $username = NULL;
	public $user_id = NULL;

	private $logindata = NULL;
	private $_errors = array();

	/*************************************************************************************************
	 * constructor
	 *
	*/
	public function __construct()
	{
		// Call the Model constructor
		parent::__construct();

		//load session
		$this->logindata = $this->session->userdata('logindata');

		//main user
		if( isset($this->logindata['main']['user_id']) && $this->logindata['main']['user_id'] != NULL )
		{
			$this->user_id = $this->logindata['main']['user_id'];
			$this->username = $this->logindata['main']['naam'];
		}
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
	 * set error
	 * @return void
	 *
	 */
	public function setError( $error = '' )
	{
		$this->_errors[] = $error;
	}

	/*************************************************************************************************
	 * get errors
	 * @return array|boolean
	 *
	*/
	public function errors()
	{
		if( count($this->_errors) > 0 )
			return $this->_errors;

		return false;
	}


}/* end of class */
