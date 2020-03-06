<?php

namespace models\verloning;

use models\Connector;
use models\file\Zip;
use models\forms\Validator;
use models\utils\DBhelper;
use models\utils\Tijdvak;
use models\werknemers\WerknemerGroup;

if (!defined('BASEPATH'))exit('No direct script access allowed');


/*
 * Loonstoken zip class
 *
 */

class LoonstrokenZip extends Connector
{
	
	private $_id = NULL;
	private $_details = NULL;
	private $_file_path = NULL;
	private $_zip = NULL;
	
	private $_error = NULL;
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * constructor
	 */
	public function __construct( $id = NULL)
	{
		//call parent constructor for connecting to database
		parent::__construct();
		
		if( $id != NULL )
			$this->setID( $id );
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * set ID
	 *
	 */
	public function setID( $id ) :void
	{
		$this->_id = intval($id);
		$this->load();
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * zip details
	 *
	 */
	public function load() :void
	{
		$query = $this->db_user->query( "SELECT * FROM loonstroken_zip WHERE zip_id = $this->_id LIMIT 1" );
		
		//bestand details
		$this->_details = DBhelper::toRow( $query, 'NULL' );
		
		//zip file
		if( $this->_details['status_done'] == 0 )
			$this->_zip = new Zip( $this->_details );
	}
	
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * file info naar database
	 *
	 */
	public function updateZipInfo() :void
	{
		if( $this->_details['pdf_totaal'] === NULL )
			$this->_updateTotalFiles();
		
		$this->_updateFilesLeft();
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * file info naar database
	 *
	 */
	public function _updateFilesLeft() :void
	{
		$this->db_user->where( 'zip_id', $this->_id );
		$this->db_user->update( 'loonstroken_zip', array('pdf_resterend'=> $this->_zip->numFiles()) );
		
		if( $this->_zip->numFiles() == 0 )
			$this->_updateStatus( 1 );
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * totaal aantal pdf's
	 *
	 */
	public function _updateTotalFiles() :void
	{
		$this->db_user->where( 'zip_id', $this->_id );
		$this->db_user->update( 'loonstroken_zip', array('pdf_totaal'=> $this->_zip->numFiles()) );
	}
	
		/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * totaal aantal pdf's
	 *
	 */
	public function _updateStatus( $status = 1 ) :void
	{
		$this->db_user->where( 'zip_id', $this->_id );
		$this->db_user->update( 'loonstroken_zip', array('status_done' => $status ) );
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * zip leeghalen
	 *
	 */
	public function process() :void
	{
		//bestand is al verwerkt
		if( $this->_details['status_done'] == 1 )
			return;
		
		$this->_extractZip();
		$this->_movePdfs();
		$this->_updateFilesLeft();
		
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * uitpakken
	 *
	 */
	private function _extractZip()
	{
		$temp_path = $this->_tempPath();
		
		if( checkAndCreateDir( $temp_path ) )
			$this->_zip->extract( $temp_path );
	}
	
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * verplaatsen naar werknemer
	 *
	 */
	private function _movePdfs()
	{
		$temp_path = $this->_tempPath();
		
		//door bestanden heen lopen
		if ($handle = opendir($temp_path)) {
			while (false !== ($file = readdir($handle))) {
				
				if ('.' === $file) continue;
				if ('..' === $file) continue;
				
				//info uit file
				$file_name = str_replace( 'SalarisSpecificatie0000', '', $file);
				
				$werkgever_id = intval(substr($file_name, 0, 2));
				$werknemer_id = intval(substr($file_name, 3, 5));
				$jaar = intval(substr($file_name, 9, 4));
				$periode = intval(substr($file_name, 13, 2));
				
				//tijdvak
				if( $werkgever_id == 1 ) $tijdvak = 'w';
				if( $werkgever_id == 2 ) $tijdvak = '4w';
				if( $werkgever_id == 3 ) $tijdvak = 'm';
				
				$tijdvakObject = new Tijdvak( $tijdvak, $jaar, $periode);
				
				//naar andere map
				$path_org = $temp_path . '/' . $file;
				$dir_new = UPLOAD_DIR .'/werkgever_dir_'. $this->user->werkgever_id .'/loonstroken/' . $jaar . '/';
				$path_new = $dir_new . $file;
				
				if( checkAndCreateDir($dir_new))
					rename( $path_org, $path_new);
				
				//delete oud
				$this->db_user->query( "UPDATE loonstroken_pdf SET deleted = 1, deleted_on = NOW(), deleted_by = ?
										WHERE deleted = 0 AND werknemer_id = ? AND tijdvak = ? AND jaar = ? AND periode = ? LIMIT 1",
										array( $this->user->user_id, $werknemer_id, $tijdvak, $jaar, $periode ) );
				
				//naar database
				$insert['werknemer_id'] = $werknemer_id;
				$insert['jaar'] = $jaar;
				$insert['periode'] = $periode;
				$insert['file_dir'] = 'loonstroken/' . $jaar;
				$insert['file_name'] = $file;
				$insert['tijdvak'] = $tijdvak;
				$insert['date_start'] = $tijdvakObject->startDatum();
				$insert['date_end'] = $tijdvakObject->eindDatum();
				
				$this->db_user->insert( 'loonstroken_pdf', $insert );
				
				//uit zip
				$this->_zip->deleteFile( $file );
				
				
			}
			closedir($handle);
		}
	}
	
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * temp path
	 *
	 */
	private function _tempPath() :?string
	{
		return str_replace( '.zip', '', UPLOAD_DIR .'/werkgever_dir_'. $this->user->werkgever_id .'/loonstroken/temp/' . $this->_details['file_name']);
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * alle wachtende zips ophalen
	 *
	 */
	public function queue() :?array
	{
		$query = $this->db_user->query( "SELECT * FROM loonstroken_zip WHERE status_done = 0 AND deleted = 0" );
		return  DBhelper::toArray( $query, 'zip_id', 'NULL' );
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * verwijderen
	 *
	 */
	public function delete() :bool
	{
		$this->db_user->query( "UPDATE loonstroken_zip SET deleted = 1, deleted_on = NOW(), deleted_by = ? WHERE deleted = 0 AND zip_id = ?", array( $this->user->user_id, $this->_id ) );
		
		if( $this->db_user->affected_rows() != -1 )
			return true;
		return false;
	}

	

	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
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