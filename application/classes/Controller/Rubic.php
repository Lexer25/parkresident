<?php defined('SYSPATH') or die('No direct script access.');
/**
* @package    ParkResident/Application
 * @category   Base
 * @author     Artonit
 * @copyright  (c) 2025 Artonit Team
 * @license    http://artonit/ru 
 
 */

class Controller_Rubic extends Controller_Template {
	
	
	public $template = 'template';
	public function before()
	{
			//echo Debug::vars('9', $_POST); exit;
			parent::before();
			$session = Session::instance();
			
			$_SESSION['checkplaceenable']=Model::Factory('gates')->checkplaceenable();//статус счетчиков
			
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
							->rule('id_parking', 'digit')
							;
					if($query->check())
					{
						$rp=Arr::get($query, 'rp'); // имеется номер родительской парковки
						
						
					} else 
					{
						$rp=0; // номер родительской паровки не указан
					}
					
		
		//$rubic_list=Model::Factory('ResidentPlace')->get_list_rp($rp);//список жилый комлексов
		$rubic_list=Model::Factory('ResidentPlace')->get_list($rp);//список жилый комлексов
		
		$content = View::factory('ResidentPlace', array(
			'rubic_list'=>$rubic_list,
			
		));
        $this->template->content = $content;
		
	}
	
	
	
	
	public function action_map()
	{
		
				$content = View::factory('map');
	}
	
