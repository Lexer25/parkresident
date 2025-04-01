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
	
	
	public function action_index()// главная страница при входе. Показываю жилые комплексы + возможность добавить ЖК resident_place
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
						$id_resident=Model::factory('residentPlace')->get_list_rp();
						
					}
		//echo Debug::vars('52', $id_resident);exit;			
		$rubic_list=array();
		$count_busy=array();
		$org_list=array();
			
		//echo Debug::vars('38', $this->request->query(), $id_parking); //exit;
		//$rubic_list=Model::Factory('rubic')->get_list_parking($id_parking);//список парковок
		//$count_busy=Model::Factory('rubic')->count_busy();//список количества свободных мест
		//$org_list=Model::Factory('rubic')->get_list_org();//список организация для формирования новой парковки
		
		
		$content = View::factory('list', array(
			'rubic_list'=>$rubic_list,
			'org_list'=>$org_list,
			'count_busy'=>$count_busy,
			'id_resident'=>$id_resident,
			
		
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
				$_data=Validation::factory($this->request->post());
				$_data->rule('add_rp_name', 'not_empty')
							;
					if($_data->check())
					{
						//echo Debug::vars('113', Arr::get($_data, 'add_rp_name'));exit;
						$residence = new Residence();
						$residence->name=Arr::get($_data, 'add_rp_name');
						$residence->is_active=1;
						if ($residence->add())
						{
							Session::instance()->set('ok_mess', array('ok_mess' => __(Arr::get($_data, 'add_rp_name').' добавлено успешно')));
							
						} else {
							Session::instance()->set('err_mess', array('ok_mess' => __(Arr::get($_data, 'add_rp_name').' ошибка при добавлении')));
							
						}
						
						//Model::factory('residentplace')->add_rp(Arr::get($_data, 'add_rp_name'));// далее добавляем новый ЖК.
						
						
					} else 
					{
						Session::instance()->set('e_mess', $_data->errors('Valid_mess'));
						
					}
				$this->redirect('residentplace');
			break;
			
			case 'del_rp'://удаление Жилого комплекса из списка
				//echo Debug::vars('301', $_GET, $_POST); exit;
				$_data=Validation::factory($this->request->post());
				$_data->rule('id_rp', 'not_empty')
							->rule('id_rp', 'digit')
							;
					if($_data->check())
					{
						$residence = new Residence();
						//echo Debug::vars('144', $_data);exit;
						$residence->id=Arr::get($_data, 'id_rp');
						if($residence->del())
						{
							Session::instance()->set('ok_mess', array('ok_mess' => __(Arr::get($_data, 'add_rp_name').' удален успешно')));
							
						} else {
							Session::instance()->set('err_mess', array('ok_mess' => __(Arr::get($_data, 'add_rp_name').' ошибка при удалении')));
							
						}
						
						
						
					} else {
						Session::instance()->set('e_mess', $_data->errors('Valid_mess'));
						
					}
					$this->redirect('residentplace');
			break;
			
			case 'edit_rp'://просмотр и редакция парковки. Переход на форму редактирования
			//echo Debug::vars('235', $_GET, $_POST); exit;
				$_data=Validation::factory($this->request->post());
				$_data->rule('id_rp', 'not_empty')
						->rule('id_rp', 'digit')
						
						;
				/* if($_data->check())
				{
					$this->redirect('residentplace/edit/'.Arr::get($_data, 'id_rp'));
					echo Debug::vars('174');exit;
				} else 
				{
					//echo Debug::vars('177');exit;
					Session::instance()->set('e_mess', $_data->errors('Valid_mess'));
					$this->redirect('residentplace');
				} */
				
				
				if($_data->check())
				{
					//echo Debug::vars('167', $_data, Arr::get($_data, 'id_rp'));//exit;
					$residence = new Residence(Arr::get($_data, 'id_rp'));
					//echo Debug::vars('169', $residence);exit;
					$content = View::factory('edit', array(
							'residence'=>$residence,
							));
					$this->template->content = $content;
				} else 
				{
					echo Debug::vars('175');exit;
					Session::instance()->set('e_mess', $_data->errors('Valid_mess'));
					$this->redirect('residentplace');
				}
			break;
			
			case 'update_rp'://обновление данных о жилом комплексе. Примем данных и обновление данных о ЖК.
			//echo Debug::vars('185', $_GET, $_POST); exit;
				$_data=Validation::factory($this->request->post());
				$_data->rule('id_rp', 'not_empty')
						->rule('id_rp', 'digit')
						->rule('name', 'not_empty')
						
						;
				if($_data->check())
				{
					$residence = new Residence(Arr::get($_data, 'id_rp'));
					//echo Debug::vars('197', $residence);//exit;
					//echo Debug::vars('198', filter_var(Arr::get($_data, 'is_active'), FILTER_VALIDATE_BOOLEAN));//exit;
					$residence->name=Arr::get($_data, 'name');
					if(is_null(Arr::get($_data, 'is_active'))) $residence->is_active=0;
					if(filter_var(Arr::get($_data, 'is_active'), FILTER_VALIDATE_BOOLEAN)) 
					{
						$residence->is_active=1;
					} else{
						$residence->is_active=0;
					}
					//echo Debug::vars('197', $residence);exit;
					//echo Debug::vars('202', $residence);exit;
					if($residence->update())
						{
							Session::instance()->set('ok_mess', array('ok_mess' => __(Arr::get($_data, 'name').' обновлен успешно')));
							
						} else {
							Session::instance()->set('err_mess', array('ok_mess' => __(Arr::get($_data, 'name').' ошибка при обновлении')));
							
						}
						
					//Model::factory('residentplace')->update_rp($_data);
					//Session::instance()->set('ok_mess', array('ok_mess' => __(Arr::get($_data, 'name').' обновлен успешно')));
					
				} else 
				{
					Session::instance()->set('e_mess', $_data->errors('Valid_mess'));
					
				}
				$this->redirect('residentplace');
		
			break;
			
			
			
			default:
				//echo Debug::vars('755', $_GET, $_POST); exit;
				$this->redirect('/');
			break;
		}
		//$content='';
        //$this->template->content = $content;
		
		
	}

		
	public function _action_edit()// редактирование информации о жилом комплекса
	{
		
		$param=array('id'=>$this->request->param('id'));
		//echo Debug::vars('263', $_GET, $_POST, $this->request->param('id'), $param); //exit;
		$_data=Validation::factory($param);
		$_data->rule('id', 'not_empty')
				->rule('id', 'digit')
				;
		if($_data->check())
		{
			$residence = new Residence(Arr::get($_data, 'id'));
			//echo Debug::vars('252', $residence);exit;
			$content = View::factory('edit', array(
					'residence'=>$residence,
					));
			$this->template->content = $content;
		} else 
		{

			Session::instance()->set('e_mess', $_data->errors('Valid_mess'));
			$this->redirect('residentplace');
		}
	
	}
} 
