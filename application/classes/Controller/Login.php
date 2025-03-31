<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Login extends Controller {

	public function before()
	{
			//echo Debug::vars('9', $_POST); exit;
			parent::before();
			$session = Session::instance();
			
			
	}
	public function action_index()
	{
		if (!empty($_POST)) {
             	$username = Arr::get($_POST, 'username');
                $password = Arr::get($_POST, 'password');
			
                if (Auth::instance()->login($username, $password)) {
                $user = Auth::instance()->get_user();
				}
			}
			//if(Arr::get($_POST, 'action') != 'rubic/index') $this->redirect(Arr::get($_POST, 'action'));
			$this->redirect(Arr::get($_POST, 'action', 'rubic'));
	}

}

