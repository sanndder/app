<?php

use Mpdf\Mpdf;


if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @property Auth $auth auth class
 * @property Smarty $smarty smarty class
 * @property Menu $menu menu class
 * @property Uitzender_model $uitzenders uitzenders class
 * @property Paginator $paginator paginator class
 * @property Rapportage_model $rapport rapport class
 * @property Factuur_model $factuur factuur class
 * @property User_model $users users class
 *
 */

class Proforma_model extends CI_Model
{
	private $_loonheffingskorting = false;
	private $_geboortejaar = false;
	private $_bruto = false;
	private $_prestatietoeslag = false;
	private $_km = false;
	private $_cao = false;
	private $_pensioen = false;
	private $_uren = false;
	private $_dagen = false;
	private $_frequentie = false;
	private $_netto_loon = 0;
	private $_uitbetaald = 0;

	private $_vakantieuren_direct = 0;
	private $_vakantiegeld_direct = 0;
	private $_atv_direct = 0;

	private $_et_regeling = 0;
	private $_et_huisvesting = 0;
	private $_et_km = 0;
	private $_et_km_bedrag = 0;
	private $_et_verschil_leven = 0;
	private $_max_et_bedrag = 0;
	private $_et_bedrag = 0;

	private $_verkooptarief = 0;
	private $_factor_belast = 0;
	private $_factor_onbelast = 0;
	private $_doelgroepverklaring = 0;


	private $_tech_admin = 'T'; //alleen technisch

	private $_vars = NULL;

	/**
	 * Constructor
	 *
	 */
	public function __construct()
	{
		// Call the CI_Model constructor

		$this->_vars = $this->loadVars();


		$this->load->model( 'verloning_model', 'verloning' );
		//show($this->logindata);

	}


	// --------------------------------------------------------------------

	/**
	 * var toevoegen
	 *
	 */
	public function insertVar()
	{

		$insert['categorie'] = 'Percentages';
		$insert['categorie_full_text'] = 'Percentages';
		$insert['categorie_id'] = '0';
		$insert['full_text'] = 'wachtdagcompensatie bouw technisch';
		$insert['value'] = '1.16';
		$insert['format'] = '%';

		$insert['name'] = strtolower(str_replace(' ', '_', $insert['full_text'] ));
		$this->db_user->insert('settings_proforma', $insert);



		$insert['categorie'] = 'Percentages';
		$insert['categorie_full_text'] = 'Percentages';
		$insert['categorie_id'] = '0';
		$insert['full_text'] = 'wachtdagcompensatie bouw administratief';
		$insert['value'] = '0.71';
		$insert['format'] = '%';

		$insert['name'] = strtolower(str_replace(' ', '_', $insert['full_text'] ));
		$this->db_user->insert('settings_proforma', $insert);


		$insert['categorie'] = 'Percentages';
		$insert['categorie_full_text'] = 'Percentages';
		$insert['categorie_id'] = '0';
		$insert['full_text'] = 'Aanv. Zw. bouw technisch';
		$insert['value'] = '1.1';
		$insert['format'] = '%';

		$insert['name'] = strtolower(str_replace(' ', '_', $insert['full_text'] ));
		$this->db_user->insert('settings_proforma', $insert);



		$insert['categorie'] = 'Percentages';
		$insert['categorie_full_text'] = 'Percentages';
		$insert['categorie_id'] = '0';
		$insert['full_text'] = 'Aanv. Zw. bouw administratief';
		$insert['value'] = '0.58';
		$insert['format'] = '%';

		$insert['name'] = strtolower(str_replace(' ', '_', $insert['full_text'] ));
		$this->db_user->insert('settings_proforma', $insert);


		$insert['categorie'] = 'Percentages';
		$insert['categorie_full_text'] = 'Percentages';
		$insert['categorie_id'] = '0';
		$insert['full_text'] = 'WGA bouw technisch';
		$insert['value'] = '0.435';
		$insert['format'] = '%';

		$insert['name'] = strtolower(str_replace(' ', '_', $insert['full_text'] ));
		$this->db_user->insert('settings_proforma', $insert);

	}

	// --------------------------------------------------------------------

	/**
	 * vars ophalen
	 *
	 */
	public function loadVars()
	{
		$sql = "SELECT * FROM settings_proforma";
		$query = $this->db_user->query($sql);

		if ($query->num_rows() == 0)
			return false;

		foreach ($query->result_array() as $row)
		{
			$row['value_format'] = rtrim(rtrim(number_format($row['value'],6,',','.'), '0'),',');


			$data[$row['id']] = $row;
		}

		return $data;
	}


	// --------------------------------------------------------------------

	/**
	 * vars opslaan
	 *
	 */
	public function saveProformaVariabelen( $data = '' )
	{
		$return = array();


		foreach($data['var'] as $id => $val )
		{
			$val = str_replace('.', '', $val );
			$val = str_replace(',', '.', $val );

			if( is_numeric($val) )
			{
				$opslaan[$id] = $val;
			}
			else
			{
				$fout[$id]['val'] = $val;
			}

		}

		//opslaan
		foreach ($opslaan as $id => $val )
		{
			$update['value'] = $val;

			$this->db_user->where('id', $id);
			$this->db_user->update('settings_proforma', $update);
		}

		//fouten naam er bij
		if(isset($fout))
		{
			$sql = "SELECT * FROM settings_proforma WHERE id IN (".array_keys_to_string($fout).")";
			$query = $this->db_user->query($sql);
			foreach ($query->result_array() as $row)
			{
				$error_data[$row['id']] = $row;
			}

			foreach ( $fout as $id => $arr)
			{
				$return['error'][$id] = 'Foute waarde: <b>' . $error_data[$id]['categorie_full_text'] . ' => ' . $error_data[$id]['full_text'] . '</b>' . ' ('.$arr['val'].' )' ;
			}
		}

		return $return;

	}



