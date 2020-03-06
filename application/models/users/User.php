<?php

namespace models\users;

use models\Connector;
use models\email\Email;
use models\forms\Valid;
use models\forms\Validator;
use models\utils\DBhelper;

if (!defined('BASEPATH'))
	exit('No direct script access allowed');


/*
 * User class
 *
 *
 *
 */

class User extends Connector
{
	
	/**
	 * @var int
	 */
	private $user_id;
	
	/**
	 * admin database object
	 * @var object
	 */
	private $db_admin = NULL;
	
	/**
	 * user data
	 */
	private $_username = NULL;
	private $_password = NULL;
	private $_user_type = NULL;
	private $_admin = NULL;
	private $_naam = NULL;
	private $_email = NULL;
	private $_email_confirmed = NULL;
	private $_new_key = NULL;
	private $_new_key_expires = NULL;
	private $_reset_key = NULL;
	private $_reset_key_expires = NULL;
	
	private $_data = NULL;
	/**
	 * @var array
	 */
	private $_error = NULL;

	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * constructor
	 */
	public function __construct( $user_id = NULL )
	{
		//call parent constructor for connecting to database
		parent::__construct();
		
		//eerst database laden
		$CI = &get_instance();// Grab the super object
		$this->db_admin = $CI->load->database('admin', TRUE);
		
		//set ID
		if( $user_id !== NULL )
			$this->setID($user_id);
		
		//user ophalen
		$this->_load();
		
	}


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Set ID
	 */
	public function setID($user_id)
	{
		$this->user_id = intval($user_id);
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * get ID
	 */
	public function id()
	{
		return $this->user_id;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * get ID
	 */
	public function data()
	{
		return $this->_data;
	}

	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Set ID
	 */
	public function _load()
	{
		if($this->user_id === NULL )
			return NULL;
		
		$sql = "SELECT users.*, users_accounts.* ,users.created_by
				FROM users
				LEFT JOIN users_accounts ON users.user_id = users_accounts.user_id
				WHERE users.user_id = $this->user_id AND users_accounts.werkgever_id = ".$this->user->werkgever_id." LIMIT 1";
		
		$query = $this->db_admin->query( $sql );

		if( $query->num_rows() > 0 )
		{
			$data = $query->row_array();
			
			$this->_username = $data['username'];
			$this->_password = $data['password'];
			$this->_user_type = $data['user_type'];
			$this->_admin = $data['admin'];
			$this->_naam = $data['naam'];
			$this->_email = $data['email'];
			$this->_email_confirmed = $data['email_confirmed'];
			$this->_new_key = $data['new_key'];
			$this->_new_key_expires = $data['new_key_expires'];
			$this->_reset_key = $data['reset_key'];
			$this->_reset_key_expires = $data['reset_key_expires'];
			
			$users = UserGroup::list( array($data['created_by']) );
			$data['created_by'] = $users[$data['created_by']];
			
			$this->_data = $data;
			
		}
		
	}


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * ID ophalen aan de hand van de hash
	 * @return void
	 */
	public function getByNewHash( $hash )
	{
	
		$sql = "SELECT user_id FROM users WHERE new_key = ? AND deleted = 0";
		$query = $this->db_admin->query( $sql, array( $hash ) );
		
		if( $query->num_rows() > 0 )
		{
			$data = $query->row_array();
			$this->setID( $data['user_id']);
			$this->_load();
		}
		else
			$this->_error[] = 'Geen gebruiker gevonden!';
		
		//wanneer er op de link wordt geklikt is het emailadres ook bevestigd
		$this->confirmEmail();
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * ID ophalen aan de hand van de hash
	 * @return void
	 */
	public function getByResetHash( $hash )
	{
		$sql = "SELECT user_id FROM users WHERE reset_key = ? AND deleted = 0";
		$query = $this->db_admin->query( $sql, array( $hash ) );
		
		if( $query->num_rows() > 0 )
		{
			$data = $query->row_array();
			$this->setID( $data['user_id']);
			$this->_load();
		}
		else
			$this->_error[] = 'Geen gebruiker gevonden!';
	}


	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * confirm email
	 * @return void
	 */
	public function confirmEmail()
	{
		$update['email_confirmed'] = 1;
		$this->db_admin->where( 'user_id', $this->user_id );
		$this->db_admin->update( 'users', $update );
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * new key al verouderd?
	 * @return boolean
	 */
	public function newKeyExpired()
	{
		if( $this->_new_key_expires === NULL )
		{
			$this->_error[] = 'Deze link is ongeldig of al een keer gebruikt!';
			return true;
		}
		
		if( date('Y-m-d H:i:s') > $this->_new_key_expires )
		{
			$this->_error[] = 'De aanmeldingslink is verlopen. Neem contact met ons op.';
			return true;
		}
		
		return false;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * reset key al verouderd?
	 * @return boolean
	 */
	public function resetKeyExpired()
	{
		if( $this->_reset_key_expires === NULL )
		{
			$this->_error[] = 'Deze link is ongeldig of al een keer gebruikt!';
			return true;
		}
		
		if( date('Y-m-d H:i:s') > $this->_reset_key_expires )
		{
			$this->_error[] = 'De herstellink is verlopen. Neem contact met ons op.';
			return true;
		}
		
		return false;
	}

	
	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * hash password
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
	public function updatePassword( $first_time = false )
	{
		//get old password
		if( $first_time === false )
		{
			$sql = "SELECT * FROM users WHERE user_id = ? LIMIT 1";
			$query = $this->db_admin->query( $sql, array( $this->_user_id ) );
			
			$user = $query->row_array();
			
			//check old password
			if( !password_verify( $_POST['password']['old'], $user['password'] ) )
			{
				$this->_error[] = 'Uw huidige wachtwoord is onjuist';
				return false;
			}
		}
		
		//check password
		if( !$this->_passwordIsValid( $_POST['password']['1'] ))
			return false;
		
		//zijn beide wachtwoorden gelijk
		if ($_POST['password']['1'] != $_POST['password']['2'])
		{
			$this->_error[] = 'Nieuwe wachtwoorden zijn niet aan elkaar gelijk';
			return false;
		}
		
		//alles klopt, wijzigen
		$update['password'] = $this->hashPassword($_POST['password']['1']);
		
		$this->db_admin->where('user_id', $this->user_id);
		$this->db_admin->update('users', $update);
		
		//new key weggooien
		if( $this->db_user->affected_rows() == 1 )
		{
			$update2['new_key'] = NULL;
			$update2['new_key_expires'] = NULL;
			
			$this->db_admin->where('user_id', $this->user_id);
			$this->db_admin->update('users', $update2);
		}
		
		return true;
	}


	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * wachtwoord default
	 * @return bool
	 */
	public function defaultPassword()
	{
		$sql = "UPDATE users SET password = '".$this->hashPassword('test')."' WHERE user_id = $this->user_id LIMIT 1";
		$this->db_admin->query( $sql );
		
		if( $this->db_admin->affected_rows() != -1 )
			return true;
		return false;
		
	}


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * wachtwoord resetten
	 * @return bool
	 */
	public function resetPassword()
	{
		$sql = "UPDATE users SET password = password_backup WHERE user_id = $this->user_id LIMIT 1";
		$query = $this->db_admin->query( $sql );
		
		if( $this->db_admin->affected_rows() != -1 )
			return true;
		return false;
		
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * wachtwoord backuppen
	 * @return bool
	 */
	public function backupPassword()
	{
		$sql = "UPDATE users SET password_backup = password WHERE user_id = $this->user_id LIMIT 1";
		$query = $this->db_admin->query( $sql );
		
		if( $this->db_admin->affected_rows() != -1 )
			return true;
		return false;
		
	}

	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Data voor nieuwe inlener of uitzender ophalen
	 * @return array
	 */
	public function initNewUser()
	{
		if($_GET['user_type'] == 'uitzender' )
		{
			$sql = "SELECT uitzenders_contactpersonen.*, uitzenders_bedrijfsgegevens.bedrijfsnaam
					FROM uitzenders_contactpersonen
					LEFT JOIN uitzenders_bedrijfsgegevens ON uitzenders_bedrijfsgegevens.uitzender_id = uitzenders_contactpersonen.uitzender_id
					WHERE uitzenders_contactpersonen.tekenbevoegd = 1 AND uitzenders_bedrijfsgegevens.deleted = 0 AND uitzenders_contactpersonen.deleted = 0 AND uitzenders_contactpersonen.uitzender_id = ".intval(($_GET['id']));
					
			$query = $this->db_user->query( $sql );

			
			//bij meerdere contactpersonen array
			if( $query->num_rows() > 1 )
			{
				foreach( $query->result_array() as $data )
				{
					$return['uitzender_id'] = $data['uitzender_id'];
					$return['email'] = $data['email'];
					$return['bedrijfsnaam'] = $data['bedrijfsnaam'];
					
					$naam = $data['voornaam'];
					if( $data['voornaam'] != '' )
						$naam .= ' ' . $data['tussenvoegsel'];
					$naam .= ' ' . $data['achternaam'];
					
					$return['naam'][] = $naam;
				}
				
			}
			else
			{
				$data = $query->row_array();
				
				$return['uitzender_id'] = $data['uitzender_id'];
				$return['email'] = $data['email'];
				$return['bedrijfsnaam'] = $data['bedrijfsnaam'];
				
				$return['naam'] = $data['voornaam'];
				if( $data['voornaam'] != '' )
					$return['naam'] .= ' ' . $data['tussenvoegsel'];
				$return['naam'] .= ' ' . $data['achternaam'];
			}
			
		}
		
		if($_GET['user_type'] == 'inlener' )
		{
			$sql = "SELECT inleners_contactpersonen.*, inleners_bedrijfsgegevens.bedrijfsnaam
					FROM inleners_contactpersonen
					LEFT JOIN inleners_bedrijfsgegevens ON inleners_bedrijfsgegevens.inlener_id = inleners_contactpersonen.inlener_id
					WHERE inleners_contactpersonen.tekenbevoegd = 1 AND inleners_bedrijfsgegevens.deleted = 0 AND inleners_contactpersonen.deleted = 0 AND inleners_contactpersonen.inlener_id = ".intval(($_GET['id']));
			
			$query = $this->db_user->query( $sql );
			$data = $query->row_array();
			
			$return['inlener_id'] = $data['inlener_id'];
			$return['email'] = $data['email'];
			$return['bedrijfsnaam'] = $data['bedrijfsnaam'];
			$return['naam'] = $data['voornaam'];
			if( $data['tussenvoegsel'] != '' )
				$return['naam'] .= ' ' . $data['tussenvoegsel'];
			$return['naam'] .= ' ' . $data['achternaam'];
			
			//bij meerdere contactpersonen geen naam bouwen
			if( $query->num_rows() > 1 )
				$return['naam'] = '';
		}
		
		if($_GET['user_type'] == 'werknemer' )
		{
			$sql = "SELECT werknemers_gegevens.*
					FROM werknemers_gegevens
					WHERE werknemers_gegevens.deleted = 0 AND werknemers_gegevens.werknemer_id = ".intval(($_GET['id']) . " LIMIT 1" );
			
			$query = $this->db_user->query( $sql );
			$data = $query->row_array();
			
			$return['werknemer_id'] = $data['werknemer_id'];
			$return['email'] = $data['email'];
			$return['naam'] = $data['voornaam'];
			if( $data['tussenvoegsel'] != '' )
				$return['naam'] .= ' ' . $data['tussenvoegsel'];
			$return['naam'] .= ' ' . $data['achternaam'];
			$return['bedrijfsnaam'] = $return['naam'];
		}
		
		return $return;
	}

	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Nieuwe gebruiker aanmaken
	 * @return array | bool
	 */
	public function add()
	{
		$validator = new Validator();
		$validator->table('user')->input($_POST)->run();
		
		$input = $validator->data();
	
		//geen fouten, nieuwe insert doen wanneer er wijzigingen zijn
		if ($validator->success())
		{
			//extra check op email
			if( !$this->emailIsUnique($input['username']) )
			{
				$this->_error['username'] = 'Emailadres is al in gebruik';
				return $input;
			}
			
			//nieuwe user
			
			$insert['username'] = $input['username'];
			$insert['email'] = $input['username'];
			$insert['naam'] = $input['naam'];
		
			$insert['password'] = NULL;
			
			$insert['created_by'] = $this->user->user_id;
			$insert['new_key'] = md5( $input['username'] .time() );
			$insert['new_key_expires'] = date('Y-m-d', strtotime('+5 days'));
			
			$this->db_admin->insert( 'users', $insert );
			
			if( $this->db_admin->insert_id() > 0 )
			{
				$this->setID($this->db_admin->insert_id());
				
				$insert_account['werkgever_id'] = $this->user->werkgever_id;
				$insert_account['admin'] = $input['admin'];
				$insert_account['user_id'] = $this->db_admin->insert_id();
				
				//per user_type verschillend
				if( $_POST['user_type'] == 'werkgever' )
					$insert_account['user_type'] = 'werkgever';
				
				if( $_POST['user_type'] == 'uitzender' )
				{
					$insert_account['user_type'] = 'uitzender';
					$insert_account['uitzender_id'] = intval($_POST['id']);
				}
				
				if( $_POST['user_type'] == 'inlener' )
				{
					$insert_account['user_type'] = 'inlener';
					$insert_account['inlener_id'] = intval($_POST['id']);
				}
				
				if( $_POST['user_type'] == 'werknemer' )
				{
					$insert_account['user_type'] = 'werknemer';
					$insert_account['werknemer_id'] = intval($_POST['id']);
				}
				
				$this->db_admin->insert( 'users_accounts', $insert_account );
				
				$this->_load();
				
				//welkom voor werknemers
				if( $_POST['user_type'] == 'werknemer' )
					$this->sendWelkomsEmailWerknemer();
				else
					//welkom voor de rest
					$this->sendWelkomsEmail();
			}
			else
			{
				$this->_error[] = 'Gebruiker kon niet worden aangemaakt';
			}
		}
		else
		{
			$this->_error = $validator->errors();
		}
		
		return $input;
	}
	
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * send reset password
	 * TODO: uit naam van Devis sturen
	 */
	public function resetPasswordEmail( $email ) :bool
	{
		//calidate email
		if( !Valid::email($email) )
		{
			$this->_error[] = 'Ongeldig emailadres';
			return false;
		}
		
		//find user
		$sql = "SELECT users.*, users_accounts.*, werkgevers.name, werkgevers.wid, werkgevers.wg_hash
				FROM users
				LEFT JOIN users_accounts ON users.user_id = users_accounts.user_id
				LEFT JOIN werkgevers ON werkgevers.werkgever_id = users_accounts.werkgever_id
				WHERE email = ?";
		
		$query = $this->db_admin->query( $sql, array( $email ) );
		if( $query->num_rows() == 0 )
		{
			$this->_error[] = 'Emailadres is niet gevonden';
			return false;
		}
	
		$data = $query->row_array();
		
		//reset key
		$data['reset_key'] = md5( time() . $data['username'] .  $data['timestamp']);
		$data['reset_key_expires'] = date('Y-m-d', strtotime('+7 days'));
		
		//naar database
		$update['reset_key'] = $data['reset_key'];
		$update['reset_key_expires'] = $data['reset_key_expires'];
		$this->db_admin->where( 'user_id', $data['user_id'] );
		$this->db_admin->update( 'users', $update );
		
		$this->sendResetEmail($data);
		
		return true;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * reset email sturen
	 *
	 * @return boolean
	 */
	public function sendResetEmail( $user )
	{
		$link = "https://www.devisonline.nl/usermanagement/resetuser?wid=".$user['wid']."&wg_hash=".$user['wg_hash'] . "&user=" . $user['reset_key'];
		
		$email = new Email();
		
		$to['email'] = $user['email'];
		$to['name'] = $user['naam'];
		
		$email->to( $to );
		$email->setSubject('Wachtwoord herstel Devis Online');
		$email->setTitel('Nieuw wachtwoord aanvragen');
		$email->setBody('U heeft verzocht om uw wachtwoord voor <b>Devis Online</b> opnieuw in te stellen. Klik op onderstaande link om uw wachtwoord opnieuw in te stellen. De link is 7 dagen geldig.
						<br /><br />
						<a href="'.$link.'">
						Wachtwoord opnieuw instellen</a>
						<br /><br />
						Heeft u niet verzocht om uw wachtwoord te herstellen? Neem dan contact met ons op, mogelijk probeert iemand uw account te misbruiken.
						Met vriendelijke groet,<br />Abering HR Services');
		$email->useHtmlTemplate( 'devis' );
		$email->delay( 0 );
		$email->send();
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * welkoms email sturen naar nieuwe user
	 *
	 * @return boolean
	 */
	public function sendWelkomsEmail()
	{
		$link = "https://www.devisonline.nl/usermanagement/newuser?wid=".$this->werkgever->wid()."&wg_hash=".$this->werkgever->hash() . "&user=" . $this->_new_key;
		
		$email = new Email();
		
		$to['email'] = $this->_email;
		$to['name'] = $this->_naam;
		
		$email->to( $to );
		$email->setSubject('Account Devis Online');
		$email->setTitel('Welkom bij Devis Online');
		$email->setBody('Er is een account voor u aangemaakt in <b>Devis Online</b>. In deze email vind u alles wat u nodig heeft om uw account te activeren.
						<br /><br />
						<b>Gebruikersnaam: </b>'.$this->_email.'
						<br /><br />
						<a href="'.$link.'">
						'.$link.'</a>
						<br /><br />
						Gebruikt de bovenstaande link om uw wachtwoord aan te maken en uw account te activeren. Deze link verloopt over 5 dagen.
						<br /><br />Wanneer u vragen heeft kunt u contact met ons opnemen.<br /><br />Met vriendelijke groet,<br />Abering Uitzend B.V.');
		$email->useHtmlTemplate( 'default' );
		$email->delay( 0 );
		$email->send();
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * welkoms email sturen naar nieuwe werknemer
	 *
	 * @return boolean
	 */
	public function sendWelkomsEmailWerknemer()
	{
		$link = "https://www.devisonline.nl/usermanagement/newuser?wid=".$this->werkgever->wid()."&wg_hash=".$this->werkgever->hash() . "&user=" . $this->_new_key;
		
		$email = new Email();
		
		$to['email'] = $this->_email;
		$to['name'] = $this->_naam;
		
		$email->to( $to );
		$email->setSubject('Account Abering Uitzend B.V.');
		$email->setTitel('Welkom bij Abering Uitzend B.V.');
		$email->setBody('Er is een account voor u aangemaakt in <b>Devis Online</b>, de online portal van Abering Uitzend B.V.. In deze email vind u alles wat u nodig heeft om uw account te activeren.
						<br /><br />
						<b>Gebruikersnaam: </b>'.$this->_email.'
						<br /><br />
						<a href="'.$link.'">
						'.$link.'</a>
						<br /><br />
						Gebruikt de bovenstaande link om uw wachtwoord aan te maken en uw account te activeren. Deze link verloopt over 5 dagen.
						<br /><br />
						Met uw online account kunt u al uw gegevens inzien, loonstroken downloaden, zich ziekmelden en uw instellingen aanpassen.
						<br /><br />Wanneer u vragen heeft kunt u contact met ons opnemen.<br /><br />Met vriendelijke groet,<br />Abering Uitzend B.V.');
		$email->useHtmlTemplate( 'default' );
		$email->delay( 0 );
		$email->send();
	}

	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * check of emailadres al bestaat
	 * @return boolean
	 */
	public function emailIsUnique( $email )
	{
		//voor development
		if( $email == 'hsmeijering@home.nl' )
			return true;
		
		$sql = "SELECT email FROM users WHERE email = '$email' AND deleted = 0 LIMIT 1";
		$query = $this->db_admin->query( $sql );
		
		if( $query->num_rows() > 0 )
			return false;
		
		return true;
	}
	
	

	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Toon errors
	 * @return array|boolean
	 */
	public function errors()
	{
		//output for debug
		if (isset($_GET['debug']))
		{
			if ($this->_error === NULL)
				show('Geen errors');
			else
				show($this->_error);
		}

		if ($this->_error === NULL)
			return false;

		return $this->_error;
	}
}


?>