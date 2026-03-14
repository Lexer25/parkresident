<?php defined('SYSPATH') OR die('No direct access allowed.');

return array
(
	'fb' => array(
		'type'			=> 'pdo',
		'connection'	=> array(
			//'dsn'		=> 'odbc:SDUO',
			//'dsn'		=> 'odbc:ParkResident',
			'dsn'		=> 'odbc:HL',
			'charset'   => 'windows-1251',
			'username'	=> 'SYSDBA',
			'password'	=> 'temp',
			)
		),
    'charset' =>  'UTF8',
    'profiling' =>  TRUE,

);

