<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/*
 *
 * Handles all uploads
 *
 *
*/

class Upload_model extends MY_Model
{
	private $_dir = NULL; //map waar de bestanden heen moeten
	private $_path = NULL; //volledige path op de server waar de bestanden heen moeten
	private $_table = NULL; //tabel waar de info moet worden opgeslagen

	private $_random_name = true; //random naam meegeven
	private $_random_name_prefix = ''; //random naam prefix

	private $_file_name = NULL; //name after upload
	private $_file_path = NULL; //name after upload

	private $_field = ''; //welk veld is key
	private $_id = ''; //id voor table


	private $_error = NULL;

	/*
	 * constructor
	 * @return void
	 *
	*/
	public function __construct()
	{
		// Call the Model constructor
		parent::__construct();

	}


	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Locatie van bestand teruggeven
	 *
	 * @return void
	 */
	public function getFilePath()
	{
		return $this->_file_path;
	}

	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Array met info terug
	 *
	 * @return void
	 */
	public function getFileArray()
	{
		$array['file_name'] = $this->_file_name;
		$array['file_dir'] = $this->_dir;

		return $array;
	}


	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Tabel waar de data moet worden opgeslagen
	 *
	 * @return void
	 */
	public function setDatabaseTable( $table = '' )
	{
		$this->_table = trim($table);
	}


	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * field en value voor database
	 *
	 * @return void
	 */
	public function setFieldId( $field, $id )
	{
		$this->_field = $field;
		$this->_id = $id;
	}


	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Upload dir instellen en eventueel aanmaken
	 *
	 * @return void
	 */
	public function setUploadDir( $dir )
	{
		$this->_dir = $dir;

		//remove trailing slahs if there is one
		if(substr($this->_dir, -1) == '/')
			$this->_dir = substr($this->_dir, 0, -1);

		//geen werkgever ID? Stoppen
		if( !isset($this->user->werkgever_id) || $this->user->werkgever_id === NULL || !is_numeric($this->user->werkgever_id) )
		{
			$this->_error[] = 'Ongeldig werkgever ID';
			return false;
		}

		$this->_path = UPLOAD_DIR .'/werkgever_dir_'. $this->user->werkgever_id .'/' . $this->_dir;

		if( !checkAndCreateDir($this->_path) )
			$this->_error[] = 'Upload map bestaat niet en kan niet worden aangemaakt.';
	}




	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * wel of niet de originele naam behouden, standaard niet
	 *
	 * @return void
	 */
	function randomName( $val = true )
	{
		$this->_random_name = $val;
	}



	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * prefix van de filename instellen
	 *
	 * @return void
	 */
	function setPrefix( $prefix = '' )
	{
		$this->_random_name_prefix = $prefix;
	}



	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * de te uploaden files afhandelen, we gebruiken codeignitors ingebouwde upload functies
	 *
	 * @return void
	 */
	function uploadfiles()
	{
		//nieuwe file_name
		if( $this->_random_name )
		{
			$microtime = microtime();
			$microtime = str_replace( array(' ','.') , '', $microtime);
			$config['file_name'] = $this->_random_name_prefix . $microtime . '_' . generateRandomString(8);
		}

		//config
		$config['upload_path'] = $this->_path;
		$config['allowed_types'] = '*';
		$config['overwrite'] = TRUE;
		$config['file_ext_tolower'] = TRUE;
		$config['remove_spaces'] = TRUE;

		$this->load->library('upload', $config);

		if (!$this->upload->do_upload('file'))
		{
			$this->_error = $this->upload->display_errors();
			return false;
		}

		$uploaddata = $this->upload->data();

		//check
		$this->_file_name = $uploaddata['orig_name'];
		$this->_file_path = $this->_path . '/' . $this->_file_name;

		if( !file_exists($this->_file_path) || is_dir($this->_file_path))
		{
			$this->_error[] = 'Upload mislukt, bestand niet aanwezig op server';
			return false;
		}

	}

	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * de te uploaden files in de database opslaan
	 *
	 * @return void
	 */
	function dataToDatabase( $delete_old_entries = false )
	{
		//clear old entries
		if ($delete_old_entries)
		{
			$sql = "UPDATE $this->_table 
					SET deleted = 1, deleted_on = NOW(), deleted_by = " . $this->user->user_id . " 
					WHERE deleted = 0 AND $this->_field = $this->_id";
			$this->db_user->query($sql);
		}

		$insert[$this->_field] = $this->_id;
		$insert['file_dir'] = $this->_dir;
		$insert['file_name'] = $this->_file_name;
		$insert['file_size'] = filesize($this->_file_path);
		$insert['user_id'] = $this->user->user_id;

		$this->db_user->insert('uitzenders_logo', $insert);

	}


	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * de te uploaden files in de database opslaan
	 *
	 * @return void
	 */
	function uploadfilesToDatabase( $delete_old_entries = false )
	{
		//clear old entries
		if( $delete_old_entries )
		{
			$sql = "UPDATE $this->_table 
					SET deleted = 1, deleted_on = NOW(), deleted_by = " . $this->user->user_id . " 
					WHERE deleted = 0 AND $this->_field = $this->_id";
			$this->db_user->query($sql);

		}

		//$sql = "INSERT INTO uitzenders_handtekening (file) VALUES ('".addslashes(file_get_contents($_FILES['file']['tmp_name']))."')";
		$sql = "INSERT INTO uitzenders_handtekening (file, ".$this->_field.",user_id) 
				VALUES (AES_ENCRYPT('".addslashes(file_get_contents($_FILES['file']['tmp_name']))."', UNHEX(SHA2('".UPLOAD_SECRET."',512))),
				".$this->_id.",
				".$this->user->user_id."				
				)";
		//$sql = "INSERT INTO uitzenders_handtekening (file) VALUES ('".addslashes(file_get_contents($_FILES['file']['tmp_name']))."')";
		$this->db_user->query($sql);

		//$ciphertext = sodium_crypto_secretbox(, $nonce, UPLOAD_SECRET));

		//$update['file'] = $ciphertext;
		//$this->db_user->insert($this->_table, $update );


	}


	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Toon errors
	 *
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


}/* end of class */
