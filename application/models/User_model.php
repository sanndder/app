<?php use models\utils\DBhelper;

if (!defined('BASEPATH'))
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
	public $active_user_id = NULL;
	public $id = NULL; // alias
	public $user_type = NULL;
	public $account_id = NULL;
	public $werkgever_id = NULL;
	public $werkgever_naam = NULL;
	public $werkgever_type = NULL;
	public $werknemer_id = NULL;
	public $inlener_id = NULL;
	public $uitzender_id = NULL;
	//public $logindata = NULL;
	
	public $user_accounts = NULL; //gelinkte accounts

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
		$logindata = $this->session->userdata('logindata');

		//main user
		if( isset($logindata['main']['user_id']) && $logindata['main']['user_id'] != NULL )
		{
			//altijd hoofd ID gebruiken
			$this->user_id = $logindata['main']['user_id'];
			$this->active_user_id = $this->user_id;
			
			//is er een override?
			if( isset($logindata['override']) )
			{
				$this->username = $logindata['override']['username'];
				$this->user_name = $logindata['override']['user_name'];
				$this->user_type = $logindata['override']['user_type'];
				
				$this->uitzender_id = $logindata['override']['uitzender_id'] ?? NULL;
				$this->inlener_id = $logindata['override']['inlener_id'] ?? NULL;
				$this->werknemer_id = $logindata['override']['werknemer_id'] ?? NULL;
				$this->zzp_id = $logindata['override']['zzp_id'] ?? NULL;
				
				$this->active_user_id = $logindata['override']['user_id'];
			}
			else
			{
				$this->username = $logindata['main']['username'];
				$this->user_name = $logindata['main']['user_name'];
				$this->user_type = $logindata['main']['user_type'];
				
				$this->uitzender_id = $logindata['main']['uitzender_id'] ?? NULL;
				$this->inlener_id = $logindata['main']['inlener_id'] ?? NULL;
				$this->werknemer_id = $logindata['main']['werknemer_id'] ?? NULL;
				$this->zzp_id = $logindata['main']['zzp_id'] ?? NULL;
			}
			
			$this->werkgever_id = $logindata['werkgever_id'];
			$this->werkgever_naam = $logindata['werkgever_naam'];
			$this->werkgever_type = $logindata['werkgever_type'];
			$this->account_id = $logindata['account_id'];

			$this->smarty->assign( 'user_name' , $this->user_name );
		}
		//external
		elseif( isset($logindata['user_type']) && isset($logindata['werkgever_id']))
		{
			$this->user_type = $logindata['user_type'];
			$this->werkgever_id = $logindata['werkgever_id'];
			$this->user_id = 0;
		}

		//niet voor external
		if( $this->user_type !== 'external' )
			$this->_getLinkedAccounts();
		
		$this->id = $this->user_id;
	}
	
	/*************************************************************************************************
	 * get linked accounts to switch to
	 * TODO: dit kan wel handiger
	 * TODO: bij login als ook andere lijst weergeven. Account ID moet mee vanuit main en override
	 * @return void
	 *
	 */
	private function _getLinkedAccounts()
	{
		//accounts laden
		$sql = "SELECT users_accounts.id, users_accounts.user_id, users_accounts.werkgever_id, users_accounts.uitzender_id, users_accounts.inlener_id, users_accounts.werknemer_id, users_accounts.user_type, users_accounts.admin, w.name
				FROM users_accounts
				LEFT JOIN werkgevers w on users_accounts.werkgever_id = w.werkgever_id
				WHERE user_id = $this->active_user_id AND users_accounts.deleted = 0";
		
		$query = $this->db_admin->query( $sql );
		$this->user_accounts = DBhelper::toArray( $query, 'id', 'NULL' );
	}


	/*************************************************************************************************
	 * TODO temp!!!
	 * @return void
	 *
	 */
	public function updateUsers()
	{
		$sql= "SELECT `user_id`, `werkgever_id`, `uitzender_id`, `inlener_id`, `werknemer_id`, `user_type`, `admin` FROM users";
		$query = $this->db_admin->query( $sql );
		
		foreach( $query->result_array() as $row )
		{
			$data[] = $row;
		}
		
		show($data);
		
		$this->db_admin->insert_batch( 'users_accounts', $data );
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
