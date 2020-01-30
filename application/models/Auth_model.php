<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/*
 * Auth model
 * Zorg voor inloggen en beveiliging user
*/

class Auth_model extends CI_Model
{

	private $_session_timeout = 7200; //session duration in seconds
	private $_max_failed_logins = 10; //max tries
	private $_block_time_minutes = 10; //timeout for login

	private $_errors = array();

	const SALT_SECRET = 'H9mV6iFqiqk1bds-PvUVa2CY#I^$QXMY1a!@xw&p';

	/*************************************************************************************************
	 * constructor
	 *
	*/
	public function __construct()
	{
		// Call the Model constructor
		parent::__construct();

		//connect to admin database, THIS SHOULD BE THE ONLY DUPLICATE ADMIN DB LOAD
		$this->db_admin = $this->load->database('admin', TRUE);
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
	 * login
	 * @return boolean
	 *
	*/
	public function login()
	{
		//eind spaties niet als malafide behandelen
		$_POST['username'] = trim($_POST['username']);

		//extra beveiliging
		$username = $this->_cleanupUsername( $_POST['username'] );

		//check of ip geblockt is
		if( $this->_isIPBlocked() )
		{
			$this->_logFailedAttempt( $_POST['username'], 'ip block' );
			$this->setError('U heeft te veel ongeldige inlogpogingen gedaan, wacht '.$this->_block_time_minutes.' minuten en probeer het opnieuw');
			return false;
		}


		//poging tot sql injection of malafide code?
		if( $username != $_POST['username'] )
		{
			$this->_logFailedAttempt( $_POST['username'], 'username contaminated' );
			$this->setError('Ongeldige gebruikersnaam en/of wachtwoord (0)');
			return false;
		}

		//bind value
		$sql = "SELECT users.*, users_accounts.*, werkgevers.name, werkgevers.type
				FROM users
				LEFT JOIN users_accounts on users.user_id = users_accounts.user_id
				LEFT JOIN werkgevers ON users_accounts.werkgever_id = werkgevers.werkgever_id
				WHERE username = ? LIMIT 1";
		$query = $this->db_admin->query($sql, array($username));
		
		
		//return false if user not found
		if ($query->num_rows() == 0)
		{
			$this->_logFailedAttempt( $_POST['username'], 'username not found' );
			$this->setError('Ongeldige gebruikersnaam en/of wachtwoord (1)');
			return false;
		}
		//get user
		$user = $query->row_array();

		//compare password
		if( !password_verify( $_POST['password'], $user['password'] ) )
		{
			$this->_logFailedAttempt( $_POST['username'], 'wrong password', $user['user_id'] );
			$this->setError('Ongeldige gebruikersnaam en/of wachtwoord (2)');
			return false;
		}
		//SUCCESS

		//unique session ID
		$sid = sha1(uniqid() . generateRandomString(12) ) ;

		//secret maken
		$secret = $this->_secretHash( $user['user_id'], $user['user_type'], $sid );

		//sessie aanmaken
		$session['logindata']['werkgever_id'] = $user['werkgever_id'];
		$session['logindata']['werkgever_naam'] = $user['name'];
		$session['logindata']['werkgever_type'] = $user['type'];
		$session['logindata']['user_type'] = $user['user_type'];
		$session['logindata']['account_id'] = $user['id'];
		$session['logindata']['main']['user_id'] = $user['user_id'];
		$session['logindata']['main']['user_name'] = $user['naam'];
		$session['logindata']['main']['username'] = $user['username'];
		$session['logindata']['main']['user_type'] = $user['user_type'];
		$session['logindata']['main']['sid'] = $sid;
		
		if( $user['user_type'] == 'uitzender' ) $session['logindata']['main']['uitzender_id'] =  $user['uitzender_id'];
		if( $user['user_type'] == 'inlener' ) $session['logindata']['main']['inlener_id'] =  $user['inlener_id'];
		if( $user['user_type'] == 'werknemer' ) $session['logindata']['main']['werknemer_id'] =  $user['werknemer_id'];
		if( $user['user_type'] == 'zzp' ) $session['logindata']['main']['zzp_id'] =  $user['zzp_id'];

		//session to database
		$this->_loginSessionToDatabase( $user['user_id'], $secret, $sid );

		$this->session->set_userdata( $session );
		
		return true;
	}


	/*************************************************************************************************
	 * Zonder login pagina's benaderen
	 *
	 * @return void
	 */
	public function validate_nologin()
	{
		
		if( isset($_SESSION['logindata']['wid']) && isset($_SESSION['logindata']['wg_hash']) )
		{
			$_GET['wid'] = $_SESSION['logindata']['wid'];
			$_GET['wg_hash'] = $_SESSION['logindata']['wg_hash'];
		}
		
		if( !isset($_GET['wid']) || !isset($_GET['wg_hash']) )
			$this->logout( NULL, NULL );
		
		//werkgever ophalen
		$sql = "SELECT werkgevers.*, AES_DECRYPT( werkgevers.db_password, UNHEX(SHA2('".DB_SECRET."' ,512)) ) AS db_password
				FROM werkgevers
				WHERE wid = ? AND wg_hash = ?
				LIMIT 1";
		$query = $this->db_admin->query( $sql, array( $_GET['wid'],$_GET['wg_hash']) );
		
		//werkgever niet gevonden
		if( $query->num_rows() == 0 )
			$this->logout( NULL, NULL );
		
		//data werkgever
		$data = $query->row_array();
		
		//databse gegevens naar config
		$this->config->set_item( 'db_name', $data['db_name'] );
		$this->config->set_item( 'db_user', $data['db_user'] );
		$this->config->set_item( 'db_password', $data['db_password'] );
		
		//sessie aanmaken
		$session['logindata']['werkgever_id'] = $data['werkgever_id'];
		$session['logindata']['wid'] = $data['wid'];
		$session['logindata']['wg_hash'] = $data['wg_hash'];
		$session['logindata']['user_type'] = 'external';

		$this->session->set_userdata( $session );
	}



	/*************************************************************************************************
	 * switch to other account
	 *
	 * @return void
	 */
	public function switchAccount( $account_id = NULL )
	{
		//alleen bij geldig ID
		if( $account_id === NULL ) return NULL;
		
		$logindata = $this->session->userdata('logindata');

		$sql = "SELECT * FROM users_accounts LEFT JOIN werkgevers ON users_accounts.werkgever_id = werkgevers.werkgever_id WHERE id = ? AND user_id = ? AND users_accounts.deleted = 0 LIMIT 1";
		$query = $this->db_admin->query( $sql, array( $account_id, $logindata['main']['user_id'] ) );
		
		//als er een record bestaat dan switchen
		if( $query->num_rows() > 0 )
		{
			$data = $query->row_array();
			
			//sessie veranderen
			$session['logindata']['prev_werkgever_id'] = $_SESSION['logindata']['werkgever_id'];;
			$session['logindata']['werkgever_id'] = $data['werkgever_id'];
			$session['logindata']['werkgever_naam'] = $data['name'];
			$session['logindata']['werkgever_type'] = $data['type'];
			$session['logindata']['user_type'] = $data['user_type'];
			$session['logindata']['account_id'] = $account_id;
			$session['logindata']['main']['user_id'] = $data['user_id'];
			$session['logindata']['main']['user_name'] = $logindata['main']['user_name'];
			$session['logindata']['main']['username'] = $logindata['main']['username'];
			$session['logindata']['main']['user_type'] = $data['user_type'];
			$session['logindata']['main']['sid'] = $logindata['main']['sid'];
			
			if( $data['user_type'] == 'uitzender' ) $session['logindata']['main']['uitzender_id'] =  $data['uitzender_id'];
			if( $data['user_type'] == 'inlener' ) $session['logindata']['main']['inlener_id'] =  $data['inlener_id'];
			if( $data['user_type'] == 'werknemer' ) $session['logindata']['main']['werknemer_id'] =  $data['werknemer_id'];
			if( $data['user_type'] == 'zzp' ) $session['logindata']['main']['zzp_id'] =  $data['zzp_id'];
			
			
			$this->session->set_userdata( $session );
		}
	
	}
	
	
	/*************************************************************************************************
	 * check login
	 * @return void
	 */
	public function check( $logout = false )
	{
		//get session
		$logindata = $this->session->userdata('logindata');
		
		//extra check
		if( !isset($logindata['main']['user_id']))
			$this->logout( '', '', 'user not found');
		
		//check main user
		$user_id = $logindata['main']['user_id'];
		$user_type = $logindata['main']['user_type'];
		$sid = $logindata['main']['sid'];
		$account_id = $logindata['account_id'];

		//user wants to logout
		if ($logout)
			$this->logout( $user_id, $sid, 'user action');
		
		//TODO: move to other class
		//$sql = "UPDATE werkgevers SET db_password = AES_ENCRYPT('0&2Bhzy4', UNHEX(SHA2('".DB_SECRET."',512))) WHERE werkgever_id = 3"; //_d00zRj0 for simple-internet-solutions
		//$query = $this->db_admin->query($sql);
		//show($sql);
		//die();

		//get login session
		$sql = "SELECT users.*, users_sessions.*, werkgevers.*, AES_DECRYPT( werkgevers.db_password, UNHEX(SHA2('".DB_SECRET."' ,512)) ) AS db_password, users_accounts.*
				FROM users
				LEFT JOIN users_accounts ON  users_accounts.user_id = users.user_id
				LEFT JOIN users_sessions ON users.user_id = users_sessions.user_id 
				LEFT JOIN werkgevers ON users_accounts.werkgever_id = werkgevers.werkgever_id
				WHERE users.user_id = ? AND sid = ? AND user_type = ? AND users_accounts.id = ? AND session_logout IS NULL
				LIMIT 1";

		$query = $this->db_admin->query($sql, array($user_id, $sid, $user_type, $account_id));

		//logout
		if ($query->num_rows() == 0)
			$this->logout( $user_id, $sid, 'session not found');

		//get row
		$session = $query->row_array();

		//databse gegevens naar config
		$this->config->set_item( 'db_name', $session['db_name'] );
		$this->config->set_item( 'db_user', $session['db_user'] );
		$this->config->set_item( 'db_password', $session['db_password'] );

		//check timeout
		if( strtotime($session['session_last_action']) + $this->_session_timeout < time() )
			$this->logout( $user_id, $sid, 'timeout');

		//get secret hash
		$secret = $this->_secretHash( $user_id, $session['user_type'], $sid );

		//check secret
		if( $session['secret'] != $secret )
			$this->logout( $user_id, $sid, 'invalid secret');


		//all good, update last action
		$update['session_last_action'] = date('Y-m-d H:i:s');
		$this->db_admin->where( 'sid', $sid );
		$this->db_admin->update('users_sessions', $update);
	}


	/*************************************************************************************************
	 * store login session in database
	 * @return void
	 */
	public function logout( $user_id, $sid, $reason = '', $redirect = true )
	{
		//end database session
		$update['session_logout'] = date('Y-m-d H:i:s');
		$update['session_logout_reason'] = $reason;

		$this->db_admin->where( 'user_id', $user_id );
		$this->db_admin->where( 'sid', $sid );
		$this->db_admin->update('users_sessions', $update);

		//destroy browser session
		$this->session->unset_userdata('logindata');
		$this->session->unset_userdata('entiteit_id');
		
		//store url in session
		$this->session->set_userdata('ref_url', base_url(uri_string()) );

		//redirect to
		$url = $this->config->item('base_url') . 'login';

		if( $redirect )
		{
			redirect( $url, 'location' );
			die();
		}
	}

	/*************************************************************************************************
	 * is IP blocked
	 * @return boolean
	 */
	public function _isIPBlocked()
	{
		$sql = "SELECT id FROM login_blacklist_ip WHERE block_untill > NOW() AND ip = '".$_SERVER['REMOTE_ADDR']."' LIMIT 1";
		$query = $this->db_admin->query($sql);

		if ($query->num_rows() > 0)
			return true;

		return false;
	}

	/*************************************************************************************************
	 * store failed attempt
	 * @return void
	 */
	public function _logFailedAttempt( $username = '', $reason = '', $user_id = NULL )
	{
		$device_hash = md5($_SERVER['HTTP_USER_AGENT'].$_SERVER['REMOTE_ADDR']);

		$insert['user_id'] = $user_id;
		$insert['username'] = $username;
		$insert['ip'] = $_SERVER['REMOTE_ADDR'];
		$insert['browser'] = $_SERVER['HTTP_USER_AGENT'];
		$insert['device_hash'] = $device_hash;
		$insert['reason'] = $reason;

		$this->db_admin->insert('login_attempts', $insert);

		//count failed logins
		$sql = "SELECT count(id) AS count, ip FROM login_attempts WHERE reason != 'ip block' AND device_hash = '$device_hash' AND timestamp > (NOW() - INTERVAL 10 MINUTE)";
		$query = $this->db_admin->query($sql);

		$row = $query->row_array();

		//Na te veel pogingen IP blokken
		//TODO email alert malafide pogingen
		if( $row['count'] > $this->_max_failed_logins )
		{
			unset($insert);

			$date = new DateTime();
			$timestamp = $date->modify("+$this->_block_time_minutes minutes")->format("Y-m-d H:i:s");

			$insert['ip'] = $row['ip'];
			$insert['block_untill'] = $timestamp;

			$this->db_admin->insert('login_blacklist_ip', $insert);
		}

	}



	/*************************************************************************************************
	 * store login session in database
	 * @return void
	 */
	public function _loginSessionToDatabase( $user_id, $secret, $sid )
	{
		$insert['user_id'] = $user_id;
		$insert['secret'] = $secret;
		$insert['sid'] = $sid;
		$insert['ip'] = $_SERVER['REMOTE_ADDR'];
		$insert['browser'] = $_SERVER['HTTP_USER_AGENT'];
		$insert['session_last_action'] = date("Y-m-d H:i:s");

		$this->db_admin->insert('users_sessions', $insert);
	}


	/*************************************************************************************************
	 * secret hash maken
	 * @return string
	 *
	 */
	public function _secretHash( $user_id, $user_type, $sid )
	{
		//TEMP LOCAL
		if( ENVIRONMENT == 'development' )
			$_SERVER['REMOTE_ADDR'] = 1;

		$string =  $user_id . $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT'] . $user_type  . $sid;
		$secret = hash( 'sha256', $string . self::SALT_SECRET );
		return $secret;
	}


	/*************************************************************************************************
	 * clean up username
	 * @return string
	 *
	 */
	public function _cleanupUsername( $val = '' )
	{
		//tags er uit
		$return = strip_tags($val);

		//alle gekke karakters eruit
		$search = array('"', "'", '*', ';', 'SELECT ', 'DELETE ', 'UPDATE ', 'INSERT ', 'DROP ', 'TRUNCATE ', ' ');
		$return = str_ireplace( $search, '', $return );

		return $return;

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
