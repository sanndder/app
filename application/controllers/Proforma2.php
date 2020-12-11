<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Account en userbeheer
 */

class Proforma extends MY_Controller {


	//-----------------------------------------------------------------------------------------------------------------
	// Constructor
	//-----------------------------------------------------------------------------------------------------------------
	public function __construct()
	{
		parent::__construct();
		
		if(	$this->user->user_type != 'werkgever' && $this->user->user_type != 'uitzender'  )forbidden();
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Proforma pdf strook
	 *
	 */
	public function loonstrook( $id = '' )
	{
		$this->load->model('proforma_model', 'proforma');
		
		if( $id == '' )
			die('Ongeldige loonstrook id');
		
		$_POST = $this->proforma->getRecentSearch( $id );
		$return = $this->proforma->setInputData($_POST);
		if ($return['status'] != 'success')
		{
			die('Er gaat wat mis');
		}
		else
		{
			$result = $this->proforma->calculateLoon();
			$this->proforma->pdfLoonstrook($result['data']);
		}
	}
	
	
	//-----------------------------------------------------------------------------------------------------------------
	// Proforma pagina
	//-----------------------------------------------------------------------------------------------------------------
	public function index()
	{
		$this->load->model('proforma_model', 'proforma');
		
		//show($_POST);
		if( count($_POST) == 0 )
		{
			$_POST['geboortejaar'] = 1980;
			$_POST['loonheffingskorting'] = 1;
			$_POST['bruto'] = 10.00;
			$_POST['prestatietoeslag'] = 0;
			$_POST['km'] = 0;
			$_POST['cao'] = 'NBBU';
			$_POST['pensioen'] = 0;
			$_POST['uren'] = 40;
			$_POST['dagen'] = 5;
			$_POST['frequentie'] = 'w';
			$_POST['vakantieuren_direct'] = 0;
			$_POST['vakantiegeld_direct'] = 0;
			$_POST['atv_direct'] = 0;
			$_POST['et_regeling'] = 0;
			$_POST['et_huisvesting'] = 0;
			$_POST['et_km'] = 0;
			$_POST['et_verschil_leven'] = 0;
			$_POST['doelgroepverklaring'] = 0;
			$_POST['verkooptarief'] = 0;
			$_POST['factor_belast'] = 1.73;
			$_POST['factor_onbelast'] = 1.55;
		}

		//verschil levensstandaard
		/*
		$cola_lijst = $this->verloning->getColaLijst();
		$this->smarty->assign('cola_lijst', $cola_lijst);*/
		
		//bruto netto
		if( isset($_POST['go']) )
		{
			$return = $this->proforma->setInputData($_POST);
			if ($return['status'] != 'success')
			{
				$this->smarty->assign('msg', msg('danger', $return['error']));
			}
			else
			{
				$result = $this->proforma->calculateLoon();
				//show($result);
				$this->smarty->assign('id', $result['id']);
				$_POST['netto_loon'] = $result['netto_loon'];
				$_POST['uitbetaald'] = $result['uitbetaald'];
				$_POST['heffing'] = abs($result['data']['loonheffing']['netto']);
			}
		}
		
		//netto bruto
		if( isset($_POST['netto_bruto']) )
		{
			$return = $this->proforma->setInputData($_POST);
			if ($return['status'] != 'success')
			{
				$this->smarty->assign('msg', msg('danger', $return['error']));
			}
			else
			{
				$result = $this->proforma->calculateBrutoUurloon();
				
				$_POST['netto_loon'] = $result['netto_loon'];
				$_POST['uitbetaald'] = $result['uitbetaald'];
				$_POST['bruto'] = $result['data']['uurloon']['factor_1'];
				$this->smarty->assign('result_netto_bruto', true);
				$this->smarty->assign('result', $result);
				
			}
		}

		$cola_lijst = array( array( 'cola' => '28.77', 'land' => 'Polen'));
		$this->smarty->assign('cola_lijst', $cola_lijst);

		$this->smarty->display('proforma/overzicht.tpl');
	}

}