	// --------------------------------------------------------------------

	/**
	 * Pdf tonen
	 *
	 */
	public function pdfLoonstrook( $data )
	{
		
		$mpdf = new Mpdf();
		
		$mpdf->SetTitle( 'proforma' );
		$mpdf->SetAuthor('Devis Online');
		$mpdf->SetDisplayMode('fullpage');
		$mpdf->SetProtection(array('print')); //printen toestaan

		$this->smarty->assign('data', $data);
		$this->smarty->assign('post', $_POST);

		if( $this->_et_regeling == 1 )
		{
			$this->smarty->assign('et_regeling', 1 );

			$this->smarty->assign('et_huisvesting', $this->_et_huisvesting);
			$this->smarty->assign('et_verschil_leven', $this->_et_verschil_leven);
			$this->smarty->assign('et_km_bedrag', $this->_et_km_bedrag);

			$this->smarty->assign('et_bedrag', $this->_et_bedrag);
		}

		//$wm = 'PROFORMA';
		//$mpdf->SetWatermarkText($wm, 0.08);
		//$mpdf->showWatermarkText = true;
		
		$this->smarty->assign( 'logo', $this->werkgever->logo('path') );
		
		//stylesheet erin
		$stylesheet = file_get_contents('application/views/pdf/proforma/loonstrook.css');
		$mpdf->WriteHTML($stylesheet, 1);

		$html = $this->smarty->fetch('application/views/pdf/proforma/loonstrook.tpl');
		$mpdf->WriteHTML($html, 2);

		$mpdf->Output();

	}

	// --------------------------------------------------------------------

	/**
	 * Pdf tonen
	 *
	 */
	public function pdfKostenoverzicht( $data )
	{

		//pdf library laden
		require_once('application/third_party/mpdf/mpdf.php'); //pdf library laden

		//bv instellen
		$this->bv->setID( 1 );
		$this->smarty->assign( 'logo', $this->bv->getLogo() );

		$mpdf = new mPDF('win-1252', 'A4', '14', '', 14, 14, 20, 25, 8, 4);
		$mpdf->SetProtection(array('print')); //printen toestaan
		$mpdf->SetTitle('Proformaloonstrook');
		$mpdf->SetAuthor('Flexuur.nl');
		$mpdf->SetDisplayMode('fullpage');

		$this->smarty->assign('data', $data);
		$this->smarty->assign('post', $_POST);

		//stylesheet erin
		$stylesheet = file_get_contents('application/views/pdf/css/loonstrook.css');
		$mpdf->WriteHTML($stylesheet, 1);

		$html = $this->smarty->fetch('application/views/pdf/proforma_kosten.tpl');
		$mpdf->WriteHTML($html, 2);

		$mpdf->Output();

	}


	// --------------------------------------------------------------------

	/**
	 * Data instellen
	 *
	 */
	public function setInputData( $data )
	{
		$return['error'] = array();

		//show($data);

		if( !isset($data['loonheffingskorting']) || $data['loonheffingskorting'] == '' )
			$this->_loonheffingskorting = 1;
		else
			$this->_loonheffingskorting = $data['loonheffingskorting'];

		$this->_geboortejaar = prepareAmountForDatabase($data['geboortejaar']);
		if(!is_numeric($this->_geboortejaar) )
			$return['error'][] = 'Ongeldig geboortejaar';

		$this->_bruto = prepareAmountForDatabase($data['bruto']);
		if(!is_numeric($this->_bruto) )
			$return['error'][] = 'Ongeldig brutoloon';

		$this->_prestatietoeslag = prepareAmountForDatabase( $data['prestatietoeslag']);
		if(!is_numeric($this->_prestatietoeslag) )
			$return['error'][] = 'Ongeldige prestatietoeslag';

		$this->_km = prepareAmountForDatabase( $data['km']);
		if(!is_numeric($this->_km) )
			$return['error'][] = 'Ongeldige aantal kilometers';

		if( !isset($data['cao']) || $data['cao'] == '' )
			$this->_cao = 'NBBU';
		else
			$this->_cao = $data['cao'];

		if( !isset($data['pensioen']) || $data['pensioen'] == '' )
			$this->_pensioen = 0;
		else
			$this->_pensioen = $data['pensioen'];

		$this->_uren = prepareAmountForDatabase($data['uren']);
		if(!is_numeric($this->_uren) )
			$return['error'][] = 'Ongeldig aantal uren';

		$this->_dagen = prepareAmountForDatabase($data['dagen']);
		if(!is_numeric($this->_dagen) )
			$return['error'][] = 'Ongeldig aantal dagen';

		$this->_verkooptarief = prepareAmountForDatabase($data['verkooptarief']);
		if(!is_numeric($this->_verkooptarief) )
			$return['error'][] = 'Ongeldig verkooptarief';

		$this->_factor_belast = str_replace( ',','.', $data['factor_belast']);
		if(!is_numeric($this->_factor_belast) )
			$return['error'][] = 'Ongeldige factor belast';

		$this->_factor_onbelast = str_replace( ',','.',$data['factor_onbelast']);
		if(!is_numeric($this->_factor_onbelast) )
			$return['error'][] = 'Ongeldige factor onbelast';

		$this->_doelgroepverklaring = $data['doelgroepverklaring'];

		if( !isset($data['frequentie']) || $data['frequentie'] == '' )
			$this->_frequentie = 'w';
		else
			$this->_frequentie = $data['frequentie'];

		if( isset( $data['netto_bruto']))
		{
			$this->_netto_loon = prepareAmountForDatabase($data['netto_loon']);
			if(!is_numeric($this->_netto_loon) )
				$return['error'][] = 'Ongeldig netto loon';
		}

		if( isset( $data['et_regeling']) && $data['et_regeling'] == 1 )
		{
			$this->_et_regeling = $data['et_regeling'];

			$this->_et_huisvesting = prepareAmountForDatabase($data['et_huisvesting']);
			if(!is_numeric($this->_et_huisvesting) )
				$return['error'][] = 'Ongeldig bedrag huisvesting';
			else
			{
				/*if( $this->_et_huisvesting > 72.50 )
					$return['error'][] = 'Bedrag huisvesting mag niet hoger zijn dan € 72,50';*/
			}

			$this->_et_km = prepareAmountForDatabase($data['et_km']);
			if(!is_numeric($this->_et_km) )
				$return['error'][] = 'Ongeldig aantal kilometers';
			else
				$this->_et_km_bedrag = $this->_et_km * 0.19;

			$this->_et_verschil_leven = $data['et_verschil_leven'];

		}

		//direct uitkeren
		if( isset($data['vakantiegeld_direct']) )
			$this->_vakantiegeld_direct = $data['vakantiegeld_direct'];

		if( isset($data['vakantieuren_direct']) )
			$this->_vakantieuren_direct = $data['vakantieuren_direct'];

		if( isset($data['vakantieuren_direct']) && $data['cao'] == 'bouw' )
			$this->_atv_direct = $data['atv_direct'];


		if( count($return['error']) == 0 )
		{
			//et regeling checken
			if( isset( $data['et_regeling']) && $data['et_regeling'] == 1 )
			{
				$return = $this->checkEtRegeling();
				return $return;
			}
			else
			{
				$return['status'] = 'success';
				return $return;
			}


		}

		$return['status'] = 'error';
		return $return;
	}



