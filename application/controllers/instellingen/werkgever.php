<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Instellingen controller
 */

class Werkgever extends MY_Controller {


	//--------------------------------------------------------------------------
	// Constructor
	//--------------------------------------------------------------------------
	public function __construct()
	{
		parent::__construct();
	}


	//--------------------------------------------------------------------------
	// aanpassen bedrijfsgegevens
	//--------------------------------------------------------------------------
	public function bedrijfsgegevens()
	{

		$this->smarty->display('instellingen/werkgever/bedrijfsgegevens.tpl');
	}

	//--------------------------------------------------------------------------
	// logo uploaden
	//--------------------------------------------------------------------------
	public function logo()
	{

		$this->smarty->display('instellingen/werkgever/logo.tpl');
	}

}
