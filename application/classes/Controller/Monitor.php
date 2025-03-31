<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Monitor extends Controller_Template { // Монитор событий онлайн. Ожидаю увидеть проезды автомобилей
	
	
	public $template = 'template';
	public function before()
	{
			
			parent::before();
			$session = Session::instance();
			
			I18n::load('rubic');
			
	}

	public function action_index()// 
	{
		$content = View::factory('monitor/monitor');
        $this->template->content = $content;
		
	}
	

} 