	// --------------------------------------------------------------------

	/**
	 * Checken ET regeling
	 *
	 */
	public function checkEtRegeling()
	{
		
		$tabel_minimum_loon[20]['leeftijd'] = 20;
		$tabel_minimum_loon[20]['40_uur'] = 6.38;
		$tabel_minimum_loon[20]['38_uur'] = 6.71;
		$tabel_minimum_loon[20]['36_uur'] = 7.09;
		
		$tabel_minimum_loon[21]['leeftijd'] = 21;
		$tabel_minimum_loon[21]['40_uur'] = 7.74;
		$tabel_minimum_loon[21]['38_uur'] = 8.15;
		$tabel_minimum_loon[21]['36_uur'] = 8.60;
		
		$tabel_minimum_loon[22]['leeftijd'] = 22;
		$tabel_minimum_loon[23]['40_uur'] = 9.11;
		$tabel_minimum_loon[23]['38_uur'] = 9.59;
		$tabel_minimum_loon[22]['36_uur'] = 10.12;
		
		$tabel_minimum_loon[23]['leeftijd'] = 23;
		$tabel_minimum_loon[23]['40_uur'] = 9.11;
		$tabel_minimum_loon[23]['38_uur'] = 9.59;
		$tabel_minimum_loon[23]['36_uur'] = 10.12;
		

		$leeftijd = date('Y') - $this->_geboortejaar;
		if( $leeftijd > 23 )
			$leeftijd = 23;
		
		if( $leeftijd < 20 )
			$leeftijd = 20;

		if( $this->_uren >= 39)	$uren = '40_uur';
		if(  $this->_uren < 39 && $this->_uren >= 37)	$uren = '38_uur';
		if(  $this->_uren < 37 )	$uren = '36_uur';

		$min_loon = $tabel_minimum_loon[$leeftijd][$uren];
		$verschil = $this->_bruto - $min_loon;

		if( $verschil <= 0 )
		{
			$return['status'] = 'error';
			$return['error'] = 'Verschil minimumloon is kleiner of gelijk aan 0';
			return $return;
		}

		$dertig_bruto_loon = round( 0.3 * $this->_uren * $this->_bruto , 2 );
		$verschil_minuminloon = round( $this->_uren *  $verschil, 2);

		$et_bedrag = min($dertig_bruto_loon, $verschil_minuminloon);
		$this->_max_et_bedrag = round( ($et_bedrag * 0.81) , 2); // 81% van het bedrag

		//ingehouden bedrag optellen
		$this->_et_bedrag = $this->_et_verschil_leven + $this->_et_huisvesting + $this->_et_km_bedrag;

		if( $this->_et_bedrag > $this->_max_et_bedrag )
		{
			$return['status'] = 'error';
			$return['error'] = 'Inhoudingen ET regelingen (€ '.number_format($this->_et_bedrag,2,',','.').') hoger dan maximaal bedrag ET-regeling (€ '.number_format($this->_max_et_bedrag,2,',','.').')';
			return $return;
		}

		$return['status'] = 'success';
		return $return;
	}

	// --------------------------------------------------------------------

