<?php

namespace models\pdf;

use models\Connector;
use models\file\Pdf;
use Mpdf\Mpdf;
use Mpdf\Output\Destination;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * Wrapper voor PDF MPDF classes.
 *
 *
 */
class PdfBuilder extends Connector {

	/*
	 * @var mpdf object
	 */
	public $mpdf = NULL;

	/*
	 * @var smarty object
	 */
	public $smarty = NULL;

	/*
	 * @var array
	 */
	protected $_error = NULL;
	
	//file info
	protected $_file_name = NULL; //file name on disk
	protected $_file_name_display = NULL; //file name to show when download
	protected $_file_dir = NULL; //dir
	protected $_file_path = NULL; // full path ( dir + file_name)
	protected $_file_table = NULL; //table where file is stored

	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Init new mpdf object
	 * Set default config
	 *
	 * @param array
	 * @return $this
	 */
	public function __construct( $config = NULL )
	{
		parent::__construct();
		
		//Landscape or portrait
		if( isset( $config['format']) && $config['format'] == 'L' )
			$config['format'] = 'A4-L';
		else
			$config['format'] = 'A4-P';

		//font
		$config['default_font'] = 'arial';
		$config['autoMarginPadding'] = 0;
		$config['bleedMargin'] = 0;
		$config['collapseBlockMargins'] = false;

		/*
		'autoMarginPadding' => 0,
        'bleedMargin' => 0,
        'crossMarkMargin' => 0,
        'cropMarkMargin' => 0,
        'nonPrintMargin' => 0,
        'margBuffer' => 0,
        'collapseBlockMargins' => false,*/

		//init mpdf object
		$this->mpdf = new Mpdf( $config );

		$this->mpdf->SetTitle( $config['titel'] );
		$this->mpdf->SetAuthor('Devis Online');
		$this->mpdf->SetDisplayMode('fullpage');
		//$this->mpdf->SetProtection ( array('print','print-highres','assemble','extract','copy') );

		//init smarty object
		$CI =& get_instance();// Grab the super object
		$this->smarty = $CI->smarty;

		return $this;
	}

	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * View pdf in browser
	 * @return array | bool
	 */
	public function preview()
	{
		$this->mpdf->Output( $this->_file_name_display . '.pdf', Destination::INLINE);
		
		//temp
		//if( isset($_GET['p']) )
			//$this->mpdf->Output();
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * set file dir
	 * @return object
	 */
	public function setFileDir( $dir )
	{
		$this->_file_dir = $dir;
		$this->_setFilePath();
		return $this;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * get file dir
	 * @return object
	 */
	public function getFileDir()
	{
		return $this->_file_dir;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * set file name
	 * @return object
	 */
	public function setFileName( $name )
	{
		$this->_file_name = $name;
		$this->_setFilePath();
		return $this;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * get file name
	 * @return object
	 */
	public function getFileName()
	{
		return $this->_file_name;
	}

	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * set file path
	 * @return object
	 */
	public function _setFilePath()
	{
		$CI =& get_instance();// Grab the super object
		$this->_file_path = UPLOAD_DIR .'/werkgever_dir_'. $CI->user->werkgever_id .'/' . $this->_file_dir . '/' . $this->_file_name;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * get file path
	 * @return object
	 */
	public function getFilePath()
	{
		return $this->_file_path;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * set file display name
	 * @return object
	 */
	public function setFileDisplayName( $name )
	{
		$this->_file_name_display = $name;
		return $this;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * get file path
	 * @return object
	 */
	public function getFileDisplayName()
	{
		return $this->_file_name_display;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * set table
	 * @return object
	 */
	public function setTable( $table )
	{
		$this->_file_table = $table;
		return $this;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * get table
	 * @return object
	 */
	public function getTable()
	{
		return $this->_file_table;
	}
	

	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * maak de pdf en geef object terug
	 * @return object|boolean
	 */
	public function generate()
	{
		$CI =& get_instance();// Grab the super object
		
		if( !checkAndCreateDir(UPLOAD_DIR .'/werkgever_dir_'. $CI->user->werkgever_id .'/' . $this->_file_dir) )
			$this->_error[] = 'Upload map bestaat niet en kan niet worden aangemaakt.';
		
		//opruimen
		unset($CI);
		
		$this->mpdf->Output( $this->_file_path );
		
		//check
		if( !file_exists( $this->_file_path ) )
		{
			$this->_error[] = 'Er gaat wat fout. PDF kon niet worden weggeschreven';
			return false;
 		}
		
		$config['file_name'] = $this->_file_name;
		$config['file_dir'] = $this->_file_dir;
		$config['file_name_display'] = $this->_file_name_display;

		$pdf = new Pdf( $config );
		return $pdf;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Toon errors
	 * @return array|boolean
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