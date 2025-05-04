<?php defined('SYSPATH') or die('No direct script access.');
/**
* @package    ParkResident/Application
 * @category   Base
 * @author     Artonit
 * @copyright  (c) 2025 Artonit Team
 * @license    http://artonit/ru 
 
 */

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