	/**
	 * Loonstrook ophalen
	 *
	 */
	public function calculateBrutoUurloon()
	{
		//benchmark
		$this->benchmark->mark('start');

		$target = $this->_netto_loon;

		$min = 1;
		$min_result = 0;

		$max = 100;
		$max_result = 0;

		$avg = 0;
		$avg_result = 0;
		$netto = false;

		$t = 0.00001;
		$i = 0;

		$break = false;

		//init min en max
		$this->_bruto = $min;
		$this->calculateLoon();
		$min_result = $this->_netto_loon;

		$this->_bruto = $max;
		$this->calculateLoon();
		$max_result = $this->_netto_loon;



		while( $break === false )
		{
			$i++;

			//nieuw avg
			$avg = ($min + $max) / 2;

			$this->_bruto = $avg;

			$last_result = $this->calculateLoon();
			$avg_result = $this->_netto_loon;

			//dichtbij genoeg?
			$diff = abs($target - $avg_result);

			if( ($diff / $target) <= $t)
			{
				$break = true;
			}
			//doorgaan
			else
			{
				if( $avg_result > $target )
					$max = $avg;

				if( $avg_result < $target )
					$min = $avg;
			}
			//safety
			if( $i > 1000 )
				$break = true;
		}

		//benchmark
		$this->benchmark->mark('end');

		$this->_i = $i;
		$this->_time = $this->benchmark->elapsed_time('start', 'end');

		$last_result['i'] = $this->_i;
		$last_result['time'] = $this->_time;

		return $last_result;
	}


	// --------------------------------------------------------------------

	/**
	 * Loonstrook ophalen
	 *
	 */
	public function calculateLoon()
	{
		//array index gelijk aan naam in excel bronbestand
		// 5 kolommen: percentage, factor_1, factor_2, resultaat, netto

		//uurloon * uren
		$array['uurloon']['factor_1'] = $this->_bruto;
		$array['uurloon']['factor_2'] = $this->_uren;
		$array['uurloon']['resultaat'] = round( $array['uurloon']['factor_1'] * $array['uurloon']['factor_2'], 2 );

		//prestatietoeslag erbij
		$array['prestatietoeslag']['factor_1'] = $this->_prestatietoeslag;
		$array['prestatietoeslag']['factor_2'] = $this->_uren;
		$array['prestatietoeslag']['resultaat'] = round( $array['prestatietoeslag']['factor_1'] * $array['prestatietoeslag']['factor_2'], 2 );

		//optellen
		$array['som_uurloon_prestatie']['resultaat'] = $array['uurloon']['resultaat'] + $array['prestatietoeslag']['resultaat'];
		$array['som_uurloon_prestatie']['netto'] = $array['som_uurloon_prestatie']['resultaat'];

		//wachtdagcompensatie
		if( isset($this->_et_regeling) && $this->_et_regeling == 1)
		{
			$array['wachtdagcompensatie']['percentage'] = $this->percentage( 'wachtdagcompensatie' );
			$array['wachtdagcompensatie']['resultaat'] = ( $array['wachtdagcompensatie']['percentage'] / 100 ) * ($array['som_uurloon_prestatie']['resultaat'] - $this->_et_bedrag);

		}
		else
		{
			$array['wachtdagcompensatie']['percentage'] = $this->percentage( 'wachtdagcompensatie' );
			$array['wachtdagcompensatie']['resultaat'] = ( $array['wachtdagcompensatie']['percentage'] / 100 ) * $array['som_uurloon_prestatie']['resultaat'];
		}

		//bruto loon
		$array['bruto_loon']['resultaat'] = $array['som_uurloon_prestatie']['resultaat'] + $array['wachtdagcompensatie']['resultaat'];

		//ET
		if( isset($this->_et_regeling) && $this->_et_regeling == 1)
		{
			$array['bruto_loon']['resultaat'] = $array['bruto_loon']['resultaat'] - $this->_et_bedrag;

			$array['som_uurloon_prestatie']['resultaat'] = $array['uurloon']['resultaat'] + $array['prestatietoeslag']['resultaat']  - $this->_et_bedrag;
			$array['som_uurloon_prestatie']['netto'] = $array['som_uurloon_prestatie']['resultaat'];
		}

		//reserveringen
		$reserveringen =  $this->berekenReserveringen( $array['som_uurloon_prestatie']['resultaat'] );
		$array = array_merge( $array, $reserveringen );

		//pensioen
		$pensioen =  $this->berekenPensioen( $array['som_uurloon_prestatie']['resultaat'] );
		$array = array_merge( $array, $pensioen );

		//aanvulling ziektewet
		$array['aanv_zw']['percentage'] = $this->percentage( 'aanv_zw' );

		$temp_factor = ($array['bruto_loon']['resultaat'] + $array['pensioen_totaal']['resultaat']) / (1 + ($array['aanv_zw']['percentage']/100) ); //nog een keer delen door percentage??

		$array['aanv_zw']['resultaat'] = ($array['aanv_zw']['percentage']/100) * $temp_factor * -1;
		$array['aanv_zw']['netto'] = $array['aanv_zw']['resultaat'];

		//heffingsloon
		$array['heffingsloon']['resultaat'] = $array['bruto_loon']['resultaat'] + $array['pensioen_totaal']['resultaat'] + $array['aanv_zw']['resultaat'];


		if( $this->_vakantieuren_direct == 1 ) $array['heffingsloon']['resultaat'] += $array['vakantieuren']['resultaat'];
		if( $this->_vakantiegeld_direct == 1 ) $array['heffingsloon']['resultaat'] += $array['vakantiegeld']['resultaat'];
		if( $this->_atv_direct == 1 ) $array['heffingsloon']['resultaat'] += $array['atv']['resultaat'];


		//loonheffing
		$loonheffing =  $this->berekenLoonheffing( $array['heffingsloon']['resultaat'] );

		$array = array_merge( $array, $loonheffing );

		//inhouding WOA/WGA
		$array['inhouding_WAO_WGA']['percentage'] = $this->percentage( 'wga' );
		$array['inhouding_WAO_WGA']['netto'] = ($array['inhouding_WAO_WGA']['percentage']/100) * $array['heffingsloon']['resultaat'] * -1;

		//vergoeding
		/*
		$array['vergoeding']['factor_1'] = 2.16;
		$array['vergoeding']['netto'] = $array['vergoeding']['factor_1'] * $this->_dagen;*/

		//show($array);

		$array['netto']['netto'] = $array['heffingsloon']['resultaat'] + $array['loonheffing']['netto'] + $array['inhouding_WAO_WGA']['netto'];

		if( isset($array['vergoeding']['netto']) )
			$array['netto']['netto'] += $array['vergoeding']['netto'];


		$this->_netto_loon = round( $array['netto']['netto'] , 2);
		$this->_uitbetaald = round( $array['netto']['netto'] , 2);

		if( $this->_km !== false )
		{
			$array['kilometers']['netto'] = round( $this->_km * 0.19 ,2 );

			$this->_uitbetaald += $array['kilometers']['netto'];
		}

		//ET
		if( isset($this->_et_regeling) && $this->_et_regeling == 1)
		{
			$this->_uitbetaald = $this->_uitbetaald + $this->_et_bedrag - $this->_et_huisvesting;
			$array['huisvesting'] = $this->_et_huisvesting;
		}
		
		$array['uitbetaald']['netto'] = $this->_uitbetaald;


		//kosten berekenen
		if( $this->_verkooptarief > 0 )
		{
			$array['kosten']['factor_belast'] = $this->_factor_belast;
			$array['kosten']['factor_onbelast'] = $this->_factor_onbelast;

			$array['kosten']['uurloon'] = round( $this->_bruto * $this->_factor_belast ,2 );

			$array['kosten']['uurloon'] = round( $this->_bruto * $this->_factor_belast ,2 );
			$array['kosten']['prestatietoeslag'] = round( $this->_prestatietoeslag * $this->_factor_onbelast ,2 );

			$array['kosten']['km'] = 0;
			if( $this->_km > 0 )
				$array['kosten']['km'] = round( ($this->_km * 0.19 ) / $this->_uren ,2 );

			$array['kosten']['doelgroepverklaring'] = 0;

			if( $this->_doelgroepverklaring == 1 )
				$array['kosten']['doelgroepverklaring'] =  round( (7000/2080), 2) * -1;

			$array['kosten']['totaal'] = $array['kosten']['uurloon'] + $array['kosten']['prestatietoeslag'] + $array['kosten']['km'] + $array['kosten']['doelgroepverklaring'];

			$array['kosten']['verkooptarief'] = $this->_verkooptarief;

			$array['kosten']['winst'] = $array['kosten']['verkooptarief'] - $array['kosten']['totaal'];
			$array['kosten']['winst_percentage'] = round(($array['kosten']['winst']/$array['kosten']['verkooptarief'])*100,2);
		}


		//zoekopdracht opslaan
		$id = $this->saveSearch();

		$return['netto_loon'] = $this->_netto_loon;
		$return['uitbetaald'] = $this->_uitbetaald;
		$return['id'] = $id;
		$return['data'] = $array;


		return $return;
	}




