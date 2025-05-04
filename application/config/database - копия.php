<?php defined('SYSPATH') OR die('No direct access allowed.');

return array
(
	'fb' => array(
		'type'			=> 'pdo',
		'connection'	=> array(
			'dsn'		=> 'odbc:SDUO',
			//'dsn'		=> 'odbc:tt2t',
			//'dsn'		=> 'odbc:ParkResident',
			//'dsn'		=> 'firebird:dbname=127.0.0.1:C:\\temp3\\HL.FDB',
			//'dsn'		=> 'firebird:dbname=26.98.93.81:C:\\Program Files (x86)\\CardSoft\\DuoSE\\Access\\ShieldPro_rest.gdb',
			//'dsn'		=> 'firebird:dbname=127.0.0.1:C:\\vnii\\vnii.gdb',
			'charset'   => 'windows-1251',
			//'charset'   => 'UTF8',
			'username'	=> 'SYSDBA',
			'password'	=> 'temp',
			)
		),
	
);

