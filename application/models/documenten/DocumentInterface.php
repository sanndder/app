<?php

namespace models\Documenten;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * Aanmaken en beheer PDF templates
 *
 */
interface DocumentInterface
{
	public function setHeader();
	public function setFooter();
	public function setBody();
	public function setHTML();
	public function build();
	public function setPDFInfo();
	public function pdf();
}

?>