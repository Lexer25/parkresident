<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Place extends Controller_Template { // класс описывает машиноместо
	
	
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
	
	
	public function action_index()//Показываю список машиномест для указанных паркингов
	{
		$id = $this->request->param('id');
		$_SESSION['menu_active']='rubic';
		$query=Validation::factory($this->request->query());
					$query->rule('id_parking', 'not_empty')
							->rule('id_parking', 'digit')
							->rule('id_parking', 'digit')
							;
					if($query->check())
					{
						$id_parking=Arr::get($query, 'id_parking'); // имеется номер паркинга и надо показывать именно его
						
						
					} else 
					{
						//$id_parking=0; // номер родительской паровки не указан.
						$id_parking=Model::factory('ParkingPlace')->get_list();
						
					}
					//echo Debug::vars('44', $id_parking);exit;
		$content = View::factory('place/matrix', array(
			
			'id_parking'=>$id_parking,
			
		
		));
        $this->template->content = $content;
	}
	
	
	/** 4.04.2025 	Показываю список машиномест для указанных паркингов
	*если паркинг указан, то показываю места именно этого паркинга
	*если паркинг НЕ указан, то показываю все.
	*/
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

						$id_place=Model::factory('place')->getChild(Arr::get($query, 'id'));
						
					} else 
					{
						//echo Debug::vars('75');exit;
						//$id_parking=0; // номер родительской паровки не указан. надо показывать все ЖК.
						//$id_parking=Model::factory('ParkingPlace')->get_list();
						$id_place=Model::factory('place')->getAll();
						
					}
		
		$content = View::factory('place/list', array(
			
			'id_place'=>$id_place,
			
		
		));
        $this->template->content = $content;
	}
	
	
	/** 10.04.2025 	Показываю список машиномест для указанного паркинга
	*в виде матрицы
	*если паркинг НЕ указан, то ничего не показываю..
	*/
	public function action_matrix()//
	{
		$id = $this->request->param('id');
		//$_SESSION['menu_active']='rubic';
		$query=Validation::factory($this->request->param());
					$query->rule('id', 'not_empty')
							->rule('id', 'digit')
							;
					if($query->check())
					{

						//$id_place=Model::factory('place')->getChild(Arr::get($query, 'id'));
						$id_place[]=array('ID'=>$id);
						
						
					} else 
					{
						//echo Debug::vars('75');exit;
						//$id_parking=0; // номер родительской паровки не указан. надо показывать все ЖК.
						//$id_parking=Model::factory('ParkingPlace')->get_list();
						$id_place=Model::factory('place')->getAll();
						
					}
		$id_place[]=array('ID'=>$id);
		$content = View::factory('place/matrix', array(
			
			'id_place'=>$id_place,
			
		
		));
        $this->template->content = $content;
	}
	
	
	
	
	public function action_control()
	{
		//echo Debug::vars('95', $_POST);exit;
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
			
			
			
			case 'add'://добавление нового машиноместа

				$_data=Validation::factory($this->request->post());
				$_data->rule('placenumber', 'not_empty')
							;
					if($_data->check())
					{
						
						$entity = new Place();
						$entity->name='Новое машиноместо_'.Arr::get($_data, 'placenumber');
						$entity->placenumber=Arr::get($_data, 'placenumber');
						$entity->id_parking=0;
						$entity->description="";
						$entity->note="";
						$entity->status=0;
						if ($entity->add())
						{
							Session::instance()->set('ok_mess', array('ok_mess' => __(Arr::get($_data, 'add_rp_name').' добавлено успешно')));
							
						} else {
							Session::instance()->set('err_mess', array('ok_mess' => __(Arr::get($_data, 'add_rp_name').' ошибка при добавлении')));
							
						}
						
						
					} else 
					{
						//echo Debug::vars('137');exit;
						Session::instance()->set('e_mess', $_data->errors('Valid_mess'));
						
					}
				$this->redirect('place/list');
			break;
			
			case 'del'://удаление Жилого комплекса из списка
				//echo Debug::vars('301', $_GET, $_POST); exit;
				$_data=Validation::factory($this->request->post());
				$_data->rule('id', 'not_empty')
							->rule('id', 'digit')
							;
					if($_data->check())
					{
						$entity = new Place();
						
						$entity->id=Arr::get($_data, 'id');
						if($entity->del())
						{
							Session::instance()->set('ok_mess', array('ok_mess' => __(Arr::get($_data, 'add_rp_name').' удален успешно')));
							
						} else {
							Session::instance()->set('err_mess', array('ok_mess' => __(Arr::get($_data, 'add_rp_name').' ошибка при удалении')));
							
						}
						
						
						
					} else {
						Session::instance()->set('e_mess', $_data->errors('Valid_mess'));
						
					}
					$this->redirect('place/list');
			break;
			
			case 'edit'://просмотр и редакция парковки. Переход на форму редактирования
			//echo Debug::vars('174', $_GET, $_POST); exit;
				$_data=Validation::factory($this->request->post());
				$_data->rule('id', 'not_empty')
						->rule('id', 'digit')
				;
				if($_data->check())
				{
					//echo Debug::vars('167', $_data, Arr::get($_data, 'id_rp'));//exit;
					$entity = new Place(Arr::get($_data, 'id'));
					//echo Debug::vars('183', $entity);exit;
					$content = View::factory('place/edit', array(
							'place'=>$entity,
							));
					$this->template->content = $content;
				} else 
				{
					echo Debug::vars('193');exit;
					Session::instance()->set('e_mess', $_data->errors('Valid_mess'));
					$this->redirect('place/list');
				}
			break;
			
			
			
			case 'editMatrix'://редактирование машиноместа при вызове его из таблицы
			//echo Debug::vars('174', $_GET, $_POST); exit;
				$_data=Validation::factory($this->request->post());
				$_data->rule('place', 'not_empty')
						->rule('place', 'digit')
						->rule('parking', 'digit')
						->rule('parking', 'digit')
				;
				if($_data->check())
				{
					//echo Debug::vars('248', $_data, Arr::get($_data, 'id_rp'));//exit;
					$entity = new PlaceNP(Arr::get($_data, 'place'), Arr::get($_data, 'parking'));
					
					if(is_null($entity->id))//если нет машиноместа с указанным номером и парковкой, то надо будет создавать новое (а не обновлять)
					{
						$entity->placenumber  = Arr::get($_data, 'place');
						$entity->id_parking    = Arr::get($_data, 'parking');
					}
					//echo Debug::vars('250', Arr::get($_data, 'place'), Arr::get($_data, 'parking'),$entity);exit;
					$content = View::factory('place/edit', array(
							'place'=>$entity,
							));
					$this->template->content = $content;
				} else 
				{
					echo Debug::vars('193');exit;
					Session::instance()->set('e_mess', $_data->errors('Valid_mess'));
					$this->redirect('place/list');
				}
			break;
			
			
			
			
			case 'update'://обновление данных о жилом комплексе. Примем данных и обновление данных о ЖК.
			echo Debug::vars('185', $_GET, $_POST); exit;
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
					$entity = new Place (Arr::get($_data, 'id'));
					$entity->placenumber=Arr::get($_data, 'placenumber');
					$entity->name=Arr::get($_data, 'name');
					$entity->id_parking=Arr::get($_data, 'id_parking');
					$entity->description=Arr::get($_data, 'description');
					$entity->note=Arr::get($_data, 'note');
					$entity->status=Arr::get($_data, 'status');
					
					if(Arr::get($_data, 'status') == '') $entity->status=0;
					if(Arr::get($_data, 'id_parking') == '') $entity->id_parking=0;
					//echo Debug::vars('211', $entity);exit;
					if($entity->update())
						{
							Session::instance()->set('ok_mess', array('ok_mess' => __(Arr::get($_data, 'name').' обновлен успешно')));
						} else {
							Session::instance()->set('err_mess', array('ok_mess' => __(Arr::get($_data, 'name').' ошибка при обновлении')));
						}
				} else 
				{
					echo Debug::vars('298');exit;
					Session::instance()->set('e_mess', $_data->errors('Valid_mess'));
				}
				$this->redirect('place/list');
			break;
			
			
			case 'updateNP'://обновление данных о жилом комплексе. Примем данных и обновление данных о ЖК.
			//echo Debug::vars('306', $_GET, $_POST); exit;
				$_data=Validation::factory($this->request->post());
				$_data->rule('placenumber', 'not_empty')
						->rule('placenumber', 'digit')
						->rule('id_parking', 'not_empty')
						->rule('id_parking', 'digit')
						
						;
				if($_data->check())
				{
					
					
					//echo Debug::vars('208', $_data);//exit;
					$entity = new PlaceNP (Arr::get($_data, 'placenumber'), Arr::get($_data, 'id_parking'));
					$entity->placenumber=Arr::get($_data, 'placenumber');
					$entity->name=Arr::get($_data, 'name');
					$entity->id_parking=Arr::get($_data, 'id_parking');
					$entity->description=Arr::get($_data, 'description');
					$entity->note=Arr::get($_data, 'note');
					$entity->status=Arr::get($_data, 'status');
					
					if(Arr::get($_data, 'status') == '') $entity->status=0;
					if(Arr::get($_data, 'id_parking') == '') $entity->id_parking=0;
					//echo Debug::vars('211', $entity, is_null($entity->id));exit;
					if(is_null($entity->id))
					{
						//нет такого машиноместа, надо добавлять.
						//echo Debug::vars('339');exit;
						if($entity->add())
						{
							Session::instance()->set('ok_mess', array('ok_mess' => __(Arr::get($_data, 'name').' обновлен успешно')));
						} else {
							Session::instance()->set('err_mess', array('ok_mess' => __(Arr::get($_data, 'name').' ошибка при обновлении')));
						}
						
					} else { //есть такое машиноместо. Надо обновлять.
					//echo Debug::vars('348');exit;
						if($entity->update())
							{
								Session::instance()->set('ok_mess', array('ok_mess' => __(Arr::get($_data, 'name').' обновлен успешно')));
							} else {
								Session::instance()->set('err_mess', array('ok_mess' => __(Arr::get($_data, 'name').' ошибка при обновлении')));
							}
					}
				} else 
				{
					echo Debug::vars('336');exit;
					Session::instance()->set('e_mess', $_data->errors('Valid_mess'));
				}
				$this->redirect('place/list');
			break;
			
			
			
			default:
				//echo Debug::vars('755', $_GET, $_POST); exit;
				$this->redirect('place/list');
			break;
		}
		
		
	}


		/*10.04.2025 редактирование машиноместа из таблицы
		*/
		public function action_addMatrix()
		{
			
			echo Debug::vars('297', $_POST, $this->request->param('id'));exit;
			$_data=Validation::factory($this->request->post());
				$_data->rule('place', 'not_empty')
						->rule('place', 'digit')
						->rule('parking', 'not_empty')
						->rule('parking', 'digit')
						
						;
				if($_data->check())
				{
					//echo Debug::vars('208', $_data);//exit;
					$entity = new PlaceNP (Arr::get($_data, 'place'), Arr::get($_data, 'parking'));
					$entity->placenumber=Arr::get($_data, 'placenumber');
					$entity->name=Arr::get($_data, 'name');
					$entity->id_parking=Arr::get($_data, 'id_parking');
					$entity->description=Arr::get($_data, 'description');
					$entity->note=Arr::get($_data, 'note');
					$entity->status=Arr::get($_data, 'status');
					
					if(Arr::get($_data, 'status') == '') $entity->status=0;
					if(Arr::get($_data, 'id_parking') == '') $entity->id_parking=0;
					//echo Debug::vars('211', $entity);exit;
					
					if($entity->update())
						{
							Session::instance()->set('ok_mess', array('ok_mess' => __(Arr::get($_data, 'name').' обновлен успешно')));
							
						} else {
							Session::instance()->set('err_mess', array('ok_mess' => __(Arr::get($_data, 'name').' ошибка при обновлении')));
							
						}
						
					
				} else 
				{
					echo Debug::vars('394');exit;
					Session::instance()->set('e_mess', $_data->errors('Valid_mess'));
					
				}
				$this->redirect('place/list');
			
		}
	
} 
