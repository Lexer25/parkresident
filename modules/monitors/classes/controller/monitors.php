<?php defined('SYSPATH') or die('No direct script access.');
/**


*/
class Controller_monitors extends Controller_Template { 
		
	public $view = 'result';//view для показа результата
	public $template = 'template';
	
	public function before()
	{
		parent::before();
		$session = Session::instance();

	}


	public function action_index()
	{
		//echo Debug::vars('19');exit;
		
		
		$this->template->content = View::factory('monitor\list')
			//->bind('cards', $list)
			//->bind('cardsList', $list)
			//->bind('catdTypelist', $catdTypelist)
			->bind('alert', $fl)
			->bind('arrAlert', $arrAlert)
			//->bind('filter', $filter)
			;
	}
	
	
	
	
	public function action_getEvent()
	{
	
		return 'Now: '. time();
		
			
	
	}
	
	
	
	
}