	// --------------------------------------------------------------------

	/**
	 * Reserveringen
	 *
	 */
	private function berekenReserveringen( $bruto_loon )
	{
		$reseveringen = array();

		//$percentage['vakantieuren'] = 10.43;
		$percentage['vakantieuren'] = $this->_vars[1]['value'];
		//$percentage['feestdagen'] = 2.60;
		$percentage['feestdagen'] = $this->_vars[2]['value'];
		//$percentage['kort_verzuim'] = 0.60;
		$percentage['kort_verzuim'] = $this->_vars[3]['value'];
		//$percentage['atv'] = 7.69;
		$percentage['atv'] = $this->_vars[4]['value'];
		//$percentage['vakantiegeld'] = 8.00;
		$percentage['vakantiegeld'] = $this->_vars[5]['value'];

		$reseveringen['vakantieuren']['percentage'] = $percentage['vakantieuren'];
		$reseveringen['vakantieuren']['resultaat'] = ($percentage['vakantieuren']/100) * $bruto_loon;

		$reseveringen['feestdagen']['percentage'] = $percentage['feestdagen'];
		$reseveringen['feestdagen']['resultaat'] = ($percentage['feestdagen']/100) * $bruto_loon;

		$reseveringen['kort_verzuim']['percentage'] = $percentage['kort_verzuim'];
		$reseveringen['kort_verzuim']['resultaat'] = ($percentage['kort_verzuim']/100) * $bruto_loon;

		if( $this->_cao == 'bouw' )
		{
			$reseveringen['atv']['percentage'] = $percentage['atv'];
			$reseveringen['atv']['resultaat'] = ($percentage['atv'] / 100) * $bruto_loon;
		}
		else
		{
			$reseveringen['atv']['percentage'] = 0;
			$reseveringen['atv']['resultaat'] = 0;
		}

		$grondslag_vakantiegeld = $bruto_loon;


		$reseveringen['vakantiegeld']['percentage'] = $percentage['vakantiegeld'];
		$reseveringen['vakantiegeld']['resultaat'] = ($percentage['vakantiegeld']/100) * $grondslag_vakantiegeld;


		return $reseveringen;
	}


	// --------------------------------------------------------------------

