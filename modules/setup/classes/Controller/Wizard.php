<?php defined('SYSPATH') or die('No direct script access.');
/*
03.05.2025 
Wizard - контроллера для настройки интеграции

*/


class Controller_Wizard extends Controller_Template { // класс описывает въезды и вызды (ворота) для парковочных площадок
	
	
	public $template = 'template';
	
	
	
	public function before()
	{
			
			parent::before();
			$session = Session::instance();
	
	}
	
	
	public function action_index()
	{
		
		
		$content = View::factory('setup/wizard', array(
			
				
		));
        $this->template->content = $content;
		
	}
	
	
	
	
	
} 
