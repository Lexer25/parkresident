<?php defined('SYSPATH') or die('No direct script access.');
/**
* @package    ParkResident/Application
 * @category   Base
 * @author     Artonit
 * @copyright  (c) 2025 Artonit Team
 * @license    http://artonit/ru 
 
 */

class Controller_Logout extends Controller {

	public function before()
	{
			//echo Debug::vars('7', $_GET); exit;
			parent::before();
			$session = Session::instance();
			
			
	}
	public function action_index()
	{
		Auth::instance()->logout();
		//Session::instance()->destroy();
		//$this->redirect('rubic');
		//echo Debug::vars('7', $_GET, Arr::get($_GET, 'action', 'rubic')); exit;
		$this->redirect(Arr::get($_GET, 'action', 'rubic'));
	}

}

