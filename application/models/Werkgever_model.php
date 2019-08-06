<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/*
 * Werkgever class
 * Wordt altijd geladen en is overal beschikbaar
 *
 *
 */

class Werkgever_model extends MY_Model
{
	/*
	 * @var array
	 */
	private $_error = NULL;
	private $_insert_id = NULL;

	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * connect to to database
	 *
	 */
	public function __construct()
	{
		parent::__construct();
	}



	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Get 1 bankrekening
	 *
	 */
	public function bankrekening( $id )
	{
		$bankrekeningen = $this->bankrekeningen();

		if(isset($bankrekeningen[$id]))
			return $bankrekeningen[$id];

		return NULL;
	}

	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Get  array bankrekeningen
	 *
	 */
	public function bankrekeningen()
	{
		$sql = "SELECT * FROM werkgever_bankrekeningen WHERE deleted = 0 ORDER BY omschrijving ASC";
		$query = $this->db_user->query($sql);

		$bankrekeningen = \models\Utils\Dbhelper::toArray( $query, 'id', 'array');
		return $bankrekeningen;
	}




	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Del bankrekening
	 *
	 */
	public function delBankrekening( $id )
	{
		$sql = "UPDATE werkgever_bankrekeningen SET deleted = 1, deleted_on = NOW(), deleted_by = ".$this->user->user_id." WHERE deleted = 0 AND id = $id";
		$this->db_user->query($sql);

		if ($this->db_user->affected_rows() > 0 )
			return true;

		return false;
	}


	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Set bankrekening
	 *
	 */
	public function setBankrekening()
	{
		//welk id is geklikt
		$id = key( $_POST['set']);

		//array naar ander format
		$input = formatPostArray($_POST, 'set');

		//show($id);
		//show($input);

		$validator = new models\Forms\Validator();
		$validator->table( 'werkgever_bankrekeningen' )->input( $input[$id] )->run();

		$input = $validator->data();

		//geen fouten, nieuwe insert doen wanneer er wijzigingen zijn
		if( $validator->success() )
		{
			//nieuwe insert
			if( $id == 0 )
			{
				$input['user_id'] = $this->user->user_id;
				$this->db_user->insert('werkgever_bankrekeningen', $input);

				if ($this->db_user->insert_id() > 0)
					$this->_insert_id = $this->db_user->insert_id();
				else
					$this->_error[] = 'Database error: insert mislukt';
			}
			else
			{
				//zijn er daadwerkelijk wijzigingen?
				if( inputIsDifferent( $input, $this->bankrekening($id) ))
				{
					//alle vorige entries als deleted
					$sql = "UPDATE werkgever_bankrekeningen SET deleted = 1, deleted_on = NOW(), deleted_by = ".$this->user->user_id." WHERE deleted = 0 AND id = $id";
					$this->db_user->query($sql);

					//alleen wanneer de update lukt om dubbele entries te voorkomen
					if ($this->db_user->affected_rows() != -1)
					{
						$input['user_id'] = $this->user->user_id;
						$this->db_user->insert('werkgever_bankrekeningen', $input);

						if ($this->db_user->insert_id() > 0)
							$this->_insert_id = $this->db_user->insert_id();
						else
							$this->_error[] = 'Database error: insert mislukt';
					}
					else
					{
						$this->_error[] = 'Database error: update mislukt';
					}
				}
				//zelfde ID terug
				else
				{
					$this->_insert_id = $id;
				}
			}

		}
		//fouten aanwezig
		else
		{
			$this->_error = $validator->errors();
		}

		return $input;
	}

	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Get nieuw aangemaakte id
	 *
	 */
	public function getInsertId()
	{
		return $this->_insert_id;
	}



	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Handtekening downloaden
	 *
	 */
	public function handtekening()
	{
		$sql = "SELECT AES_DECRYPT( uitzenders_handtekening.file, UNHEX(SHA2('".UPLOAD_SECRET."' ,512)) ) AS file FROM uitzenders_handtekening LIMIT 1";
		$query = $this->db_user->query($sql);

		if ($query->num_rows() > 0)
		{
			$data = $query->row_array();

			//$type = 'image/jpeg';
			//header('Content-Type:'.$type);
			//echo (base64_encode($data['file']));
			echo "<img src='data:image/jpeg;base64," . base64_encode( $data['file'] )."'>";
			die();
		}
	}


	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Get bedrijfsgegevens
	 *
	 */
	public function bedrijfsgegevens()
	{
		$sql = "SELECT * FROM werkgever_bedrijfsgegevens WHERE deleted = 0 ORDER BY id DESC LIMIT 1";
		$query = $this->db_user->query($sql);

		if ( $query->num_rows() == 0 )
			return NULL;

		return $query->row_array();
	}



	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Sla bedrijfsgegevens op na controle
	 * Oude gegevens worden als verwijderd aangemerkt
	 * Geeft ingevoerde data terug
	 * @return array
	 */
	public function setBedrijfsgegevens()
	{
		$validator = new models\Forms\Validator();
		$validator->table( 'werkgever_bedrijfsgegevens' )->input( $_POST )->run();

		$input = $validator->data();

		//geen fouten, nieuwe insert doen wanneer er wijzigingen zijn
		if( $validator->success() )
		{
			//zijn er daadwerkelijk wijzigingen?
			if( inputIsDifferent( $input, $this->bedrijfsgegevens() ))
			{
				//alle vorige entries als deleted
				$sql = "UPDATE werkgever_bedrijfsgegevens SET deleted = 1, deleted_on = NOW(), deleted_by = ".$this->user->user_id." WHERE deleted = 0";
				$this->db_user->query($sql);

				//alleen wanneer de update lukt om dubbele entries te voorkomen
				if ($this->db_user->affected_rows() != -1)
				{
					$input['user_id'] = $this->user->user_id;
					$this->db_user->insert('werkgever_bedrijfsgegevens', $input);
				}
				else
				{
					$this->_error[] = 'Database error: update mislukt';
				}

			}
		}
		//fouten aanwezig
		else
		{
			$this->_error = $validator->errors();
		}

		return $input;
	}


	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
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