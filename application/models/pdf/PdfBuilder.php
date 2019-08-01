<?php

namespace models\pdf;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * PDF class
 *
 * Common PDF methods
 *
 */
class PdfBuilder{

	/*
	 * @var mpdf object
	 */
	protected $mpdf = NULL;

	/*
	 * @var smarty object
	 */
	protected $smarty = NULL;

	/*
	 * @var array
	 */
	protected $_error = NULL;



	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Init new mpdf object
	 * Set default config
	 *
	 * @param config array
	 * @return $this
	 */
	public function __construct( $config = NULL )
	{
		//load lib
		require_once('application/third_party/vendor/autoload.php');

		//Landscape or portrait
		if( isset($config['format']) && $config['format'] == 'L' )
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
		$this->mpdf = new \Mpdf\mpdf( $config );

		$this->mpdf->SetTitle('PDF');
		$this->mpdf->SetAuthor('App');
		$this->mpdf->SetDisplayMode('fullpage');
		$this->mpdf->SetProtection ( array('print','print-highres','assemble','extract','copy') );

		//init smarty object
		$CI =& get_instance();// Grab the super object
		$this->smarty = $CI->smarty;

		return $this;
	}

	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * View pdf in browser
	 * @return array or boolean
	 */
	public function view()
	{
		//temp
		if( isset($_GET['p']) )
			$this->mpdf->Output();
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