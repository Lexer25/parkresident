<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Cvstest extends Controller_Template { // класс описывает въезды и вызды (ворота) для парковочных площадок
	
	
	public $template = 'template';
	public function before()
	{
			
			parent::before();
			$session = Session::instance();
			if (!empty($_POST)) {
             	$username = Arr::get($_POST, 'username');
                $password = Arr::get($_POST, 'password');
			
                if (Auth::instance()->login($username, $password)) {
                $user = Auth::instance()->get_user();
				}
			}
			//echo Debug::vars('9', $_POST, $_GET, Auth::instance()->logged_in(), $_SESSION);
			I18n::load('rubic');
			
	}
	
	
	public function action_index()// просмотр списка ГРЗ и их свойств
	{
		$_SESSION['menu_active']='grz';
			
		//echo Debug::vars('38', $_GET, $_POST, $id_parking); //exit;
		$id_parking=1;
		$grz_list=Model::Factory('grz')->get_list_grz($id_parking);//список ГРЗ
	
			$content = View::factory('rubic/grzList', array(
			'grz_list'=>$grz_list,
			));
	
		
        $this->template->content = $content;
	}
	
	public function grz_control()
	{
		echo Debug::vars('44 grz_control', $_POST); exit;
		
	}
	

/*

18.04.2023
Установка метки: ГРЗ в паркинге.
Происходит запись ГРЗ в таблицу HL_INSIDE
Делается запись в таблицу HL_EVENTS

*/

	public function action_car_in_parking()
	{
		//echo Debug::vars('51 car_in_parking', $_POST); exit;
		$post=Validation::factory($_POST);
		$post->rule('car_in_parking', 'not_empty')
			//->rule('car_in_parking', 'regex', array(':value', '/^[A-F\d]{10}+$/')) // https://regex101.com/ допустимы знаки только ГРЗ
			->rule('id_parking','not_empty') 
			->rule('id_parking','digit') 
			;
		if($post->check())
		{
			//внесение ГРЗ в таблицу HL_INSIDE для указанной парковки
			Model::factory('grz')->addToInside(Arr::get($post, 'car_in_parking'));

			//внесение события "Ручной въезд" ГРЗ для указанной парковки
			Model::factory('grz')->addEventsInsertGRZTomInside(Arr::get($post, 'car_in_parking'));			
			
				Session::instance()->set('ok_mess', array('result'=>'ГРЗ '.Arr::get($post, 'car_in_parking').' добавлен в список INSIDE'));
			$this->redirect('grz');
			
		} else {
			Session::instance()->set('e_mess', $post->errors('Valid_mess'));
			$this->redirect('grz');
		}
		$this->redirect('grz');
	}


/*

18.04.2023
Удаление метки: ГРЗ в паркинге.
Происходит удаление ГРЗ из таблицы HL_INSIDE
Делается запись в таблицу HL_EVENTS

*/	

	public function action_car_out_parking()
	{
		//echo Debug::vars('79 car_in_parking', $_POST); exit;
		$post=Validation::factory($_POST);
		$post->rule('car_out_parking', 'not_empty')
			//->rule('car_out_parking', 'regex', array(':value', '/^[A-F\d]{10}+$/')) // https://regex101.com/ допустимы знаки только ГРЗ
			->rule('id_parking','not_empty') 
			->rule('id_parking','digit') 
			;
		if($post->check())
		{
			//удадление ГРЗ из таблицу HL_INSIDE
			Model::factory('grz')->delFromInside(Arr::get($post, 'car_out_parking'));			
			//внесение события "Ручной выезд" ГРЗ для указанной парковки
			Model::factory('grz')->addEventsDeleteGRZFromInside(Arr::get($post, 'car_out_parking'));			
			
			Session::instance()->set('ok_mess', array('result'=>'ГРЗ '.Arr::get($post, 'car_out_parking').' удален из списка INSIDE'));
			$this->redirect('grz');
			
		} else {
			Session::instance()->set('e_mess', $post->errors('Valid_mess'));
			$this->redirect('grz');
		}
		$this->redirect('grz');
		
	}
	

	
/*

25.04.2023
имитация вЪезда и выезда ГРЗ из паркинга
направление проезда определяется параметров id_cam и настройками парковочной системы
  "messageId": "63d519ec-f4da-4555-9a5c-b7c82f594456",
  "plate": {
    "camera": 1,
    "channel": 1,
    "count": 17,
    "dateTime": "20230423T061004Z",
    "description": "---",
    "direction": 1,
    "groupId": -1,
    "id": 85844,
    "inList": 0,
    "passed": 2,
    "plate": "Y967KC777",
    "quality": "555555555000",
    "stayTimeMinutes": 0,
    "type": 0,
    "weight": 0
  }
}

*/	

	public function action_test_car_parking()
	{
		//echo Debug::vars('153 car_in_parking', $_POST); exit;
		$post=Validation::factory($_POST);
		$post->rule('test_door', 'not_empty')
			->rule('test_door','is_array') 
			;
		if($post->check())
		{
			foreach(Arr::get($post, 'test_door') as $key=>$value)
			{
				$id_cam=$key;
				$grz=$value;
			}
			//echo Debug::vars('162', $key, $value,  $id_cam, $grz,  Arr::get(Arr::get($post, 'test_out_door'), 0)); exit;
			$cvs_emul=array(
				  'messageId'=>com_create_guid(),
				  'plate'=>array(
					'camera'=> $id_cam,
					'channel'=> 1,
					'count'=> 17,
					'dateTime'=> gmdate('c', time()),
					'description'=> '---',
					'direction'=> 1,
					'groupId'=> -1,
					'id'=> 85844,
					'inList'=> 0,
					'passed'=> 2,
					'plate'=> $grz,
					'quality'=> '555555555000',
					'stayTimeMinutes'=> 0,
					'type'=> 0,
					'weight'=> 0
				  )
				);		
			
			try{
			$request = Request::factory('http://26.98.93.81:8080/cvs/cvstest/exec')
					->headers("Accept", "application/json")
					->headers("Content-Type", "application/json")
					->method('POST')
					->body(json_encode($cvs_emul))
					->execute();
					
				/* echo  Debug::vars('191', $request, 
				'EMERGENCY='.Log::EMERGENCY, 
				'ALERT='.Log::ALERT, 
				'CRITICAL='.Log::CRITICAL, 
				'ERROR='.Log::EMERGENCY, 
				'WARNING='.Log::WARNING, 
				'NOTICE='.Log::NOTICE, 
				'INFO='.Log::INFO, 
				'DEBUG='.Log::DEBUG
				); exit; */
				
			Session::instance()->set('ok_mess', array('result'=>$request->body()));
			//echo Debug::vars('207', $request->body()); exit;
			
			//return array('status'=>$request->status(), 'res'=>json_decode ($request->body(), true));
			$this->redirect('grz');
		} catch (Kohana_Request_Exception $e) {
			//echo  Debug::vars('195', $e->getMessage()); exit;
			
				Session::instance()->set('e_mess', array ('status'=>0, 'res'=>$e->getMessage()));
				$this->redirect('grz');
		}
		
		Session::instance()->set('ok_mess', array('result'=>'ГРЗ '.Arr::get($post, 'car_out_parking').' удален из списка INSIDE'));
			$this->redirect('grz');
			
		} else {
			//echo  Debug::vars('203', $post); exit;
			Session::instance()->set('e_mess', $post->errors('Valid_mess'));
			$this->redirect('grz');
		}
		return;
		
	}
	


	
	
	
	
} 
