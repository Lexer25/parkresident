<?php defined('SYSPATH') or die('No direct script access.');
/*
3.05.2025 
Setup - контроллер для автоматизации настройки парковочной системы

*/


class Controller_Setup extends Controller_Template { // класс описывает въезды и вызды (ворота) для парковочных площадок
	
	
	public $template = 'template';
	public $tableList=array(
			'HL_EVENTCODE',
			'HL_EVENTS',
			'HL_GARAGENAME',
			'HL_ORGACCESS',
			'HL_GARAGE',
			'HL_RESIDENT',
			'HL_INSIDE',
			'HL_MESSAGES',
			//'HL_COUNTERS',
			'HL_PARAM',
			'HL_PARKING',
			'HL_PLACE',
			//'HL_PLACEGROUP',
			'HL_SETTING'
		);
		
	public	$procedureList=array(
				'HL_UPDATE_GARAGE_NAME',
				
				'VALIDATEPASS_HL_PARKING',
				'VALIDATEPASS_HL_PARKING_2',
				'VALIDATEPASS_HL_PARKING_3',
				'REGISTERPASS_HL_2',
				
			);
			
	public	$dataList=array(
				'HL_EVENTCODE',
				'HL_MESSAGES',
				'HL_RESIDENT',
				'HL_GARAGE',
				
			);
			
	
	
	public function before()
	{
			
			parent::before();
			$session = Session::instance();
	
	}
	
	
	
	/**3.05.2025 Добавление категорий доступа в СКУД.
	* каждая парковочная площадка добавляется в СКУД как категорию доступа с таким же названием. 
	*/
	public function action_addAccessname()
	{
		echo Debug::vars('65', $_POST);exit;
		$this->redirect('checkdb');
		
	}
	
	
	
} 
