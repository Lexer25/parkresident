<?php defined('SYSPATH') or die('No direct script access.');

class Controller_ResidentPlace extends Controller_Template { // класс описывает жилой комплекс (resident place)
	
	
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
	
	
	public function action_logout()// выход
	{
		Auth::instance()->logout();
		Session::instance()->delete('username');
		$this->redirect('rubic');
	}
	
	

	public function action_index()// главная страница при входе. Показываю жилые комплексы + возможность добавить ЖК resident_place
	{
		$_SESSION['menu_active']='rubic';
		$query=Validation::factory($this->request->query());
					$query->rule('id_parking', 'not_empty')
							->rule('id_card', 'digit')
							;
					if($query->check())
					{
						$id_parking=Arr::get($query, 'id_parking'); // имеется номер родительской парковки
						
						
					} else 
					{
						$id_parking=0; // номер родительской паровки не указан
					}
					
		
		//echo Debug::vars('38', $this->request->query(), $id_parking); //exit;
		$rubic_list=Model::Factory('rubic')->get_list_parking($id_parking);//список парковок
		$count_busy=Model::Factory('rubic')->count_busy();//список количества свободных мест
		$org_list=Model::Factory('rubic')->get_list_org();//список организация для формирования новой парковки
		
		
		$content = View::factory('rubic/ResidentPlace', array(
			'rubic_list'=>$rubic_list,
			'org_list'=>$org_list,
			'count_busy'=>$count_busy,
			//'setup'=>$setup,
			
		
		));
        $this->template->content = $content;
		
	}
	

	
	public function action_map()
	{
		
				$content = View::factory('map');
	}
	
	public function action_cardList() // получить список парковочных мест
	{
		$_SESSION['menu_active']='rubic';
		$id_parking=1;
		//echo Debug::vars('20', $_SESSION);
		//$card_list=Model::Factory('rubic')->get_list_parking_card();//список парковочный карт
		$card_list=Model::Factory('rubic')->get_list_parking_place($id_parking);//список парковочный мест
		$content = View::factory('rubic/placeList', array(
			'card_list'=>$card_list,
				
		));
        $this->template->content = $content;
		
	}
	
	
	
	
	
	
	public function action_placeList()// перечень парковочных мест.
	{
		$_SESSION['menu_active']='rubic';
		//echo Debug::vars('20', $_SESSION);
		$card_list=Model::Factory('rubic')->get_list_parking_place();//список парковочный мест
		$content = View::factory('rubic/placeList', array(
			'card_list'=>$card_list,
				
		));
        $this->template->content = $content;
		
	}
	
	
	
	public function action_event()// просмотр событий
	{
				
		$_SESSION['menu_active']='rubic';
		//echo Debug::vars('20', Session::instance());// exit;
		$eventTable=Validation::factory(Session::instance()->as_array());
		/* $eventTable->rule('eventTable', 'not_empty') //timeFrom
					->rule('eventTable', 'not_empty')//timeTo
					; */
		$eventTable->rule('timeFrom', 'not_empty')
						->rule('timeFrom', 'date')
						->rule('timeTo', 'not_empty')
						->rule('timeTo', 'date');
		
		if($eventTable->check())
		{
			//$event_list=Arr::get($eventTable, 'eventTable');
			$event_list=Model::Factory('rubic')->getEventsFromTo(array('timeFrom'=>Arr::get($eventTable, 'timeFrom'), 'timeTo'=>Arr::get($eventTable, 'timeTo') ));
		} else {
				
		$event_list=Model::Factory('rubic')->getEventsFromTo(array(
			'timeFrom'=>date("d.m.Y H:m:s",strtotime("-1 days")),
			'timeTo'=>date("d.m.Y H:m:s",strtotime("now"))
			));//просмотр журнала событий за последние сутки
		}
		$content = View::factory('rubic/event', array(
			'event_list'=>$event_list,
				
		));
        $this->template->content = $content;
		
	}

