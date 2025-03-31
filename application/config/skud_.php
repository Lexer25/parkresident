<?php defined('SYSPATH') or die('No direct script access.');
 
return array(
	'skud_list'=>array(
	'1' =>	array(
			'name'=>'Рубитех',
			'fb' => array(
				'type'			=> 'pdo',
				'connection'	=> array(
					'dsn'		=> 'ODBC:SDUO',
					'username'	=> 'SYSDBA',
					'password'	=> 'temp',
					'charset'   => 'windows-1251',
					)
				),
			),
		)
	
);