	/**
	 * loonheffing
	 *
	 */
	private function berekenLoonheffing( $loonvoorloonheffing )
	{
		$schijftarief[0][19982]['A'] = 0;
		$schijftarief[0][19982]['B'] = 0;
		$schijftarief[0][19982]['B'] = 0;


		//leeftijd berekenen
		$leeftijd = date('Y') - $this->_geboortejaar;

		if( $this->_frequentie == 'w' )	 $weken = 52;
		if( $this->_frequentie == '4w' ) $weken = 13;
		if( $this->_frequentie == 'm' ) $weken = 12;

		$jaarloon = floor( ($loonvoorloonheffing * $weken )/54 ) * 54;

		if( $this->_geboortejaar <= 1953 )
		{
			//$schijf[1]['min'] = 0;
			//$schijf[1]['max'] = 19982;
			//$schijf[1]['per'] = 18.65;
			//$schijf[1]['som'] = 0;

			$schijf[1]['min'] = $this->_vars[6]['value'];
			$schijf[1]['max'] = $this->_vars[7]['value'];
			$schijf[1]['per'] = $this->_vars[8]['value'];
			$schijf[1]['som'] = $this->_vars[9]['value'];

			//$schijf[2]['min'] = 19982;
			//$schijf[2]['max'] = 33791;
			//$schijf[2]['per'] = 22.890;
			//$schijf[2]['som'] = 3726;

			$schijf[2]['min'] = $this->_vars[10]['value'];
			$schijf[2]['max'] = $this->_vars[11]['value'];
			$schijf[2]['per'] = $this->_vars[12]['value'];
			$schijf[2]['som'] = $this->_vars[13]['value'];


			//$schijf[3]['min'] = 33791;
			//$schijf[3]['max'] = 67072;
			//$schijf[3]['per'] = 40.80;
			//$schijf[3]['som'] = 6965;

			$schijf[3]['min'] = $this->_vars[14]['value'];
			$schijf[3]['max'] = $this->_vars[15]['value'];
			$schijf[3]['per'] = $this->_vars[16]['value'];
			$schijf[3]['som'] = $this->_vars[17]['value'];

			//$schijf[4]['min'] = 67072;
			//$schijf[4]['max'] = 9999999;
			//$schijf[4]['per'] = 52.00;
			//$schijf[4]['som'] = 20405;


			$schijf[4]['min'] = $this->_vars[18]['value'];
			$schijf[4]['max'] = $this->_vars[19]['value'];
			$schijf[4]['per'] = $this->_vars[20]['value'];
			$schijf[4]['som'] = $this->_vars[21]['value'];

			//$arbeidskorting['M'] = 0.00904;
			//$arbeidskorting['N'] = 0.14449;
			//$arbeidskorting['P'] = 9309;
			//$arbeidskorting['ML_max'] = 86;
			//$arbeidskorting['K_max'] = 1645;

			$arbeidskorting['M'] = $this->_vars[38]['value'];
			$arbeidskorting['N'] = $this->_vars[39]['value'];
			$arbeidskorting['P'] = $this->_vars[40]['value'];
			$arbeidskorting['ML_max'] = $this->_vars[41]['value'];
			$arbeidskorting['K_max'] = $this->_vars[42]['value'];

			//if( $jaarloon < 36057 ) $heffingskorting = 2254;
			//if( $jaarloon >= 36057 ) $heffingskorting = 1222;
			//$heffingskorting_h = 0.02443;
			//$heffingskorting_h_max = 1151;

			if( $jaarloon < $this->_vars[48]['value'] ) $heffingskorting = $this->_vars[49]['value'];
			if( $jaarloon >= $this->_vars[48]['value'] ) $heffingskorting = $this->_vars[50]['value'];
			$heffingskorting_h = $this->_vars[51]['value'];
			$heffingskorting_h_max = $this->_vars[52]['value'];
		}

		if( $this->_geboortejaar > 1953 )
		{
			//$schijf[1]['min'] = 0;
			//$schijf[1]['max'] = 19982;
			//$schijf[1]['per'] = 36.55;
			//$schijf[1]['som'] = 0;

			$schijf[1]['min'] = $this->_vars[22]['value'];
			$schijf[1]['max'] = $this->_vars[23]['value'];
			$schijf[1]['per'] = $this->_vars[24]['value'];
			$schijf[1]['som'] = $this->_vars[25]['value'];

			//$schijf[2]['min'] = 19982;
			//$schijf[2]['max'] = 33791;
			//$schijf[2]['per'] = 40.80;
			//$schijf[2]['som'] = 7303;

			$schijf[2]['min'] = $this->_vars[26]['value'];
			$schijf[2]['max'] = $this->_vars[27]['value'];
			$schijf[2]['per'] = $this->_vars[28]['value'];
			$schijf[2]['som'] = $this->_vars[29]['value'];

			//$schijf[3]['min'] = 33791;
			//$schijf[3]['max'] = 67072;
			//$schijf[3]['per'] = 40.80;
			//$schijf[3]['som'] = 12937;

			$schijf[3]['min'] = $this->_vars[30]['value'];
			$schijf[3]['max'] = $this->_vars[31]['value'];
			$schijf[3]['per'] = $this->_vars[32]['value'];
			$schijf[3]['som'] = $this->_vars[33]['value'];

			//$schijf[4]['min'] = 67072;
			//$schijf[4]['max'] = 9999999;
			//$schijf[4]['per'] = 52.00;
			//$schijf[4]['som'] = 26515;

			$schijf[4]['min'] = $this->_vars[34]['value'];
			$schijf[4]['max'] = $this->_vars[35]['value'];
			$schijf[4]['per'] = $this->_vars[36]['value'];
			$schijf[4]['som'] = $this->_vars[37]['value'];

			//$arbeidskorting['M'] = 0.01772;
			//$arbeidskorting['N'] = 0.28317;
			//$arbeidskorting['P'] = 9309;
			//$arbeidskorting['ML_max'] = 165;
			//$arbeidskorting['K_max'] = 3223;

			$arbeidskorting['M'] = $this->_vars[43]['value'];
			$arbeidskorting['N'] = $this->_vars[44]['value'];
			$arbeidskorting['P'] = $this->_vars[45]['value'];
			$arbeidskorting['ML_max'] = $this->_vars[46]['value'];
			$arbeidskorting['K_max'] = $this->_vars[47]['value'];

			//$heffingskorting = 2254;
			//$heffingskorting_h = 0.04787;
			//$heffingskorting_h_max = 2254;

			$heffingskorting = $this->_vars[53]['value'];
			$heffingskorting_h = $this->_vars[54]['value'];
			$heffingskorting_h_max = $this->_vars[55]['value'];
		}


		$ml = min( ($arbeidskorting['M'] * $jaarloon), $arbeidskorting['ML_max']);

		//schijf bepalen
		if( $jaarloon > $schijf[1]['min'] ) $schijf_nr = 1;
		if( $jaarloon > $schijf[2]['min'] ) $schijf_nr = 2;
		if( $jaarloon > $schijf[3]['min'] ) $schijf_nr = 3;
		if( $jaarloon > $schijf[4]['min'] ) $schijf_nr = 4;

		$afdragen = ($jaarloon - $schijf[$schijf_nr]['min']) * ($schijf[$schijf_nr]['per']/100) + $schijf[$schijf_nr]['som'];

		//show($jaarloon);

		if( $jaarloon <= 9309 )
		{
			$k = $jaarloon * $arbeidskorting['M'];
		}
		elseif( $jaarloon > 9309 && $jaarloon <= 20108 )
		{
			$k = $ml + $arbeidskorting['N'] * ($jaarloon - $arbeidskorting['P']);
		}
		elseif( $jaarloon > 20108 && $jaarloon <= 32444 )
		{
			$k = $arbeidskorting['K_max'];
		}
		elseif( $jaarloon > 32444 && $jaarloon <= 121972 )
		{
			$k = $arbeidskorting['K_max'] - ( 0.036 * ( $jaarloon - 32444)  );
		}
		else
		{
			$k = 0;
		}

		if( $this->_loonheffingskorting == 1)
			$k_max = $arbeidskorting['K_max'];
		else
			$k_max = 0;


		//$h_max = floor(min( (($jaarloon - $schijf[1]['max']) * $heffingskorting_h), $heffingskorting_h_max));
		//show($h_max);
/*

		if( $this->_loonheffingskorting == 1)
			$korting = $heffingskorting - $h_max;
		else
			$korting = 0;*/
		if( $this->_loonheffingskorting == 1)
		{
			$korting = 0;
			if ($jaarloon <= 19982)
			{
				$korting = $heffingskorting;
			}
			elseif ($jaarloon > 19982 && $jaarloon <= 67068)
			{
				$korting = $heffingskorting - $heffingskorting_h * ( $jaarloon - 19982);
			}
			elseif ($jaarloon > 20108 && $jaarloon <= 32444)
			{
				$korting = 0;
			}
		}
		else
		{
			$korting = 0;
		}

		//show('Heffingkorting:' . $korting/$weken);
		//show('Arbeidskorting: '  . (min( $k, $k_max )/52)); //61.98
		//show('Totaal:' . (min( $k, $k_max ) + $korting)/$weken);


		$loonheffing = ((($afdragen - (min( $k, $k_max ) + $korting))) / $weken) * -1;

		if( $loonheffing > 0 )
			$loonheffing = 0;

		$array['loonheffing']['netto'] = round($loonheffing,2);

		return $array;
	}



