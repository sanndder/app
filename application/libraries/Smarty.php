<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once( 'application/third_party/smarty/Smarty.class.php' );


class Smarty extends Smarty_class{

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function __construct()
	{
		parent::__construct();

		$this->setTemplateDir('application/views');
		$this->setCompileDir('application/views/_compile');

	}

}
