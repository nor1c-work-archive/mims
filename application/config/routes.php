<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'index';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['dummy_data'] = 'Index/dummy_data';
$route['pickSomethingDummy'] = 'Index/pickSomethingDummy';

$route['assets/v/(:any)'] = 'assets';
$route['assets/form/(:any)'] = 'assets/form';
$route['assets/data/(:any)'] = 'assets/data';
$route['assets/addableForm/(:any)'] = 'assets/addableForm';
$route['assets/expandableContent/(:any)'] = 'assets/expandableContent';

$route['tracking/v/(:any)'] = 'tracking';

$route['master/v/(:any)'] = 'master/index';

$route['location/v/(:any)'] = 'location/index';