	// --------------------------------------------------------------------

	/**
	 * pensioenregels
	 *
	 */
	private function berekenPensioen( $som_netto_prestatie = '' )
	{
		//$franchise['bouw'] 	= 6.31;
		//$franchise['NBBU'] 	= 6.32;

		$franchise['bouw'] 	= $this->_vars[56]['value'];
		$franchise['NBBU'] 	= $this->_vars[57]['value'];

		//bouw pensioen
		if( $this->_cao == 'bouw' )
		{
			if( $this->_tech_admin == 'T' )
			{
				//$array['pensioen']['percentage'] 			= 7.3427;
				//$array['pensioen_AOP']['percentage'] 		= 0.0750;
				//$array['pensioen_55_min']['percentage'] 	= 3.7077;
				//$array['pensioen_55_plus']['percentage'] 	= 1.2500;

				$array['pensioen']['percentage'] 			= $this->_vars[58]['value'];
				$array['pensioen_AOP']['percentage'] 		= $this->_vars[59]['value'];
				$array['pensioen_55_min']['percentage'] 	= $this->_vars[60]['value'];
				$array['pensioen_55_plus']['percentage'] 	= $this->_vars[61]['value'];
			}
			if( $this->_tech_admin == 'A' )
			{
				//$array['pensioen']['percentage'] 			= 8.1577;
				//$array['pensioen_AOP']['percentage'] 		= 0.0750;
				//$array['pensioen_55_min']['percentage'] 	= 1.1025;
				//$array['pensioen_55_plus']['percentage'] 	= 0.6000;

				$array['pensioen']['percentage'] 			= $this->_vars[62]['value'];
				$array['pensioen_AOP']['percentage'] 		= $this->_vars[63]['value'];
				$array['pensioen_55_min']['percentage'] 	= $this->_vars[64]['value'];
				$array['pensioen_55_plus']['percentage'] 	= $this->_vars[65]['value'];
			}

			$pensioen_factor = 1.08  * $som_netto_prestatie;

			$array['pensioen']['resultaat'] = ($pensioen_factor - ($this->_uren * $franchise['bouw'])) * ($array['pensioen']['percentage']/100) * -1;
			$array['pensioen_AOP']['resultaat'] = ($pensioen_factor - ($this->_uren * $franchise['bouw'])) * ($array['pensioen_AOP']['percentage']/100) * -1;
			$array['pensioen_55_min']['resultaat'] = ($pensioen_factor - ($this->_uren * $franchise['bouw'])) * ($array['pensioen_55_min']['percentage']/100) * -1;
			$array['pensioen_55_plus']['resultaat'] = $pensioen_factor * ($array['pensioen_55_plus']['percentage']/100) * -1;

		}

		//bouw pensioen
		if( $this->_cao == 'NBBU' && $this->_pensioen == 1 )
		{
			//$array['pensioen']['percentage'] = 12;
			$array['pensioen']['percentage'] = $this->_vars[66]['value'];
			$array['pensioen']['resultaat'] = (($this->_bruto + $this->_prestatietoeslag) - $franchise['NBBU']) * ($array['pensioen']['percentage']/100) * 0.3333 * $this->_uren * -1;
		}

		$array['pensioen_totaal']['resultaat'] = 0;

		//optellen
		foreach( $array as $index => $a )
		{
			$array['pensioen_totaal']['resultaat']  += $a['resultaat'];
		}




		return $array;
	}


