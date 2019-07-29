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
	}


	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * de te uploaden files in de database opslaan
	 *
	 * @return void
	 */
	function uploadfilesToDatabase()
	{
		//show($_FILES);

		//$sql = "INSERT INTO uitzenders_handtekening (file) VALUES ('".addslashes(file_get_contents($_FILES['file']['tmp_name']))."')";
		$sql = "INSERT INTO uitzenders_handtekening (file) VALUES (AES_ENCRYPT('".addslashes(file_get_contents($_FILES['file']['tmp_name']))."', UNHEX(SHA2('".UPLOAD_SECRET."',512))))";
		$query = $this->db_user->query($sql);


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
