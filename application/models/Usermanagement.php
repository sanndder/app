<?php

namespace models;

use models\forms\Validator;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//TODO:Samenvoegen met user class
/*
 * Usermanagement class
 *
 * Users en rechten aanpassen
 *
 */
class Usermanagement {

	/*
	 * @var array
	 */
	private $_error = NULL;

	/*
	 * @var user ID
	 */
	private $_user_id = NULL;

	/*
	 * @var database
	 */
	private $_db_admin = NULL;

	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 *
	 *
	 */
	public function __construct()
	{
		//connect to admin database
		$CI =& get_instance();
		$this->db_admin = $CI->load->database('admin', TRUE);
	}


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * build the form from multilayer array
	 *
	 */
	public function setUserId( $id )
	{
		$this->_user_id = intval( $id );

		if( $this->_user_id == 0 )
			die('Ongeldig user ID');
	}

	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * check of username unique is
	 * @return boolean
	 */
	public function _usernameIsUnique( $username )
	{
		$sql = "SELECT user_id FROM users WHERE username = ? AND user_id != $this->_user_id LIMIT 1";
		$query = $this->db_admin->query($sql, array($username));

		if ($query->num_rows() == 0 )
			return true;

		return false;
	}


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * check of password is valid
	 * @return boolean
	 */
	public function _passwordIsValid( $password )
	{
		$valid = true;
		
		if( strlen($password) < 6 )
		{
			$this->_error[] = 'Het door u gekozen wachtwoord is te kort.';
			$valid = false;
		}
		
		if( strlen($password) > 50 )
		{
			$this->_error[] = 'Het door u gekozen wachtwoord is te lang';
			$valid = false;
		}
		
		if ( !preg_match('/[a-zA-Z]/', $password) || !preg_match('/\d/', $password) || !preg_match('/[^a-zA-Z\d]/', $password) )
		{
			//$this->_error[] = 'Het wachtwoord moet minstens één letter, één getal en één speciaal teken bevatten';
			$this->_error[] = 'Wachtwoord voldoet niet aan de voorwaarden';
			$valid = false;
		}

		
		if( strpos($password, ' ') !== false )
		{
			$this->_error[] = 'Het door u gekozen wachtwoord mag geen spaties bevatten';
			$valid = false;
		}
		
		
		return $valid;
	}



	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 *update password, old pass needed
	 * @return boolean
	 */
	public function updatePassword()
	{
		//get old password
		$sql = "SELECT * FROM users WHERE user_id = ? LIMIT 1";
		$query = $this->db_admin->query($sql, array($this->_user_id));

		$user = $query->row_array();

		//check old password
		if (!password_verify($_POST['password']['old'], $user['password']))
		{
			$this->_error[] = 'Uw huidige wachtwoord is onjuist';
			return false;
		}

		//check password
		if( !$this->_passwordIsValid( $_POST['password']['1'] ))
		{
			return false;
		}

		//zijn beide wachtwoorden gelijk
		if ($_POST['password']['1'] != $_POST['password']['2'])
		{
			$this->_error[] = 'Nieuwe wachtwoorden zijn niet aan elkaar gelijk';
			return false;
		}

		//alles klopt, wijzigen
		$update['password'] = $this->hashPassword($_POST['password']['1']);
		$this->db_admin->where('user_id', $this->_user_id);
		$this->db_admin->update('users', $update);

		return true;
	}


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 * hash password for database reset
	 * @return string
	 *
	 */
	public function hashPassword( $string = '' )
	{
		$hash = password_hash( $string, PASSWORD_BCRYPT );
		return $hash;
	}


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Set user naam en of username (email)
	 * geeft ingevoerde data terug na opschonen
	 * @return array
	 */
	public function setUser()
	{
		$validator = new Validator();
		$validator->table( 'user' )->input( $_POST )->run();

		//schone input terug
		$input = $validator->data();

		//cech if username is unique
		if( !$this->_usernameIsUnique($input['username']) )
		{
			$this->_error['username'][] = sprintf('<strong>%s</strong> al in gebruik. U dient een uniek emailadres te kiezen.', 'Emailadres');
			$notunique = false;
		}

		//geen fouten, nieuwe insert doen wanneer er wijzigingen zijn
		if( $validator->success() && empty($notunique) )
		{
			$update['username'] = $input['username'];
			$update['naam'] = $input['naam'];
			$this->db_admin->where('user_id', $this->_user_id );
			$this->db_admin->update('users', $input);


			if ($this->db_admin->affected_rows() == -1)
				$this->_error[] = 'Database error: update mislukt';
			else
			{
				//update sessie
				$_SESSION['logindata']['main']['user_name'] = $input['naam'];
				$_SESSION['logindata']['main']['username'] = $input['username'];
			}
		}
		//fouten aanwezig
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