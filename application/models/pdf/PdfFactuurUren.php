<?php

namespace models\pdf;

use models\verloning\Invoer;
use models\verloning\InvoerKm;
use models\verloning\InvoerUren;
use models\verloning\InvoerVergoedingen;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * PDF class
 *
 * Common PDF methods
 *
 */
class PdfFactuurUren extends PdfFactuur {

	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Init new file from array
	 *
	 *
	 * @param array
	 * @return
	 */
	public function __construct()
	{
		$config['margin_left'] = 0;
		$config['margin_top'] = 23;
		$config['margin_header'] = 0;
		$config['margin_right'] = 0;
		$config['margin_bottom'] = 0;
		
		$config['margin_header'] = 0;
		$config['margin_footer'] = 0;

		$config['format'] = 'L';
		$config['titel'] = 'Factuur';
		
		parent::__construct($config);
		
		$this->mpdf->AliasNbPages('[pagetotal]');
		
		//stylesheet erin
		$stylesheet = file_get_contents('application/views/pdf/css/facturen.css');
		$this->mpdf->WriteHTML($stylesheet, 1);
		
		$bedrijfsgegevens = $this->werkgever->bedrijfsgegevens();
		$this->smarty->assign('bedrijfsgegevens', $bedrijfsgegevens);
		
		return $this;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * header voor de factuur
	 * @return object
	 */
	public function setBody() :PdfFactuurUren
	{
		$body = $this->smarty->fetch('application/views/pdf/facturen/factuur_uren.tpl');
		$this->mpdf->WriteHTML($body);
		
		return $this;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * header voor de factuur
	 * @return object
	 */
	public function setHeader() :PdfFactuurUren
	{
		$header = $this->smarty->fetch('application/views/pdf/facturen/factuur_header.tpl');
		$this->mpdf->SetHTMLHeader($header);
		return $this;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * uren er in
	 *
	 */
	public function setUrenInput() :PdfFactuurUren
	{
		$invoer = new Invoer();
		$invoer->setTijdvak( array( 'tijdvak' => $this->_tijdvak, 'jaar' => $this->_jaar, 'periode' => $this->_periode) );
		$invoer->setInlener( $this->_inlener->inlener_id );
		$invoer->setUitzender( $this->_uiztender->uitzender_id );
		
		$werknemers = $invoer->listWerknemers();
		foreach( $werknemers as $key => $werknemer)
		{
			$array[$werknemer['werknemer_id']]['werknemer'] = $werknemer;
			
			$invoerUren = new InvoerUren( $invoer );
			$invoerUren->setWerknemer( $werknemer['werknemer_id'] );
			
			$invoerKm = new InvoerKm( $invoer );
			$invoerKm->setWerknemer( $werknemer['werknemer_id'] );
			
			$invoervergoedingen = new InvoerVergoedingen( $invoer );
			$invoervergoedingen->setWerknemer( $werknemer['werknemer_id'] );
			
			//urenmatrix
			$array[$werknemer['werknemer_id']]['uren'] = $invoerUren->urenMatrix();
			
			//kilometers
			$array[$werknemer['werknemer_id']]['km'] = $invoerKm->getWerknemerKilometers();
			
			//vergoedingen
			$invoervergoedingen->setWerknemerUren( $invoerUren->getWerknemerUren() );
			$array[$werknemer['werknemer_id']]['vergoedingen'] = $invoervergoedingen->getWerknemerVergoedingen();
			
			//uren optellen
			foreach( $array[$werknemer['werknemer_id']]['uren'] as $uren )
			{
				
				if( isset($uren['rows']) )
				{
					foreach( $uren['rows'] as $row )
					{
						if( !isset( $totaal_uren[$row['uren_type_id_werknemer']] ) )
							$totaal_uren[$row['uren_type_id_werknemer']]['aantal'] = 0;
						
						$totaal_uren[$row['uren_type_id_werknemer']]['aantal'] += $row['decimaal'];
					}
				}
			}
	
		}
		
		//tarief factor en uurloon ophalen
		$sql = "SELECT werknemers_urentypes.*, wi.*, i.*, werknemers_urentypes.id AS row_id, iu.*, u.naam
				FROM werknemers_urentypes
				LEFT JOIN werknemers_inleners wi on werknemers_urentypes.plaatsing_id = wi.plaatsing_id
				LEFT JOIN inleners_factoren i on wi.factor_id = i.factor_id
				LEFT JOIN inleners_urentypes iu on werknemers_urentypes.inlener_urentype_id = iu.inlener_urentype_id
				LEFT JOIN urentypes u on iu.urentype_id = u.urentype_id
				WHERE werknemers_urentypes.id IN (".array_keys_to_string($totaal_uren).")";
		
		$query = $this->db_user->query( $sql );
		
		foreach( $query->result_array() as $row )
		{
			$id = $row['row_id'];
			if( $row['label'] == '' )
				$row['label'] = $row['naam'];
			
			$totaal_uren[$id]['bruto'] = $row['bruto_loon'];
			$totaal_uren[$id]['factor_hoog'] = $row['factor_hoog'];
			$totaal_uren[$id]['factor_laag'] = $row['factor_laag'];
			$totaal_uren[$id]['verkooptarief'] = $row['verkooptarief'];
			$totaal_uren[$id]['naam'] = $row['label'];
			
			$array[$row['werknemer_id']]['uren_totaal'] = $totaal_uren;
			
			//$data[] = $row;
		}
		
		foreach( $array as $id => $arr)
		{
			unset($array[$id]['uren']);
		}
		
		$this->smarty->assign('array', $array);
		
		//show($data);
		//show($totaal_uren);
		//show($array);
		//die();
		return $this;
	}
	
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Toon errors
	 * @return array| boolean
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