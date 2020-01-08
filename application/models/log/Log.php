<?php

namespace models\log;

use models\Connector;

if (!defined('BASEPATH'))
	exit('No direct script access allowed');


/*
 * Log class
 * Errors wegschrijven naar textbestand
 *
 *
 */

class Log extends Connector
{
	/*
	* Subdir voor de logs
	* @var string
	*/
	private $_dir = NULL;
	private $_path = NULL;
	
	/*
	* file
	* @var recources
	*/
	private $_file = NULL;
	
	/*
	* content
	* @var string
	*/
	private $_content = '';

	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * constructor
	 */
	public function __construct()
	{
		//call parent constructor for connecting to database
		parent::__construct();

	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Juiste map voor
	 * @return object
	 */
	public function setDir( $dir )
	{
		$this->_dir = $dir;
		$this->_path = 'application/logs/' . $dir . '/' . date('Y-m-d') . '.txt';

		return $this;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Bestand openen met datum van vandaag, geen bestand? Dan nieuwe maken
	 * @return object
	 */
	public function openFile()
	{
		$this->_file = fopen($this->_path, "a");
		return $this;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * data naar bestand schrijven
	 * @return object
	 */
	public function writeData( $post )
	{
		//add
		$this->_content .= "\n\r";
		
		$this->_content .= '-- '. date('Y-m-d H:i:s') .' ---------------------------------------------------------------------------------------------------------------------------------------------------------------';
		$this->_content .= "\n\r\n\r";
		$this->_content .= 'User: ' . $this->user->user_id;
		$this->_content .= "\n\r\n\r";
		if( isset($post['module']) )
		{
			$this->_content .= 'Module: ' . $post['module'];
			$this->_content .= "\n\r\n\r";
		}
		$this->_content .= 'Url: ' . $post['url'];
		$this->_content .= "\n\r\n\r";
		$this->_content .= 'Error: ' . $post['statusText'];
		$this->_content .= "\n\r\n\r";
		$this->_content .= 'Data: ' . json_encode($post['data']);
		$this->_content .= "\n\r\n\r";
		$this->_content .= 'Response: ' . json_encode($post['responseText']);
		
		$this->_content .= "\n\r";
		$this->_content .= "\n\r";
		
		//write
		fwrite($this->_file, $this->_content);
		
		return $this;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * bestand sluiten
	 * @return object
	 */
	public function saveFile()
	{
		fclose($this->_file);
		return $this;
	}


}


?>