	public function action_cardList11() // получить список парковочных мест
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
		$_SESSION['menu_active']='placeList';
		//echo Debug::vars('20', $_SESSION);
		$place_list=Model::Factory('rubic')->get_list_parking_place();//список парковочный мест
		//$list_parking=Model::Factory('parking')->get_list_parking();//список паркингов. Нужен для показа расположения машиноместа
		//echo Debug::vars('105', $place_list); exit;
		$content = View::factory('rubic/placeList', array(
			'place_list'=>$place_list,
			//'list_parking'=>$list_parking,
				
		));
        $this->template->content = $content;
		
	}
	
	
	
	
	public function action_event()// просмотр событий
	{
				
		$_SESSION['menu_active']='event';
		//echo Debug::vars('20', Session::instance());// exit;
		$eventTable=Validation::factory(Session::instance()->as_array());
	
		$eventTable->rule('timeFrom', 'not_empty')
						->rule('timeFrom', 'date')
						->rule('timeTo', 'not_empty')
						->rule('timeTo', 'date');
		
		if($eventTable->check())
		{
			$event_list=Model::Factory('rubic')->getEventsFromTo(array(
				'timeFrom'=>Arr::get($eventTable, 'timeFrom'),
				'timeTo'=>Arr::get($eventTable, 'timeTo'),
				'id_event_filter'=> Arr::get($eventTable, 'id_event_filter'))
				
				);
				
		} else {
				
		$event_list=Model::Factory('rubic')->getEventsFromTo(array(
			'timeFrom'=>date("d.m.Y H:m:s",strtotime("-1 days")),
			'timeTo'=>date("d.m.Y H:m:s",strtotime("now"))
			));//просмотр журнала событий за последние сутки
		}
		
		$list_garage=Model::Factory('garage')->get_list_garage();
		//echo Debug::vars('145', $event_list );exit;
		$events_name_list=Model::factory('event')->get_events_name_list();
		$content = View::factory('rubic/event', array(
			'event_list'=>$event_list,
			'events_name_list'=>$events_name_list,
			'list_garage'=>$list_garage,
				
		));
        $this->template->content = $content;
		
	}
	
	

	public function action_add_rubic()//Добавить новую парковку
	{
		echo Debug::vars('34', $_POST, $_GET); exit;
		$_SESSION['menu_active']='kp_park_menu';
		$rubic_list=Model::Factory('rubic')->get_list();
		$content = View::factory('rubic/rubic', array(
			'rubic_list'=>$rubic_list,
		
		));
        $this->template->content = $content;
		
	}
	
	public function action_edit_rubic()//редактировать и просматривать  парковку
	{
		$_SESSION['menu_active']='kp_park_menu';
		//echo Debug::vars('43', $_GET, $_POST, $this->request->param('id')); exit;
		$id_rubic = $this->request->param('id');
		$info_parking=Model::Factory('rubic')->get_info_parking($id_rubic); //получить общую информацию о парковке
		$list_key_into_parking=Model::Factory('rubic')->list_plate_into_parking($id_rubic); //получить список ГРЗ, находящихся на парковке
		$apb_device_list=Model::Factory('rubic')->get_list_dev($id_rubic); //получить лист точек прохода, уже входящих в периметр
		$door_list=Model::Factory('rubic')->door_list($id_rubic); //получить лист точек прохода, которые планирую включить во въезд-выезд парковки
		
		
		//echo Debug::vars('45', $door_list, $id_rubic); exit;
		
		$content = View::factory('rubic/edit_rubic', array(
			'rubic_getinfo'=>$info_parking,
			'list_key_into_parking' => $list_key_into_parking,
			'apb_device_list'=>$apb_device_list,
			'door_list'=>$door_list,
		));
        $this->template->content = $content;
		
	}
	

	
	
	
	public function action_edit_place()//редактировать и просматривать свойства машиноместа
	{
		$_SESSION['menu_active']='kp_mm_menu';
		//echo Debug::vars('69', $_GET, $_POST, $id_rubic = $this->request->param('id')); exit;
		$id_place = $this->request->param('id');
		$info_place=Model::Factory('rubic')->get_info_place($id_place); //получить общую информацию о машиноместе
		$list_parking=Model::Factory('rubic')->get_list_parking_select(14); //получить список парковок
		$list_garage=Model::Factory('rubic')->get_list_garageName(); //получить список имен гаражей
		
		//echo Debug::vars('45', $id_place, $info_place, $list_parking, $list_garage); exit;
		
		$content = View::factory('rubic/edit_place', array(
			'info_place'=>$info_place,
			'list_parking'=>$list_parking,
			'list_garage'=>$list_garage,
			'id_place'=>$id_place,
			
		));
        $this->template->content = $content;
		
	}
	
	
	
	
	

	public function action_rubic_control()
	{
		//echo Debug::vars('30', $_GET, $_POST); exit;
		//echo Debug::vars('68', $_SESSION);
		
		$todo = $this->request->post('todo');
		switch ($todo){
			case 'add_rubic'://добавление новой парковки
				$add_rubic = $this->request->post('add_rubic');// далее добавляем новую парковку.
				$add_org = $this->request->post('id_org');// для указанной организации.
				Model::factory('rubic')->add_rubic($add_rubic, $add_org);
				$this->redirect('rubic');
			break;
			
			case 'del_rubic'://добавление нового периметра
				$del_rubic = $this->request->post('id_rubic');// далее добавляем новый периметр.
				Model::factory('rubic')->del_rubic($del_rubic);
				$this->redirect('rubic');
			break;
			
			case 'edit_rubic'://просмотр и редакция парковки
				$post=Validation::factory($this->request->post());
				$post->rule('id_rubic', 'not_empty')
						->rule('id_rubic', 'digit')
						;
				if($post->check())
				{
					$this->redirect('rubic/edit_rubic/'.Arr::get($post, 'id_rubic'));
				} else 
				{
					Session::instance()->set('e_mess', $post->errors('Valid_mess'));
					$this->redirect('rubic');
				}
		
			break;
			
			case 'add_new_place'://добавление нового машиноместа
			//echo Debug::vars('254');exit;
				$post=Validation::factory($this->request->post());
				$post->rule('new_place_number', 'not_empty')
						->rule('new_place_number', 'digit')
						->rule('new_place_number', 'Model_place::unique_place')
						->rule('new_place_number', 'Model_place::maxcount_place')
						;
				if($post->check())
				{
					
					Model::factory('place')->add_new_place(Arr::get($post, 'new_place_number'));
					Session::instance()->set('ok_mess', array('ok_mess' => __('Машиноместо new_place_number добавлено успешно', array('new_place_number'=>(Arr::get($post, 'new_place_number'))))));
					$this->redirect('rubic/placeList');
				} else 
				{
					
					Session::instance()->set('e_mess', $post->errors('Valid_mess'));
					$this->redirect('rubic');
				}
		
			break;
			
			case 'change_door'://поменять вход и выход точки прохода
				//echo Debug::vars('83', $_GET, $_POST, $todo, $this->request->post('change_door')); exit;
				$id_dev=$this->request->post('id_dev_for_change');
				$id_rubic=$this->request->post('id_rubic');
				$device_info_rubic =Model::factory('rubic')->get_dev_info($id_dev, $id_rubic);// получить информацию по заданной точке прохода в заданном rubic
				if(Arr::get($device_info_rubic, 'IS_ENTER') == 0)
				{
					Model::factory('rubic')->del_door_apd($id_dev, $id_rubic);// удаляю точку прохода
					Model::factory('rubic')->add_door_apd($id_dev, 1, $id_rubic);// добавляю точку с признаком Enter
				}
				
				if(Arr::get($device_info_rubic, 'IS_ENTER') == 1)
				{
					Model::factory('rubic')->del_door_apd($id_dev, $id_rubic);// удаляю точку прохода
					Model::factory('rubic')->add_door_apd($id_dev, 0, $id_rubic);// добавляю точку с признаком Enter
				}
				
				$this->redirect('rubic/edit_rubic/'.$id_rubic);
		
			break;
			
			case 'delete_door'://удалить точку прохода из парковки
				//echo Debug::vars('279', $_GET, $_POST, $todo, $this->request->post('change_door')); exit;
				$post=Validation::factory($this->request->post());
				$post->rule('id_parking', 'digit')
						->rule('id_parking', 'not_empty')
						->rule('id_dev', 'digit')
						->rule('id_dev', 'not_empty')
						;
				if($post->check())
				{	
				$id_dev=$this->request->post('id_dev_for_change');
				$id_rubic=$this->request->post('id_rubic');
				Model::factory('rubic')->del_door_parking($post);// удаляю точку прохода из парковки
				$this->redirect('rubic/edit_rubic/'.Arr::get($post, 'id_parking', 0));
				} {
					echo Debug::vars('293', $post->errors('validation')); exit;	
				}
				$this->redirect('rubic/edit_rubic/'.$id_rubic);
		
			break;
			
			case 'add_door_enter'://добавить точку прохода в enter указанной парковки
				//echo Debug::vars('92', $_GET, $_POST, $todo, $this->request->post('id_dev')); exit;
				$post=Validation::factory($this->request->post());
				$post->rule('name', 'not_empty')
						->rule('id_parking', 'digit')
						->rule('id_parking', 'not_empty')
						->rule('id_dev', 'digit')
						->rule('id_dev', 'not_empty')
						->rule('id_dev', 'digit')
						->rule('id_dev', 'not_empty')
						;
				if($post->check())
				{		
				$id_dev=$this->request->post('id_dev');
				$id_parking=$this->request->post('id_parking');
				Model::factory('rubic')->add_door_parking($post, 1);// добавляю точку с признаком Enter в указанную парковку
				$this->redirect('rubic/edit_rubic/'.Arr::get($post, 'id_parking', 0));
				}
				else {
					echo Debug::vars('318', $post->errors('validation')); exit;	
				}
				$this->redirect('rubic/edit_rubic/'.$id_rubic);
		
			break;
			
			
			case 'add_door_exit'://добавить точку прохода в exit указанной парковки
				//echo Debug::vars('92', $_GET, $_POST, $todo, $this->request->post('id_dev')); exit;
				$post=Validation::factory($this->request->post());
				$post->rule('name', 'not_empty')
						->rule('id_parking', 'digit')
						->rule('id_parking', 'not_empty')
						->rule('id_dev', 'digit')
						->rule('id_dev', 'not_empty')
						->rule('id_dev', 'digit')
						->rule('id_dev', 'not_empty')
						;
				if($post->check())
				{		
				$id_dev=$this->request->post('id_dev');
				$id_parking=$this->request->post('id_parking');
				Model::factory('rubic')->add_door_parking($post, 0);// добавляю точку с признаком Enter в указанную парковку
				$this->redirect('rubic/edit_rubic/'.Arr::get($post, 'id_parking', 0));
				}
				else {
					echo Debug::vars('344', $post->errors('validation')); exit;	
				}
				$this->redirect('rubic/edit_rubic/'.$id_rubic);
		
			break;
			
			
			case 'change_config'://изменение конфигурации rubic
				//echo Debug::vars('135', $_GET, $_POST, $todo); exit;
				$post=Validation::factory($this->request->post());
				$post->rule('name', 'not_empty')
						//->rule('name', 'regex', array(':value', '/^[а-яА-Яa-zA-Z0-9\s_]+$/iD' ))
						//->rule('name', 'regex', array(':value', '/^[а-я][А-Я][0-9][.][ ]$u/' ))
						->rule('maxcount', 'digit')
						->rule('maxcount', 'not_empty')
						->rule('position', 'digit')
						->rule('position', 'not_empty')
						;
				if($post->check())
				{		
				$name=Arr::get($post, 'name');
				$id_parking=Arr::get($post, 'id_parking');
				$maxcount=Arr::get($post, 'maxcount');
				$position=Arr::get($post, 'position');
				
				//echo Debug::vars('140', $id_parking, $name, $id_parking, $maxcount); exit;
				Model::factory('rubic')->change_config($id_parking, $name, $id_parking, $maxcount, $position);// обновление информации о парковке
				} else {
					
				echo Debug::vars('373', $post->errors('validation')); exit;	
					
				}
				
				$this->redirect('rubic');
		
			break;
			
			
		case 'clear_parking_inside'://очистить таблицу parking_inside
				//echo Debug::vars('195', $_GET, $_POST, $todo); exit;
				
				$id_parking=$this->request->post('id_parking');
				Model::factory('rubic')->clear_parking_inside($id_parking);// очистка указанного периметра
				$this->redirect('rubic');
		
			break;
			
		case 'apd_changer_pass_delay'://Записать новые значения задержек
				
				$post=Validation::factory($this->request->post());
				
				$post->rule('delay_pass', 'not_empty')
						->rule('delay_pass', 'is_array')
						->rule('id_rubic', 'digit')
						->rule('id_rubic', 'not_empty')
						;
						//echo Debug::vars('161',$_POST, $post, $post->check() ); exit;
				if($post->check())
				{
					$id_dev=Arr::get($post, 'delay_pass');
					$id_rubic=Arr::get($post, 'id_rubic');
					Model::factory('rubic')->update_delay_pass($id_rubic, $id_dev);// очистка указанного периметра
				} else {
					echo Debug::vars('497', $post->errors('validation')); exit;
					$res=$post->errors('validation');
					$res='post->errors(validation)';
				}
				
				$this->redirect('rubic/edit_rubic/'.Arr::get($post, 'id_rubic'));
		
			break;
			
			case 'del_card_rubic'://Удалить карту
				$id_card=$this->request->post('id_card');
				Model::factory('rubic')->del_card_rubic($id_card);// очистка указанного периметра
				Session::instance()->set('ok_mess', array('ok_mess' => __('Карта удалена успешно')));
				$this->redirect('rubic/placeList');
		
			break;
			
			
			case 'edit_place_rubic'://редактировать свойства парковочного места
			//echo Debug::vars('212', $_GET, $_POST); exit;
				$id_place=$this->request->post('id_place');
				$this->redirect('rubic/edit_place/'.$id_place);
		
			break;
			
			
			case 'save_place'://сохранить изменения машиноместа
				//echo Debug::vars('243', $_GET, $_POST); exit;
				//->rule(5, 'regex', array(':value', '/^[A-F\d]{10}+$/')) // https://regex101.com/
							
				//			^[а-яА-ЯёЁa-zA-Z0-9]+$ 
							
				$post=Validation::factory($this->request->post());
					$post->rule('id_place', 'not_empty')
							->rule('id_place', 'not_empty')
							->rule('id_parking', 'digit')
							->rule('id_parking', 'not_empty')
							->rule('status', 'digit')
							->rule('status', 'not_empty')
							//->rule('note', 'alpha_numeric')
							->rule('note', 'max_length', array(':value',250))
							//->rule('name', 'not_empty')
							->rule('name', 'max_length', array(':value',100))
							//->rule('name', 'regex', array(':value', '/^[а-яА-ЯёЁa-zA-Z0-9 ]+$/'))
							//->rule('name', 'regex', array(':value', '/^\pL/'))
							->rule('name', 'regex', array(':value', '/^[\p{L}\p{N}]/'))
							//->rule('description', 'not_empty')
							//->rule('description', 'alpha_numeric')
							->rule('description', 'max_length', array(':value',250))
							;
					if($post->check())
					{
						Model::factory('rubic')->save_place($post);// сохранить изменения машиноместа
						$this->redirect('rubic/placeList');
						
					} else 
					{
						Session::instance()->set('e_mess', $post->errors('Valid_mess'));
						$this->redirect('rubic/placeList');
					}
			
			break;
			
			
			case 'change_setup'://сохранить изменения настроек
				//echo Debug::vars('274', $_GET, $_POST); exit;
					
				$post=Validation::factory($this->request->post());
					$post->rule('autoreg', 'not_empty')
							->rule('autoreg', 'digit')
							->rule('enter_mode', 'not_empty')
							->rule('enter_mode', 'digit')
							->rule('exit_mode', 'not_empty')
							->rule('exit_mode', 'digit')
							;
					if($post->check())
					{
						Model::factory('rubic')->save_setup($post);// сохранить изменения карты
						$this->redirect('rubic');
						
					} else 
					{
						Session::instance()->set('e_mess', $post->errors('Valid_mess'));
						$this->redirect('rubic');
					}
			
			case 'getEvent'://получить журнал событий
				//echo Debug::vars('405', $_GET, $_POST); exit;
							
				$post=Validation::factory($this->request->post());
					$post->rule('timeFrom', 'not_empty')
							->rule('timeFrom', 'date')
							->rule('timeTo', 'not_empty')
							->rule('timeTo', 'date')
							;
					if($post->check())
					{
						Cookie::set('id_event_filter', serialize(Arr::get($post, 'id_event_filter')));
						//Cookie::set('id_event_filter', array('123', 678));
						//echo Debug::vars('415', 'valid OK', Arr::get($post, 'id_event_filter')); exit;
						//Model::factory('rubic')->getEventsFromTo($post);// получить журнал событий
						//Session::instance()->set('eventTable', Model::factory('rubic')->getEventsFromTo($post));// получить журнал событий 2022.08.19
						Session::instance()->set('timeFrom', Arr::get($post, 'timeFrom'));// Запись даты в переменные сессии
						Session::instance()->set('timeTo', Arr::get($post, 'timeTo'));// Запись даты в переменные сессии
						Session::instance()->set('id_event_filter', Arr::get($post, 'id_event_filter'));// Запись даты в переменные сессии
						$this->redirect('rubic/event');
						
					} else 
					{
						//echo Debug::vars('415', 'valid ERR'); exit;
						Session::instance()->set('e_mess', $post->errors('Valid_mess'));
						$this->redirect('rubic/event');
					}
			
			break;
			

			
			case 'edit_tablo'://редактирование параметров табло
				//echo Debug::vars('461', $_GET, $_POST); exit;
							
							
				$regformat="/^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)(\:([0-9]{1,4}|[1-5][0-9]{4}|6[0-4][0-9]{3}|65[0-4][0-9]{2}|655[0-2][0-9]|6553[0-5]))?$/";	
				$post=Validation::factory($this->request->post());
					
					$post->rule('tablo', 'not_empty')
							//->rule('tablo', 'regex', array(':value', '/^[0-9:]++$/iD'))
							->rule('tablo', 'regex', array(':value', $regformat))
							;
					if($post->check())
					{
						//echo Debug::vars('415', 'valid OK'); exit;
						//Model::factory('rubic')->getEventsFromTo($post);// сохранить настройки табло
						Session::instance()->set('ok_mess', array('ok_mess' => __('Настройки табло изменены успешно')));
						$this->redirect('rubic/tablo');
						
					} else 
					{
						//echo Debug::vars('415', 'valid ERR'); exit;
						Session::instance()->set('e_mess', $post->errors('Valid_mess'));
						$this->redirect('rubic/tablo');
					}
			
			break;
			
			case 'eventExport'://сохранение таблицы событий в файл
				//echo Debug::vars('488', $_GET, $_POST); exit;
							
					$post=Validation::factory($this->request->post());
					
					$post->rule('eventTable', 'not_empty')
						->rule('timeFrom', 'not_empty')
						->rule('timeTo', 'not_empty')
							;
					if($post->check())
					{
						//echo Debug::vars('415', 'valid OK'); exit;
						Session::instance()->set('eventTable', Arr::get($post, 'eventTable'));
						Session::instance()->set('filename', 'EventList_'.Arr::get($post, 'timeFrom').'-'.Arr::get($post, 'timeTo').'.csv');
						Session::instance()->set('ok_mess', array('ok_mess' => __('Журнал событий сохранен успешно.')));
						
						$this->redirect('download');
						
						$this->redirect('rubic/event');
						
					} else 
					{
						//echo Debug::vars('415', 'valid ERR'); exit;
						Session::instance()->set('e_mess', $post->errors('Valid_mess'));
						$this->redirect('rubic/event');
					}
			
			break;
			
			
			case 'add_garage'://добавление нового гаража
				//echo Debug::vars('671', $_GET, $_POST); exit;
							
					$post=Validation::factory($this->request->post());
					
					$post->rule('name', 'not_empty')
							;
					if($post->check())
					{
						//echo Debug::vars('415', 'valid OK'); exit;
						Model::factory('garage')->add_garage($post);
						
						$this->redirect('rubic/garage');
						
					} else 
					{
						//echo Debug::vars('415', 'valid ERR'); exit;
						Session::instance()->set('e_mess', $post->errors('Valid_mess'));
						$this->redirect('rubic/event');
					}
			
			break;

			
			
			
			
			default:
				//echo Debug::vars('56', $_GET, $_POST); exit;
			break;
		}
		$content='';
        $this->template->content = $content;
		
	}

	public function checkBeforInsert()
	{


	}		
	public function action_card_history()// история карты
	{
	//$id_card = array('data'=>$this->request->param('id'));	
	
	$data=Validation::factory(array('id_card'=>$this->request->param('id')));
	$data->rule('id_card', 'not_empty')
				->rule('id_card', 'digit')
				;
	if($data->check())
	{
		//echo Debug::vars('321', 'Validation OK'); exit;
		$list_history=Model::factory('rubic')->getListHistory(Arr::get($data, 'id_card'));
		$card_info=Model::factory('rubic')->get_info_card(Arr::get($data, 'id_card'));
		//$this->redirect('rubic/');
	} else 
	{
		//echo Debug::vars('325', 'Validation ERR', $data->errors('rubic') ); exit;
		Session::instance()->set('e_mess', $data->errors('rubic'));
		$this->redirect('rubic');
	}
	
	$content = View::factory('rubic/list_history', array(
			'list_history'=>$list_history,
			'card_info'=>$card_info,

		));
        $this->template->content = $content;
	
	}
}
