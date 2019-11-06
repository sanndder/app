<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


function getDagNaam( $index = NULL )
{
	
	$aDagen[0] = 'zondag';
	$aDagen[1] = 'maandag';
	$aDagen[2] = 'dinsdag';
	$aDagen[3] = 'woensdag';
	$aDagen[4] = 'donderdag';
	$aDagen[5] = 'vrijdag';
	$aDagen[6] = 'zaterdag';
	$aDagen[7] = 'zondag';
	
	if( $index === NULL )
		return $aDagen;
	
	$index = intval( $index );
	return $aDagen[$index];
}

function getDagNaamKort( $index )
{
	$aDagenShort[1] = 'ma';
	$aDagenShort[2] = 'di';
	$aDagenShort[3] = 'wo';
	$aDagenShort[4] = 'do';
	$aDagenShort[5] = 'vr';
	$aDagenShort[6] = 'za';
	$aDagenShort[7] = 'zo';

	return $aDagenShort[$index];
}

function getMaandNaam( $index )
{
	$index = intval( $index );

	$aMaanden[1] = 'januari';
	$aMaanden[2] = 'februari';
	$aMaanden[3] = 'maart';
	$aMaanden[4] = 'april';
	$aMaanden[5] = 'mei';
	$aMaanden[6] = 'juni';
	$aMaanden[7] = 'juli';
	$aMaanden[8] = 'augustus';
	$aMaanden[9] = 'september';
	$aMaanden[10] = 'oktober';
	$aMaanden[11] = 'november';
	$aMaanden[12] = 'december';

	return $aMaanden[$index];
}


?>