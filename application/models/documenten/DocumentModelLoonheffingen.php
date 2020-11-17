<?php

namespace models\documenten;

use models\Connector;
use models\file\Pdf;
use models\pdf\PdfBuilder;
use models\utils\Codering;
use models\utils\DBhelper;
use models\werknemers\Werknemer;
use setasign\Fpdi\Fpdi;

if( !defined( 'BASEPATH' ) )
	exit( 'No direct script access allowed' );

/*
 * Documenten maken
 *
 */

class DocumentModelLoonheffingen extends Connector
{
	
	protected $_werknemer_id = NULL;
	protected $werknemer = NULL;
	protected $pdf = NULL;
	
	/*
	 * @var array
	 */
	protected $_error = NULL;
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 *
	 * @return object
	 */
	public function __construct()
	{
		parent::__construct();
		
		
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * werknemer instellen
	 *
	 * Geeft object terug
	 * @return object
	 */
	public function werknemer( $id ): DocumentModelLoonheffingen
	{
		$this->_werknemer_id = intval( $id );
		
		$this->werknemer = new Werknemer( $this->_werknemer_id );
		if( $this->werknemer === NULL )
			return $this;
		
		return $this;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * pdf maken
	 *
	 * Geeft PDF object terug
	 * @return object|void
	 */
	public function pdf()
	{
		if( $this->werknemer === NULL )
			return NULL;
		
		//path voor pdf
		$dir = UPLOAD_DIR . '/werkgever_dir_' . $this->user->werkgever_id . '/documenten/formulier_loonheffingen';
		if( !checkAndCreateDir( $dir ) )
			die( 'Map kon niet worden aangemaakt' );
		
		$path = $dir . '/loonheffingen_' . $this->_werknemer_id . '_' . uniqid() . '.pdf';
		
		$this->pdf = new Pdf();
		$this->pdf->template( 'model_loonheffingen', $path );
		
		$this->_insertData( $this->pdf );
		
		return $this->pdf;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * werknemergegevens plakken
	 *
	 */
	private function _insertData( $pdf )
	{
		$fpdi = new Fpdi();
		
		$werknemer = $this->werknemer->gegevens();
		$verloning = $this->werknemer->verloning();
		
		if( isset( $_GET['s'] ) )
		{
			show( $werknemer );
			show( $verloning );
			die();
		}
		
		$fpdi->setSourceFile( $pdf->path() );
		
		$fpdi->SetFont( 'Arial', '', 10 );
		$fpdi->SetTextColor( 0, 0, 0 );
		
		//--- pagina 1 --------------------------------------------
		$tplidx = $fpdi->importPage( 1 );
		$fpdi->addPage( 'P' );
		$fpdi->useTemplate( $tplidx );
		
		//naam
		$fpdi->SetXY( 71, 173 );
		$fpdi->Cell( 25, 10, $werknemer['naam'] );
		
		//BSN
		$fpdi->SetXY( 71, 181 );
		$fpdi->Cell( 25, 10, $werknemer['bsn'] );
		
		//straat huisnr
		$fpdi->SetXY( 71, 190 );
		$fpdi->Cell( 25, 10, $werknemer['straat'] . ' ' . $werknemer['huisnummer'] . ' ' . $werknemer['huisnummer_toevoeging'] );
		
		//postcode
		$fpdi->SetXY( 71, 207 );
		$fpdi->Cell( 25, 10, $werknemer['postcode'] );
		
		//plaats
		$fpdi->SetXY( 105, 207 );
		$fpdi->Cell( 25, 10, $werknemer['plaats'] );
		
		//woonland
		if( $werknemer['woonland_id'] != 151 )
		{
			$fpdi->SetXY( 71, 215 );
			$fpdi->Cell( 25, 10, Codering::landFromId( $werknemer['woonland_id'] ) );
		}
		
		//gb datum
		$parts = explode( '-', $werknemer['gb_datum'] );
		
		$fpdi->SetXY( 73, 232 );
		$fpdi->Cell( 25, 10, $parts[2] );
		$fpdi->SetXY( 87, 232 );
		$fpdi->Cell( 25, 10, $parts[1] );
		$fpdi->SetXY( 101, 232 );
		$fpdi->Cell( 25, 10, $parts[0] );
		
		//telefoon
		$fpdi->SetXY( 71, 241 );
		$fpdi->Cell( 25, 10, $werknemer['telefoon'] );
		
		//--- pagina 2 --------------------------------------------
		$tplidx = $fpdi->importPage( 2 );
		$fpdi->addPage( 'P' );
		$fpdi->useTemplate( $tplidx );
		
		//temp
		/*
		$fpdi->SetFont( 'Arial', '', 7 );
		$fpdi->SetTextColor( 0, 0, 0 );
		
		for( $i = 1; $i <= 20; $i++ )
		{
			$fpdi->SetXY( 10 * $i, 10 );
			$fpdi->Cell( 25, 10, 10 * $i );
		}
		
		for( $i = 1; $i <= 25; $i++ )
		{
			$fpdi->SetXY( 10, 10 * $i );
			$fpdi->Cell( 25, 10, 10 * $i );
		}
		
		$fpdi->SetFont( 'Arial', '', 9 );
		$fpdi->SetTextColor( 0, 0, 0 );*/
		// einde temp
		
		//loonheffingen
		if( $verloning['loonheffingskorting'] == 1 )
		{
			$fpdi->SetXY( 114.5, 34.5 );
			$fpdi->Cell( 25, 10, 'X' );
			
			$parts = explode( '-', $verloning['loonheffingskorting_vanaf'] );
			
			$fpdi->SetXY( 151, 33 );
			$fpdi->Cell( 25, 10, $parts[2] );
			$fpdi->SetXY( 166, 33 );
			$fpdi->Cell( 25, 10, $parts[1] );
			$fpdi->SetXY( 181, 33 );
			$fpdi->Cell( 25, 10, $parts[0] );
		}
		
		if( $verloning['loonheffingskorting'] == 0 )
		{
			$fpdi->SetXY( 114.5, 43 );
			$fpdi->Cell( 25, 10, 'X' );
		}
		
		//datum
		$parts = explode( '-', date('Y-m-d') );
		
		$fpdi->SetXY( 72.5, 67 );
		$fpdi->Cell( 25, 10, $parts[2] );
		$fpdi->SetXY( 87, 67 );
		$fpdi->Cell( 25, 10, $parts[1] );
		$fpdi->SetXY( 104, 67 );
		$fpdi->Cell( 25, 10, $parts[0] );
		
		//--- opslaan --------------------------------------------
		$fpdi->Output( $pdf->path(), 'F' );
		
	}
}

?>