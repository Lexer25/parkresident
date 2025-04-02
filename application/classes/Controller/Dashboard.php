<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Dashboard extends Controller_Template {

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
			I18n::load('rubic');
			
	}
	
	public function action_index()//Первая страница веб-панели. Показываю жилые комплексы + возможность добавить ЖК resident_place
	{
		$_SESSION['menu_active']='rubic';
		$query=Validation::factory($this->request->query());
					$query->rule('id_parking', 'not_empty')
							->rule('id_parking', 'digit')
							;
					if($query->check())
					{
						$id_resident=Arr::get($query, 'id_parking'); // имеется номер ЖК, и надо показывать именно его
						
						
					} else 
					{
						$id_resident=0; // номер родительской паровки не указан. надо показывать все ЖК.
						$id_resident=Model::factory('residentPlace')->get_list();
						
					}
		$content = View::factory('dashboard/list', array(
			
			'id_resident'=>$id_resident,
			
		
		));
        $this->template->content = $content;
	}
	
	
	public function action_getidevent()
	{
		$t1=microtime(1);
		$sql='select gen_id(gen_event_id,0)
			from RDB$DATABASE';
		$id_event = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->get('GEN_ID');
		
		
		
		$this->response->headers("Access-Control-Allow-Origin", "*");
		$this->response->headers("Content-Type", "application/json");
		$this->response->body(json_encode(array('GEN_ID'=>$id_event)));
		
	}
	
	
	public function action_geteventfrom()
	{
		$t1=microtime(1);
		$id = $this->request->param('id');
		//$id=3209720;
		Log::instance()->add(Log::DEBUG, '57 запрос события '. $id);
		$event=new Eventonline($id);
		Log::instance()->add(Log::DEBUG, '59 ответ на запрос '.$id.' '.Debug::vars(json_encode(get_object_vars($event))));
		//echo Debug::vars('26', $event, json_encode(get_object_vars($event))); exit;
		$this->response->headers("Access-Control-Allow-Origin", "*");
		$this->response->headers("Content-Type", "application/json");
		$this->response->body(json_encode(get_object_vars($event)));
		
	}
	
	/**
	информация по контакту
	*/
	public function action_getContactInfo()
	{
		$t1=microtime(1);
		$id = $this->request->param('id');
		$id=7582;//фиксированное значение для отладки
		//$id=3209720;
		Log::instance()->add(Log::DEBUG, '57 запрос события '. $id);
		$event=new Contact($id);
		Log::instance()->add(Log::DEBUG, '59 ответ на запрос '.$id.' '.Debug::vars(json_encode(get_object_vars($event))));
		//echo Debug::vars('26', $event, json_encode(get_object_vars($event))); exit;
		$this->response->headers("Access-Control-Allow-Origin", "*");
		$this->response->headers("Content-Type", "application/json");
		$this->response->body(json_encode(get_object_vars($event)));
		
	}
	
	
} // End Welcome
