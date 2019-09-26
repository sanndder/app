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

function arrayBurgerlijkestaat()
{
	$burgelijkestaat['0'] = 'Niet van toepassing';
	$burgelijkestaat['1'] = 'Ongehuwd';
	$burgelijkestaat['2'] = 'Gehuwd';
	$burgelijkestaat['3'] = 'Duurzaam gescheiden';
	$burgelijkestaat['4'] = 'Geregistreerd partnerschap';
	$burgelijkestaat['5'] = 'Weduwstaat';

	return $burgelijkestaat;
}

function arrayBurgerlijkestaatNieuw()
{
	$burgelijkestaat['1'] = '1';
	$burgelijkestaat['2'] = '2';
	$burgelijkestaat['3'] = '3';
	$burgelijkestaat['4'] = '4';
	$burgelijkestaat['5'] = '7';

	return $burgelijkestaat;
}

function arrayIdSoort()
{
	$idSoort['0'] = 'Geen identiteitsbewijs';
	$idSoort['1'] = 'Nederlands paspoort';
	$idSoort['2'] = 'Nederlandse identiteitskaart';
	$idSoort['3'] = 'Identiteitskaart Nederlandse Gemeenten';
	$idSoort['4'] = 'Verblijfsdocumenten I t/m IV en EU/EER';
	$idSoort['6'] = 'W-document';
	$idSoort['7'] = 'Vreemdelingenpaspoort';
	$idSoort['8'] = 'Vluchtelingenpaspoort';
	$idSoort['9'] = 'Paspoort niet-EER-land met verblijfsvergunning';
	$idSoort['10'] = 'Paspoort EER-Land';
	$idSoort['11'] = 'Diplomatiek paspoort';
	$idSoort['12'] = 'Dienstpaspoort';

	return $idSoort;
}

function arrayIdSoortShort()
{
	$idSoort['0'] = 'Geen';
	$idSoort['1'] = 'Paspoort';
	$idSoort['2'] = 'ID-kaart';
	$idSoort['3'] = 'ID-kaart';
	$idSoort['4'] = 'Verblijfsdoc.';
	$idSoort['6'] = 'ID-kaart';
	$idSoort['7'] = 'Paspoort';
	$idSoort['8'] = 'Paspoort';
	$idSoort['9'] = 'Paspoort';
	$idSoort['10'] = 'Paspoort';
	$idSoort['11'] = 'Paspoort';
	$idSoort['12'] = 'Paspoort';

	return $idSoort;
}

function arrayIdSoortNieuw()
{
	$idSoort['0'] = 'XX';
	$idSoort['1'] = 'NP';
	$idSoort['2'] = 'EI';
	$idSoort['3'] = 'GI';
	$idSoort['4'] = 'V1';
	$idSoort['6'] = 'OIt';
	$idSoort['7'] = 'DO';
	$idSoort['8'] = 'VL';
	$idSoort['9'] = 'OI';
	$idSoort['10'] = 'NN';
	$idSoort['11'] = 'NN';
	$idSoort['12'] = 'NN';

	return $idSoort;
}



?>