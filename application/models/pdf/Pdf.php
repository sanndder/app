<?php

namespace models\pdf;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * PDF class
 *
 * Common PDF methods
 *
 */
class Pdf{

	/*
	 * @var mpdf object
	 */
	protected $mpdf = NULL;

	/*
	 * @var array
	 */
	protected $_error = NULL;



	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Init new file from array
	 *
	 *
	 * @param file array
	 * @return $this
	 */
	public function __construct( $config = NULL )
	{
		//load lib
		require_once('application/third_party/vendor/autoload.php'); //pdf library laden

		//Landscape or portrait
		if( isset($config['format']) && $config['format'] == 'L' )
			$param['format'] = 'A4-L';
		else
			$param['format'] = 'A4-P';

		//font
		$param['default_font'] = 'arial';

		$this->mpdf = new \Mpdf\mpdf( $param );

		$this->mpdf->SetTitle('PDF');
		$this->mpdf->SetAuthor('App');
		$this->mpdf->SetDisplayMode('fullpage');
		$this->mpdf->SetProtection ( array('print','print-highres','assemble','extract','copy') );

		$this->mpdf->WriteHTML('Hello World');

		$this->mpdf->Output();

		return $this;
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