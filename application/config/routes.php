<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'login';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['bedrijfsinformatie/(:any)'] 	= 'bedrijfsinformatie/index/$1';
$route['flexxoffice'] 			= 'bedrijfsinformatie/index';

$route['klantkaart/(:any)'] 	= 'klantkaart/index/$1';
$route['klantenkaart/(:any)/(:any)/(:any)'] 	= 'klantenkaart/index/$1/$2/$3';

$route['crm/uitzenders(:any)'] 					= 'crm/uitzenders/overzicht/$1';
$route['crm/uitzenders'] 						= 'crm/uitzenders/overzicht';

$route['crm/inleners(:any)'] 					= 'crm/inleners/overzicht/$1';
$route['crm/inleners'] 							= 'crm/inleners/overzicht';

$route['crm/werknemers(:any)'] 					= 'crm/werknemers/overzicht/$1';
$route['crm/werknemers'] 						= 'crm/werknemers/overzicht';

$route['crm/prospects'] 						= 'crm/prospects/prospects';

$route['crm/zzp(:any)'] 						= 'crm/zzp/overzicht/$1';
$route['crm/zzp'] 								= 'crm/zzp/overzicht';

$route['ureninvoer'] 							= 'ureninvoer/ureninvoer/index';
$route['ureninvoer/bijlage/(:any)'] 			= 'ureninvoer/ureninvoer/bijlage/$1';

$route['instellingen/werkgever/users/view/(:any)'] 				= 'instellingen/users/view/$1';
$route['instellingen/werkgever/users/view'] 					= 'instellingen/users/view';

$route['instellingen/werkgever/users/edit/(:any)'] 				= 'instellingen/users/edit/$1';
$route['instellingen/werkgever/users/edit'] 					= 'instellingen/users/edit';

$route['instellingen/werkgever/users/add/(:any)'] 				= 'instellingen/users/add/$1';
$route['instellingen/werkgever/users/add'] 						= 'instellingen/users/add';

$route['instellingen/werkgever/users/(:any)'] 					= 'instellingen/users/index/$1';
$route['instellingen/werkgever/users'] 							= 'instellingen/users/index';

/*
 * Aanmeldlinks doorverwijzen naar de aanmelding dossier
 */
$route['aanmelden/uitzender'] 					= 'crm/uitzenders/dossier/bedrijfsgegevens';
