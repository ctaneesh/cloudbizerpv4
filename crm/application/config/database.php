<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// $active_group = 'default';
// $query_builder = TRUE;
// $hostname ='localhost';
// $username ='u670329957_erpadmin';
// $password ='O*K@fs8G6n';
// $database ='u670329957_erp';

$active_group = 'default';
$query_builder = TRUE;
// $hostname ='localhost';
// $username ='u670329957_clouduserv4';
// $password ='Christal12#';
// $database ='u670329957_cloudbizerpv4';

// $hostname ='localhost';
// $username ='u670329957_cloudbizerpv4';
// $password ='Christal12#';
// $database ='u670329957_cloudbizerpv5';

$hostname ='localhost';
$username ='root';
$password ='';
$database ='erp_change_name';

$db['default'] = array(
	'dsn'	=> '',
	'hostname' => $hostname,
	'username' => $username,
	'password' => $password,
	'database' => $database,
	'dbdriver' => 'mysqli',
	'dbprefix' => '',
	'pconnect' => FALSE,
	'db_debug' => (ENVIRONMENT !== 'production'),
	'cache_on' => FALSE,
	'cachedir' => '',
	'char_set' => 'utf8',
	'dbcollat' => 'utf8_general_ci',
	'swap_pre' => '',
	'encrypt' => FALSE,
	'compress' => FALSE,
	'stricton' => FALSE,
	'failover' => array(),
	'save_queries' => TRUE
);
