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
		echo Debug::vars('28', $_POST);exit;
		$_SESSION['menu_active']='gate';
		$query=Validation::factory($this->request->query());
					$query->rule('id_parking', 'not_empty')
							->rule('id_parking', 'digit')
							;
					if($query->check())
					{
						$id=Arr::get($query, 'id_parking'); // имеется номер ворот
						
						
					} else 
					{
						$id=0; // вывести список ворота
					}
					
		
		echo Debug::vars('38', $_GET, $_POST, $id_parking); //exit;
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
			$content = View::factory('gate/gatelist', array(
			'gate_list'=>Arr::get($data, 'res'),
			'id_parking'=>$id_parking,
			'checkplaceenable'=>$checkplaceenable,
			'tabloMessages'=>$tabloMessages,
			'tabloMessageIdle'=>$tabloMessageIdle,
			));
		} else {
			
			Session::instance()->set('e_mess', array('Ошибка '.Arr::get($data, 'result').' не могу выдать данные по КПП.'));
			$content = View::factory('gate/gatelist', array(
			//'gate_list'=>null,
			'gate_list'=>Arr::get($data, 'res'),
			'id_parking'=>$id_parking,
			'id_parking'=>$id_parking,
			'checkplaceenable'=>$checkplaceenable,
			'tabloMessages'=>$tabloMessages,
			'tabloMessageIdle'=>$tabloMessageIdle,
			));
		}
		
        $this->template->content = $content;
	}
	
	
	public function action_list()//
	{
		$id = $this->request->param('id');
		//$_SESSION['menu_active']='rubic';
		$query=Validation::factory($this->request->param());
					$query->rule('id', 'not_empty')
							->rule('id', 'digit')
							;
					if($query->check())
					{

						$id=Arr::get($query, 'id');
						
					} else 
					{
						//echo Debug::vars('75');exit;
						//$id_parking=0; // номер родительской паровки не указан. надо показывать все ЖК.
						
						$id=Model::factory('gates')->getAll();//получить список всех ворот.
						
					}
		//echo Debug::vars('106', $id);exit;
		
		$content = View::factory('gate/list', array(
			
			'gate_list'=>$id,
			
		
		));
        $this->template->content = $content;
	}
	
	
	public function action_control()
	{
		//echo Debug::vars('30', $_GET, $_POST); exit;
		//echo Debug::vars('68', $_SESSION);
		
		$todo = $this->request->post('todo');
		$_data=Validation::factory($this->request->post());
		switch ($todo){
			case 'add'://добавление новой парковки
				//$data=Validation::factory($this->request->post());
				$_data->rule('name', 'not_empty')
							;
					//==
					
					if($_data->check())
					{
						
						$entity = new Gate();
						$entity->name=Arr::get($_data, 'name');
						
						//echo Debug::vars('143', $entity);exit;
						if ($entity->add())
						{
							Session::instance()->set('ok_mess', array('ok_mess' => __(Arr::get($_data, 'add_rp_name').' добавлено успешно')));
							
						} else {
							Session::instance()->set('e_mess', array('ok_mess' => __(Arr::get($_data, 'add_rp_name').' ошибка при добавлении')));
							
						}
						
						
					} else 
					{
						//echo Debug::vars('137');exit;
						Session::instance()->set('e_mess', $_data->errors('Valid_mess'));
						
					}
				$this->redirect('gate/list');
					
					//..==
			break;
			
			case 'del'://удаление ворот
			//echo Debug::vars('162', $_POST);exit;
				$_data->rule('id', 'not_empty')
						->rule('id', 'digit')
							;
					$_data=Validation::factory($this->request->post());
				$_data->rule('id', 'not_empty')
							->rule('id', 'digit')
							;
					if($_data->check())
					{
						$entity = new Gate();
						
						$entity->id=Arr::get($_data, 'id');
						if($entity->del())
						{
							Session::instance()->set('ok_mess', array('ok_mess' => __(Arr::get($_data, 'add_rp_name').' удален успешно')));
							
						} else {
							Session::instance()->set('e_mess', array('ok_mess' => __(Arr::get($_data, 'add_rp_name').' ошибка при удалении')));
							
						}
						
						
						
					} else {
						Session::instance()->set('e_mess', $_data->errors('Valid_mess'));
						
					}
					$this->redirect('gate/list');
				
			break;
			
			case 'update'://обновление ворот
				
				//echo Debug::vars('101', $_POST); exit;
				//$data=Validation::factory($this->request->post());
				$_data=Validation::factory($this->request->post());
				$_data->rule('id', 'not_empty')
						->rule('id', 'digit')
						//->rule('name', 'not_empty')
						->rule('placenumber', 'digit')
						//->rule('placenumber', 'not_empty')
						
						;
				if($_data->check())
				{
					//echo Debug::vars('208', $_data);//exit;
					$entity = new Gate (Arr::get($_data, 'id'));
					
					$entity->tablo_ip = Arr::get($_data, 'tablo_ip');
					$entity->tablo_port = Arr::get($_data, 'tablo_port');
					$entity->box_ip = Arr::get($_data, 'box_ip');
					$entity->box_port = Arr::get($_data, 'box_port');
					$entity->channel = Arr::get($_data, 'channel');
					
					$entity->id_cam = Arr::get($_data, 'id_cam');
					$entity->id_dev = Arr::get($_data, 'id_dev');
					$entity->mode = Arr::get($_data, 'mode');
					$entity->name = Arr::get($_data, 'name');
					$entity->id_parking = Arr::get($_data, 'id_parking');
					$entity->is_enter = Arr::get($_data, 'is_enter');
									
					if($entity->update())
						{
							Session::instance()->set('ok_mess', array('ok_mess' => __(Arr::get($_data, 'name').' обновлен успешно')));
							
						} else {
							Session::instance()->set('e_mess', array('ok_mess' => __(Arr::get($_data, 'name').' ошибка при обновлении')));
							
						}
						
					
				} else 
				{
					echo Debug::vars('231');exit;
					Session::instance()->set('e_mess', $_data->errors('Valid_mess'));
					
				}
				
				$this->redirect('gate/list');
			break;
			
			case 'edit'://просмотр и редакция ворот
				//echo Debug::vars('122', $_POST); exit;
				
				$_data=Validation::factory($this->request->post());
				$_data->rule('id', 'not_empty')
						->rule('id', 'digit')
				;
				if($_data->check())
				{
					//echo Debug::vars('167', $_data, Arr::get($_data, 'id_rp'));//exit;
					$entity = new Gate(Arr::get($_data, 'id'));
					//echo Debug::vars('227', $entity);exit;
					$content = View::factory('gate/edit', array(
							'info_gate'=>$entity,
							));
					$this->template->content = $content;
				} else 
				{
					echo Debug::vars('193');exit;
					Session::instance()->set('e_mess', $_data->errors('Valid_mess'));
					$this->redirect('gate/list');
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
					echo Debug::vars('267 err', $data); exit;
					Session::instance()->set('e_mess', $data->errors('Valid_mess'));
						$this->redirect('gate');
				}
		
			break;
			
			default:
				//echo Debug::vars('95 controller gate', $_GET, $_POST); exit;
			break;
		}
				
	}
	
	public function action_edit()//редактировать и просматривать  ворота
	{
		$_SESSION['menu_active']='kp_park_menu';
		
		$id_gate = $this->request->param('id');
		$info_gate=Model::Factory('gates')->get_info_gate($id_gate); //получить общую информацию о воротах
		
		//echo Debug::vars('139', $info_gate, $_POST); exit;
		
		$content = View::factory('gate/edit', array(
			'info_gate'=>$info_gate,
			
		));
        $this->template->content = $content;
		
	}
	
	
	
	
} 
