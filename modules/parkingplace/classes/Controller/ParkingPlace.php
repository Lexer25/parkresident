<?php defined('SYSPATH') or die('No direct script access.');

class Controller_ParkingPlace extends Controller_Template { // класс описывает парковочные площадки (которые находятся на территории жилого комплекса)

	
	
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
	
	
	public function action_index()// главная страница при входе. Показывает все парковочные площадки
	{
		$id = $this->request->param('id');
		//$_SESSION['menu_active']='rubic';
		$query=Validation::factory($this->request->query());
					$query->rule('id_resident', 'not_empty')
							->rule('id_resident', 'digit')
							;
					if($query->check())
					{
						$id_resident=Arr::get($query, 'id_resident'); // имеется номер ЖК, и надо показывать именно его
						
						
					} else 
					{
						$id_resident=0; // номер родительской паровки не указан. надо показывать все ЖК.
						$id_resident=Model::factory('parkingPlace')->get_list();
						
					}
		$content = View::factory('parking/list', array(
			
			'id_resident'=>$id_resident,
			
		
		));
        $this->template->content = $content;
	}
	
	public function action_control()
	{
		//echo Debug::vars('56', $_POST);exit;
		$post=Validation::factory($this->request->post());
					$post->rule('todo', 'not_empty')
							
							;
					if($post->check())
					{
						$todo = Arr::get($post, 'todo');
						
					} else 
					{
						$todo='no';
						
					}
		switch ($todo){
			
			
			
			case 'add'://добавление парковочной площадки
				$_data=Validation::factory($this->request->post());
				$_data->rule('name', 'not_empty')
							;
					if($_data->check())
					{
						//echo Debug::vars('113', Arr::get($_data, 'name'));exit;
						$_entity = new Parking();
						$_entity->name=Arr::get($_data, 'name');
						$_entity->is_active=1;
						if ($_entity->add())
						{
							Session::instance()->set('ok_mess', array('ok_mess' => __(Arr::get($_data, 'name').' добавлено успешно')));
							
						} else {
							Session::instance()->set('err_mess', array('ok_mess' => __(Arr::get($_data, 'name').' ошибка при добавлении')));
							
						}
						
						
					} else 
					{
						Session::instance()->set('e_mess', $_data->errors('Valid_mess'));
						
					}
				$this->redirect('parkingplace');
			break;
			
			case 'del'://удаление Жилого комплекса из списка
				//echo Debug::vars('301', $_GET, $_POST); exit;
				$_data=Validation::factory($this->request->post());
				$_data->rule('id', 'not_empty')
							->rule('id', 'digit')
							;
					if($_data->check())
					{
						$_entity = new Parking();
						
						$_entity->id=Arr::get($_data, 'id');
						if($_entity->del())
						{
							Session::instance()->set('ok_mess', array('ok_mess' => __(Arr::get($_data, 'add_rp_name').' удален успешно')));
							
						} else {
							Session::instance()->set('err_mess', array('ok_mess' => __(Arr::get($_data, 'add_rp_name').' ошибка при удалении')));
							
						}
						
						
						
					} else {
						Session::instance()->set('e_mess', $_data->errors('Valid_mess'));
						
					}
					$this->redirect('parkingplace');
			break;
			
			case 'edit'://просмотр и редакция парковки. Переход на форму редактирования
			//echo Debug::vars('235', $_GET, $_POST); exit;
				$_data=Validation::factory($this->request->post());
				$_data->rule('id', 'not_empty')
						->rule('id', 'digit')
						
						;
				
				
				if($_data->check())
				{
					//echo Debug::vars('167', $_data, Arr::get($_data, 'id'));//exit;
					$_entity = new Parking(Arr::get($_data, 'id'));
					//echo Debug::vars('169', $_entity);exit;
					$content = View::factory('parking/edit', array(
							'parking'=>$_entity,
							));
					$this->template->content = $content;
				} else 
				{
					echo Debug::vars('175');exit;
					Session::instance()->set('e_mess', $_data->errors('Valid_mess'));
					$this->redirect('parkingplace');
				}
			break;
			
			case 'update'://обновление данных о жилом комплексе. Примем данных и обновление данных о ЖК.
			//echo Debug::vars('185', $_GET, $_POST); exit;
				$_data=Validation::factory($this->request->post());
				$_data->rule('id', 'not_empty')
						->rule('id', 'digit')
						->rule('name', 'not_empty')
						
						;
				if($_data->check())
				{
					$_entity = new Parking(Arr::get($_data, 'id'));
					$_entity->name=Arr::get($_data, 'name');
					if(is_null(Arr::get($_data, 'is_active'))) $_entity->is_active=0;
					if(filter_var(Arr::get($_data, 'is_active'), FILTER_VALIDATE_BOOLEAN)) 
					{
						$_entity->is_active=1;
					} else{
						$_entity->is_active=0;
					}
					if($_entity->update())
						{
							Session::instance()->set('ok_mess', array('ok_mess' => __(Arr::get($_data, 'name').' обновлен успешно')));
							
						} else {
							Session::instance()->set('err_mess', array('ok_mess' => __(Arr::get($_data, 'name').' ошибка при обновлении')));
							
						}
						
					
				} else 
				{
					Session::instance()->set('e_mess', $_data->errors('Valid_mess'));
					
				}
				$this->redirect('parkingplace');
		
			break;
			
			
			
			default:
				//echo Debug::vars('755', $_GET, $_POST); exit;
				$this->redirect('/');
			break;
		}
		
		
	}

		
	
} 