	public function action_rp_control()
	{
		//echo Debug::vars('269', $_GET, $_POST); exit;
		//echo Debug::vars('68', $_SESSION);
		$post=Validation::factory($this->request->post());
					$post->rule('todo', 'not_empty')
							//->rule('todo', 'digit')
							;
					if($post->check())
					{
						$todo = Arr::get($post, 'todo');
					} else 
					{
						$todo='no';
					}
		switch ($todo){
			case 'add_rp'://добавление нового жилого комлпекса add_rp_name
				$rp_name=Validation::factory($this->request->post());
				$rp_name->rule('add_rp_name', 'not_empty')
							;
					if($rp_name->check())
					{
						Model::factory('residentplace')->add_rp(Arr::get($rp_name, 'add_rp_name'));// далее добавляем новый ЖК.
						Session::instance()->set('ok_mess', array('ok_mess' => __(Arr::get($rp_name, 'add_rp_name').' добавлено успешно')));
						$this->redirect('rubic');
					} else 
					{
						Session::instance()->set('e_mess', $rp_name->errors('Valid_mess'));
						$this->redirect('rubic');
					}
				
			break;
			
			case 'del_rp'://удаление Жилого комплекса из списка
				//echo Debug::vars('301', $_GET, $_POST); exit;
				$rp_id=Validation::factory($this->request->post());
				$rp_id->rule('id_rp', 'not_empty')
							->rule('id_rp', 'digit')
							;
					if($rp_id->check())
					{
						Model::factory('residentplace')->del_rp(Arr::get($rp_id, 'id_rp'));// удаление жилого комплекса из списка.
						Session::instance()->set('ok_mess', array('ok_mess' => __(Arr::get($rp_id, 'del_rp_name').' удален успешно')));
						$this->redirect('rubic');
					} else {
						Session::instance()->set('e_mess', $rp_name->errors('Valid_mess'));
						$this->redirect('rubic');
					}
			break;
			
			case 'edit_rp'://просмотр и редакция парковки
			//echo Debug::vars('235', $_GET, $_POST); exit;
				$post=Validation::factory($this->request->post());
				$post->rule('id_rp', 'not_empty')
						->rule('id_rp', 'digit')
						;
				if($post->check())
				{
					$this->redirect('residentplace/edit/'.Arr::get($post, 'id_rp'));
				} else 
				{
					Session::instance()->set('e_mess', $post->errors('Valid_mess'));
					$this->redirect('rubic');
				}
		
			break;
			
			case 'update_rp'://обновление данных о жилом комплексе
			//echo Debug::vars('252', $_GET, $_POST); exit;
				$post=Validation::factory($this->request->post());
				$post->rule('id_rp', 'not_empty')
						->rule('id_rp', 'digit')
						->rule('name', 'not_empty')
						
						;
				if($post->check())
				{
					Model::factory('residentplace')->update_rp($post);
					Session::instance()->set('ok_mess', array('ok_mess' => __(Arr::get($post, 'name').' обновлен успешно')));
					$this->redirect('rubic');
				} else 
				{
					Session::instance()->set('e_mess', $post->errors('Valid_mess'));
					$this->redirect('rubic');
				}
		
			break;
			
			
			
			default:
				//echo Debug::vars('755', $_GET, $_POST); exit;
				$this->redirect('/');
			break;
		}
		$content='';
        $this->template->content = $content;
		
	}

		
	public function action_edit()// редактирование информации о жилом комплекса
	{
		
		$param=array('id'=>$this->request->param('id'));
		//echo Debug::vars('263', $_GET, $_POST, $this->request->param('id'), $param); //exit;
		$query=Validation::factory($param);
		$query->rule('id', 'not_empty')
				->rule('id', 'digit')
				;
		if($query->check())
		{

			$rp_info=Model::factory('residentplace')->getResidentPlaceInfo(Arr::get($query, 'id')); // вывод информации о жилом комплексе для редактирования
			$content = View::factory('rubic/edit_residentplace', array(
					'rp_info'=>$rp_info,
					));
			$this->template->content = $content;
		} else 
		{

			Session::instance()->set('e_mess', $query->errors('Valid_mess'));
			$this->redirect('rubic');
		}
	
	}
} 
