<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/*
 * User model
 *
*/

class User_model extends MY_Model
{

	public $user_name = NULL;
	public $username = NULL;
	public $user_id = NULL;
	public $user_type = NULL;
	public $werkgever_id = NULL;

	private $_errors = array();

	/*************************************************************************************************
	 * constructor
	 *
	*/
	public function __construct()
	{
		// Call the Model constructor
		parent::__construct();

		//user as global var
		$this->logindata = $this->session->userdata('logindata');

		//main user
		if( isset($this->logindata['main']['user_id']) && $this->logindata['main']['user_id'] != NULL )
		{
			$this->user_id = $this->logindata['main']['user_id'];
			$this->username = $this->logindata['main']['username'];
			$this->user_name = $this->logindata['main']['user_name'];
			$this->user_type = $this->logindata['main']['user_type'];
			$this->werkgever_id = $this->logindata['werkgever_id'];

			$this->smarty->assign( 'user_name' , $this->user_name );

		}
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
