<?php defined('SYSPATH') or die('No direct script access.');
/*20.11.2024 Этот файл является основой всех контроллеров.
* сюда в раздел befor надо добавить проверку авторизации. Если неуспешно - то переход на ввод логина
* из других контроллеров авторизацию можно будет убрать.
*/

//class Controller_Template extends Controller_Template {
abstract class Controller_Template extends Kohana_Controller_Template {

	    
	
	 
    public function before() {
        parent::before();
     	$session = Session::instance();
		
		$_SESSION['menu_active']=$this->request->controller().'/'.$this->request->action();
		
		
    }
	
}

