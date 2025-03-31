<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Gate extends Controller_Template { // класс описывает въезды и вызды (ворота) для парковочных площадок
	
	
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
	
	
	public function action_index()// просмотр ворот и их настроек
	{
		$_SESSION['menu_active']='gate';
		$query=Validation::factory($this->request->query());
					$query->rule('id_parking', 'not_empty')
							->rule('id_parking', 'digit')
							;
					if($query->check())
					{
						$id_parking=Arr::get($query, 'id_parking'); // имеется номер родительской парковки
						
						
					} else 
					{
						$id_parking=0; // номер родительской паровки не указан
					}
					
		
		//echo Debug::vars('38', $_GET, $_POST, $id_parking); //exit;
		$gate_list=Model::Factory('gates')->get_list_gate($id_parking);//список ворот
		$checkplaceenable=Model::Factory('gates')->checkplaceenable();//статус счетчиков
		$tabloMessages=Model::Factory('gates')->tabloMessages();//информация по строкам табло
		$tabloMessageIdle=Model::Factory('gates')->tabloMessageIdle();//информация по строкам табло на время ожидания
		
		
		$data=Validation::factory($gate_list);
		$data->rule('result', 'digit')
			->rule('result', 'in_array', array(':value', array(0)))
			->rule('res', 'not_empty')
			;
		if($data->check())
		{			
			Session::instance()->set('ok_mess', array('ok_mess' => 'Данные обновлены успешно'));
			//echo Debug::vars('56', $data, Arr::get($data, 'res')); exit;
			$content = View::factory('rubic/gatelist', array(
			'gate_list'=>Arr::get($data, 'res'),
			'id_parking'=>$id_parking,
			'checkplaceenable'=>$checkplaceenable,
			'tabloMessages'=>$tabloMessages,
			'tabloMessageIdle'=>$tabloMessageIdle,
			));
		} else {
			Session::instance()->set('e_mess', array('Ошибка '.Arr::get($data, 'result').' не могу выдать данные по КПП.'));
			$content = View::factory('rubic/gatelist', array(
			'gate_list'=>null,
			'id_parking'=>$id_parking,
			));
		}
		
        $this->template->content = $content;
	}
	
	public function action_control()
	{
		//echo Debug::vars('30', $_GET, $_POST); exit;
		//echo Debug::vars('68', $_SESSION);
		
		$todo = $this->request->post('todo');
		$data=Validation::factory($this->request->post());
		switch ($todo){
			case 'add_gate'://добавление новой парковки
				//$data=Validation::factory($this->request->post());
				$data->rule('new_gate_name', 'not_empty')
							;
					if($data->check())
					{
						Model::factory('gates')->add_gate($data);// далее добавляем новые ворота.
						Session::instance()->set('ok_mess', array('ok_mess' => __(Arr::get($data, 'new_gate_name').' добавлено успешно')));
						$this->redirect('gate');
					} else 
					{
						Session::instance()->set('e_mess', $rp_name->errors('Valid_mess'));
						$this->redirect('rubic');
					}
			break;
			
			case 'del_gate'://удаление ворот
			
				$data->rule('id', 'not_empty')
						->rule('id', 'digit')
							;
					if($data->check())
					{
						Model::factory('gates')->del_gate($data);
						Session::instance()->set('ok_mess', array('ok_mess' => __(Arr::get($data, 'new_gate_name').' удалены успешно')));
						$this->redirect('gate');
					} else 
					{
						Session::instance()->set('e_mess', $data->errors('Valid_mess'));
						$this->redirect('gate');
					}
				
				$this->redirect('gate');
			break;
			
			case 'update'://обновление ворот
				
				//echo Debug::vars('101', $_POST); exit;
				//$data=Validation::factory($this->request->post());
				$data->rule('name', 'not_empty')
						->rule('id', 'not_empty')
						->rule('id', 'digit')
						;
					if($data->check())
					{
						Model::factory('gates')->update_gate($data);
						Session::instance()->set('ok_mess', array('ok_mess' => __(Arr::get($data, 'new_gate_name').' удалены успешно')));
						$this->redirect('gate');
					} else 
					{
						Session::instance()->set('e_mess', $data->errors('Valid_mess'));
						$this->redirect('gate');
					}
				
				$this->redirect('gate');
			break;
			
			case 'edit_gate'://просмотр и редакция ворот
				//echo Debug::vars('122', $_POST); exit;
				//$data=Validation::factory($this->request->post());
				$data->rule('id', 'not_empty')
						->rule('id', 'digit')
						;
				if($data->check())
				{
					$this->redirect('gate/edit/'.Arr::get($data, 'id'));
				} else 
				{
					Session::instance()->set('e_mess', $data->errors('Valid_mess'));
						$this->redirect('gate');
				}
		
			break;
			
			case 'check_count_off'://выключить счетчики свободных мест
				//echo Debug::vars('122', $_POST); exit;
		
					Model::factory('gates')->check_count_off();
					$this->redirect('gate');
			break;
			
			case 'check_count_on'://включить счетчики свободных мест
				//echo Debug::vars('122', $_POST); exit;
		
					Model::factory('gates')->check_count_on();
					$this->redirect('gate');
			break;
			
			case 'set_tablo_text':		//сохранить параметры табло
				//echo Debug::vars('172', $_POST); exit;
				//$data=Validation::factory($this->request->post());
				$data->rule('new_tablo_text', 'not_empty')
						->rule('new_tablo_text', 'is_array')
						;
				if($data->check())
				{
					//echo Debug::vars('179 OK', $data); //exit;
					Model::factory('gates')->update_gate_messages(Arr::get($data, 'new_tablo_text'), Arr::get($data, 'dx'), Arr::get($data, 'dy'), Arr::get($data, 'messColor'),Arr::get($data, 'messScroll'));
					$this->redirect('gate');
				} else 
				{
					echo Debug::vars('183 err', $data); exit;
					Session::instance()->set('e_mess', $data->errors('Valid_mess'));
						$this->redirect('gate');
				}
		
			break;
			
			default:
				echo Debug::vars('95 controller gate', $_GET, $_POST); exit;
			break;
		}
		$content='';
        $this->template->content = $content;
		
	}
	
	public function action_edit()//редактировать и просматривать  ворота
	{
		$_SESSION['menu_active']='kp_park_menu';
		
		$id_gate = $this->request->param('id');
		$info_gate=Model::Factory('gates')->get_info_gate($id_gate); //получить общую информацию о воротах
		
		//echo Debug::vars('139', $info_gate, $_POST); exit;
		
		$content = View::factory('rubic/edit_gate', array(
			'info_gate'=>$info_gate,
			
		));
        $this->template->content = $content;
		
	}
	
	
	
	
} 
