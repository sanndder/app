<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/*
 * Parent model om database te laden indien mogelijk
 */
class MY_Model extends CI_Model
{
	public $db_user = NULL;
	public $db_admin = NULL;
	public $logindata = NULL;

	public function __construct()
	{
		//call parent constructor
		parent::__construct();

		//connect to admin database
		$this->db_admin = $this->load->database('admin', TRUE);

		if( defined('EXTERNAL_CONN'))
			$this->_connect_from_external_page();// connect to user database based on WID
		else
			$this->_connect();//try to connect to user database based on logged in user

		//init user
		$this->load->model('user_model', 'user');

		//always load werkgever
		$this->load->model('werkgever_model', 'werkgever');
	}

	/*
	 * Connector om extra database connectie te maken, maar ook om meerdere connecties uit de weg te gaan
	 * Op deze manier wordt het aantal threads beperkt
	 */
	public function _connect()
	{
		if( $this->config->item('db_name') != NULL )
		{
			$config['hostname'] = 'localhost';
			$config['username'] = $this->config->item('db_user');
			$config['password'] = $this->config->item('db_password');
			$config['database'] = $this->config->item('db_name');
			$config['dbdriver'] = 'mysqli';
			$config['dbprefix'] = '';
			$config['pconnect'] = FALSE;
			$config['db_debug'] = TRUE;
			$config['cache_on'] = FALSE;
			$config['cachedir'] = '';
			$config['char_set'] = 'utf8';
			$config['dbcollat'] = 'utf8_general_ci';

			// Grab the super object
			$CI =& get_instance();

			//check if connection exists
			if (isset($CI->db_user) && is_object($CI->db_user) && !empty($CI->db_user->conn_id))
			{
				//copy instance
				$this->db_user = $CI->db_user;
			}
			//else load database
			else
			{
				$this->load->database($config, false, '', 'db_user');//load db_user
				//$this->db_user = $CI->db_user;// first time also copy
			}
		}
		else
			die('Database connection error');
	}

	/*
		 * Connector om extra database connectie te maken, maar ook om meerdere connecties uit de weg te gaan
		 * Op deze manier wordt het aantal threads beperkt
		 */
	public function _connect_from_external_page()
	{
		//only for outside connection
		if( !defined('EXTERNAL_CONN'))
			die('Connection to database failed');

		//is WID set?
		if( !isset($_GET['wid']) )
			die('WID not set');

		//get database credentials
		$sql = "SELECT wid, db_name, db_user, AES_DECRYPT( werkgevers.db_password, UNHEX(SHA2('".DB_SECRET."' ,512)) ) AS db_password
		 		FROM werkgevers WHERE wid = ?
		 		LIMIT 1";

		$query = $this->db_admin->query($sql, array($_GET['wid']));

		if ($query->num_rows() == 0)
			die('WID invallid');

		$werkgever = $query->row_array();


		$config['hostname'] = 'localhost';
		$config['username'] = $werkgever['db_user'];
		$config['password'] = $werkgever['db_password'];
		$config['database'] = $werkgever['db_name'];
		$config['dbdriver'] = 'mysqli';
		$config['dbprefix'] = '';
		$config['pconnect'] = FALSE;
		$config['db_debug'] = TRUE;
		$config['cache_on'] = FALSE;
		$config['cachedir'] = '';
		$config['char_set'] = 'utf8';
		$config['dbcollat'] = 'utf8_general_ci';

		// Grab the super object
		$CI =& get_instance();

		//check if connection exists
		if (isset($CI->db_user) && is_object($CI->db_user) && !empty($CI->db_user->conn_id))
		{
			//copy instance
			$this->db_user = $CI->db_user;
		}
		//else load database
		else
		{
			$this->load->database($config, false, '', 'db_user');//load db_user
			//$this->db_user = $CI->db_user;// first time also copy
		}

	}

}


?>