	// --------------------------------------------------------------------

	/**
	 * percentages ophalen
	 *
	 */
	private function percentage( $naam )
	{
		// wachtdagcompensatie
		if( $naam == 'wachtdagcompensatie' )
		{
			//alleen technisch voorlopig
			//if( $this->_tech_admin == 'T' )	return 1.16;
			//if( $this->_tech_admin == 'A' )	return 0.71;

			if( $this->_tech_admin == 'T' )	return $this->_vars[67]['value'];
			if( $this->_tech_admin == 'A' )	return $this->_vars[68]['value'];
		}

		// wachtdagcompensatie
		if( $naam == 'aanv_zw' )
		{
			//alleen technisch voorlopig
			//if( $this->_tech_admin == 'T' )	return 1.10;
			//if( $this->_tech_admin == 'A' )	return 0.58;

			if( $this->_tech_admin == 'T' )	return $this->_vars[69]['value'];
			if( $this->_tech_admin == 'A' )	return $this->_vars[70]['value'];
		}

		// wachtdagcompensatie
		if( $naam == 'wga' )
		{
			//alleen technisch voorlopig
			//return 0.4350;
			return $this->_vars[71]['value'];
		}

	}



	// --------------------------------------------------------------------

	/**
	 * percentages ophalen
	 *
	 */
	public function getRecentSearch( $id = '' )
	{
		$sql = "SELECT * FROM proforma_zoekopdrachten WHERE id = '".intval($id)."'";
		$query = $this->db_user->query($sql);

		$data = $query->row_array();

		return $data;

	}

	// --------------------------------------------------------------------

	/**
	 * percentages ophalen
	 *
	 */
	public function getRecentSearches( $limit = 15 )
	{
		$sql = "SELECT * FROM proforma_zoekopdrachten WHERE user_id = '".intval($this->logindata['user_id'])."' ORDER BY id DESC LIMIT ". intval($limit);
		$query = $this->db_user->query($sql);

		if ($query->num_rows() == 0)
			 return false;

		foreach ($query->result_array() as $row)
		{
			$data[] = $row;
		}

		return $data;

	}

	// --------------------------------------------------------------------

	/**
	 * percentages ophalen
	 *
	 */
	private function saveSearch()
	{
		
		if( isset($this->_netto_loon) && $this->_netto_loon > 0)
		{
			$insert['user_id'] = $this->user->user_id;
			$insert['bruto'] = $this->_bruto;
			$insert['netto_loon'] = $this->_netto_loon;
			$insert['geboortejaar'] = $this->_geboortejaar;
			$insert['loonheffingskorting'] = $this->_loonheffingskorting;
			$insert['prestatietoeslag'] = $this->_prestatietoeslag;
			$insert['cao'] = $this->_cao;
			$insert['pensioen'] = $this->_pensioen;
			$insert['uren'] = $this->_uren;
			$insert['dagen'] = $this->_dagen;
			$insert['frequentie'] = $this->_frequentie;
			$insert['vakantiegeld_direct'] = $this->_vakantiegeld_direct;
			$insert['vakantieuren_direct'] = $this->_vakantieuren_direct;
			$insert['atv_direct'] = $this->_atv_direct;
			$insert['et_regeling'] = $this->_et_regeling;
			$insert['et_huisvesting'] = $this->_et_huisvesting;
			$insert['et_km'] = $this->_et_km;
			$insert['km'] = $this->_km;
			$insert['et_verschil_leven'] = $this->_et_verschil_leven;

			$insert['verkooptarief'] = $this->_verkooptarief;
			$insert['factor_belast'] = $this->_factor_belast;
			$insert['factor_onbelast'] = $this->_factor_onbelast;
			$insert['doelgroepverklaring'] = $this->_doelgroepverklaring;

			$this->db_user->insert('proforma_zoekopdrachten', $insert);

			if ($this->db_user->insert_id() > 0)
				return $this->db_user->insert_id();
		}
	}


}