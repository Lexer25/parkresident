<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Download extends Controller {

	public function action_index()
	{
		//echo Debug::vars('7', $_SESSION); exit;
		$list=iconv('UTF-8','windows-1251', Session::instance()->get('eventTable'));
		$filename=iconv('UTF-8','windows-1251', Session::instance()->get('filename'));
		Session::instance()->delete('eventTable');
		
		$this->response->body($list);
		$this->response->send_file(true, $filename); 
	}

}

