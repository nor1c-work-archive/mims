<?php

defined('BASEPATH') OR exit('No direct script access allowed');

$active_group = 'default';
$active_record = TRUE;

$db['default']['hostname'] = env('DB_HOSTNAME');
$db['default']['username'] = env('DB_USERNAME');
$db['default']['password'] = env('DB_PASSWORD');
$db['default']['database'] = env('DB_DATABASE');
$db['default']['dbdriver'] = env('DB_DRIVER');
$db['default']['port']     = env('DB_PORT');
$db['default']['dbprefix'] = '';
$db['default']['pconnect'] = FALSE;
$db['default']['db_debug'] = TRUE;
$db['default']['cache_on'] = FALSE;
$db['default']['cachedir'] = '';
$db['default']['char_set'] = 'utf8';
$db['default']['dbcollat'] = 'utf8_general_ci';
$db['default']['swap_pre'] = '';
$db['default']['autoinit'] = TRUE;
$db['default']['stricton'] = FALSE;