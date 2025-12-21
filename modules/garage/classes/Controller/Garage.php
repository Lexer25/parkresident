<?php defined('SYSPATH') or die('No direct script access.');

/**

* @package    ParkResident/Garage
 * @category   Base
 * @author     Artonit
 * @copyright  (c) 2025 Artonit Team
 * @license    http://artonit/ru 
 
 */

class Controller_Garage extends Controller_Template {
	
	
	public $template = 'template';
	public $config;
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
				
		if(Kohana::$config->load('artonitparking_config') !== null) $this->config=Kohana::$config->load('artonitparking_config');
				
	}
	
	public function action_index() // получить список гаражей
	{
		//$_SESSION['menu_active']='garage';
		
		$id_parking=1;
		//echo Debug::vars('20', $_SESSION);
		$t1=microtime(true);
		
		$garageLst=Model::Factory('garage')->getAllGarageInfo2();// список гаражей в виде класса
		
		$garageListView=View::factory('garage/block/garageListBlock2',//подготовка таблицы гаражей. Эта таблица позже будет вставлена в общую форму
			array('garageLst'=>$garageLst));
		
		$content = View::factory('garage/garageList', array(
			'garageListView'=>$garageListView,
			't1'=>$t1,
		));
        $this->template->content = $content;
		//echo View::factory('profiler/stats');
	}
	
		public function action_edit_garage()//редактировать и просматривать  гараж
	{
		$t1=microtime(true);
		//$_SESSION['menu_active']='kp_park_menu';
		//echo Debug::vars('43', $_GET, $_POST, $this->request->param('id')); exit;
		$id_garage = $this->request->param('id');
		$modelGarage=Model::Factory('garage');
		$garage_info=$modelGarage->get_garage_info($id_garage); //получить информация о гараже
		
		$place_income_garage=$modelGarage->place_income_garage($id_garage); //список машиномест, входящих в гараж
		$place_busy=$modelGarage->place_busy_garage(); //список машиномест, входящих в другие гаражи
		$org_income_garage=$modelGarage->org_income_garage($id_garage); //список квартир, входящих в гараж
		$place_grz_garage_=$modelGarage->place_grz_garage_($id_garage); //список ГРЗ, входящих в гараж
	
	
		$place_list=Model::Factory('rubic')->get_list_parking_place();//список парковочный мест
		
		
		$org_busy=$modelGarage->org_busy_garage(); //список квартир с пометкой принадлежности к другим гаражам. Их надо пометить как неактивные и запретить выбор.
		$org_can_view=Model::Factory('treeorg')->make_tree($org_busy, $id_garage);
		

		//14.12.2025 добавляю журнал событий для гаража
		$garage = new Garage($id_garage);
		$garage->eventList=array(3,4,5,6,7,8,9,10);//список событий, которые необходимо выводить для гаража
		
		
		$eventsListForGarage=$modelGarage->getListEventsForGarage($garage, $this->config->deepEvent);//я передаю весь класс garage, получаю массив для вывода на экран
	

	$eventtable= View::factory('garage/event')// вывод таблицы журнала событий для гаража
				->set('list', $eventsListForGarage)
				
			; 
			

		$content = View::factory('garage/edit_garage', array(
			'garage_info'=>$garage_info,
			'place_list'=>$place_list,
			'place_income_garage'=>$place_income_garage,
			'place_busy'=>$place_busy,
			'org_can_view'=>$org_can_view,
			'org_income_garage'=>$org_income_garage,
			'place_grz_garage_'=>$place_grz_garage_,
			'eventtable'=>$eventtable,
			'deepEvent'=>$this->config->deepEvent,
			't1'=>$t1,
			
		));
        $this->template->content = $content;
		//echo View::factory('profiler/stats');
	}
	

	public function action_control()
	{
		//echo Debug::vars('30', $_GET, $_POST); exit;
		
		$todo = $this->request->post('todo');
		switch ($todo){
			
			case 'example':
			$post=Validation::factory($this->request->post());
				$post->rule('id_parking', 'digit')
						->rule('id_parking', 'not_empty')
						->rule('id_dev', 'digit')
						->rule('id_dev', 'not_empty')
						;
				if($post->check())
				{	
				//to do
				$this->redirect('rubic/edit_rubic/'.Arr::get($post, 'ID', 0));
				} else {
					//echo Debug::vars('293', $post->errors('Valid_mess')); exit;	
					Log::instance()->add(Log::ERROR, $post->errors('Valid_mess'));
				}
				$this->redirect('rubic/edit_garage/'.Arr::get($post, 'id'));
			break;
			
			
			case 'edit_garage':
			$post=Validation::factory($this->request->post());
				$post->rule('id_garage', 'digit')
						->rule('id_garage', 'not_empty')
						->rule('id_dev', 'digit')
						;
				if($post->check())
				{	
				//to do
				$this->redirect('garage/edit_garage/'.Arr::get($post, 'id_garage', 0));
				} else {
					//echo Debug::vars('293', $post->errors('Valid_mess')); exit;	
					Log::instance()->add(Log::ERROR, $post->errors('Valid_mess'));
				}
				$this->redirect('rubic/edit_garage/'.Arr::get($post, 'id'));
			break;
			
			
			
			case 'change_garage'://изменить параметры гаража
				$post=Validation::factory($this->request->post());
				$post->rule('id_garage', 'digit')
						->rule('id_garage', 'not_empty')
						->rule('name', 'not_empty')
						;
				if($post->check())
				{
					Model::factory('garage')->update_garage($post);
					//echo Debug::vars('96',Arr::get($post, 'id_garage')); exit; 
					$this->redirect('garage/edit_garage/'.Arr::get($post, 'id_garage'));
				} else {
					//echo Debug::vars('293', $post->errors('Valid_mess')); exit;	
					Log::instance()->add(Log::ERROR, $post->errors('Valid_mess'));
				}
				$this->redirect('garage/edit_garage/'.$id_rubic);
					
			break;
	
			/**18.05.2025
			*/
			case 'addarray'://добавление массива гаражей
				//echo Debug::vars('154', $_GET, $_POST); exit;
							
					$result_ok=array();
					$result_err=array();
					$post=Validation::factory($this->request->post());
						$post->rule('prefix', 'not_empty')
							->rule('number1', 'digit')
							->rule('number2', 'digit')
							;
					if($post->check())
					{
						//echo Debug::vars('163', 'valid OK'); exit;
						$garage=Model::factory('garage');
						//организую цикл для каждого номера гаража
						for($i=Arr::get($post, 'number1');$i<=Arr::get($post, 'number2');$i++)
						{
							//формирую массив для регистрации гаража
							
							$_data=array(
								'name'=>$name=Arr::get($post, 'prefix').$i,
							);
							
							Log::instance()->add(Log::DEBUG, '171 '.Debug::vars($_data));
							//проверяю данные для регистрации на уникальность имени
							$data=Validation::factory($_data);
								$data->rule('name', 'not_empty')
								->rule('name', 'Model_garage::checkNameIsUnique')
							;
							if($data->check())
							{
								//echo Debug::vars('233', 'valid OK'); exit;
								
								$result_ok[] = __('Гараж ":name добавлен успешно под id :id".', array(':name'=>$name, ':id'=>Model::factory('garage')->add_garage($data)));
																
							} else 
							{
								//echo Debug::vars('241', 'valid ERR'); exit;
								Log::instance()->add(Log::ERROR, $post->errors('garage_Valid_mess'));
								//Session::instance()->set('e_mess', $post->errors('garage_Valid_mess'));
								//$this->redirect('garage');
								$result_err[] = Arr::get($data->errors('garage_Valid_mess'), 'name');
								
							}
							
							
							
						}
						// echo Debug::vars('200-0', $result_ok);
						// echo Debug::vars('200-1', $result_err);exit;
						Session::instance()->set('ok_mess', $result_ok);
						Session::instance()->set('e_mess', $result_err);
						
						$this->redirect('garage');
						
					} else 
					{
						//echo Debug::vars('415', 'valid ERR'); exit;
						Log::instance()->add(Log::ERROR, $post->errors('Valid_mess'));
						Session::instance()->set('e_mess', $post->errors('Valid_mess'));
						$this->redirect('garage');
					}
			break;
			
			
			case 'add_place_to_garage'://добавление машиноместа в гараж. Приходит id_garage гаража и id_place список машиномест, которые должны входить в гараж.
					//echo Debug::vars('695', $_GET, $_POST); exit;
							
					$post=Validation::factory($this->request->post());
					
					$post->rule('id_garage', 'not_empty')
						->rule('id_garage', 'digit')
						//->rule('id_place', 'not_empty')
						//->rule('id_place', 'is_array')
							;
					if($post->check())
					{
						//echo Debug::vars('415', 'valid OK'); exit;
						$placeList=Model::factory('garage')->place_income_garage(Arr::get($post,'id_garage'));
									
						
						Model::factory('garage')->add_place_to_garage($post);
						$this->redirect('garage/edit_garage/'.Arr::get($post, 'id_garage'));
						
					} else 
					{
						//echo Debug::vars('415', 'valid ERR'); exit;
						Log::instance()->add(Log::ERROR, $post->errors('Valid_mess'));
						Session::instance()->set('e_mess', $post->errors('Valid_mess'));
						//$this->redirect('rubic/event');
					}
			break;
			
			
			case 'add_new_garage'://добавление нового гаража
				//echo Debug::vars('153', $_GET, $_POST); exit;
							
					$post=Validation::factory($this->request->post());
					
					$post->rule('name', 'not_empty')
						->rule('name', 'Model_garage::checkNameIsUnique')
							;
					if($post->check())
					{
						//echo Debug::vars('233', 'valid OK'); exit;

						if($result = Model::factory('garage')->add_garage($post) >0)
						{
							Session::instance()->set('ok_mess', array('Успешно 235'=>$result));
						} else {
							Session::instance()->set('e_mess', array('Успешно 235'=>$result));
							
						}
							
						$this->redirect('garage');
						
					} else 
					{
						//echo Debug::vars('241', 'valid ERR'); exit;
						Log::instance()->add(Log::ERROR, $post->errors('garage_Valid_mess'));
						Session::instance()->set('e_mess', $post->errors('garage_Valid_mess'));
						$this->redirect('garage');
					}
			break;
			
			
			case 'del_garage'://удаление гаража
			case 'delete_garage'://удаление гаража
				//echo Debug::vars('231', $_GET, $_POST); exit;
							
					$post=Validation::factory($this->request->post());
					$post->rule('id', 'not_empty');
					if($post->check())
					{
						//echo Debug::vars('415', 'valid OK'); exit;
						//$res=Model::factory('garage')->del_garage(Arr::get($post, 'id_garage'));
						$res=Model::factory('garage')->del_garage_without_check_child(Arr::get($post, 'id'));
						if($res==0)
						{
							Session::instance()->set('ok_mess', array('desc'=>'Команда удаления гаража выполнена успешно.'));
							$this->redirect('garage');
						} else {
							Session::instance()->set('e_mess', array('desc'=>'Команда удаления гаража не выполнена. Удалите машиноместа из гаража и повторите операцию.'));
							$this->redirect('garage');
						}
						
					} else 
					{
						//echo Debug::vars('415', 'valid ERR'); exit;
						Session::instance()->set('e_mess', $post->errors('Valid_mess'));
						$this->redirect('garage');
					}
			break;
			
			
			case 'del_place_from_garage'://удаление машиноместа из гаража
				//echo Debug::vars('199', $_GET, $_POST); exit;
							
					$post=Validation::factory($this->request->post());
					$post->rule('id_place_for_del', 'not_empty')
						->rule('id_place_for_del', 'digit')
						->rule('id_garage', 'not_empty')
						->rule('id_garage', 'digit')
						
					;
					if($post->check())
					{
						//echo Debug::vars('415', 'valid OK'); exit;
						Model::factory('garage')->del_place_from_garage(Arr::get($post,'id_place_for_del'));
						Session::instance()->set('ok_mess', array('desc'=>'Удаление машиноместа '.Arr::get($post,'id_place_for_del').' из гаража выполнено успешно.'));
						$this->redirect('garage/edit_garage/'.Arr::get($post, 'id_garage'));
						
					} else 
					{
						//echo Debug::vars('415', 'valid ERR'); exit;
						Log::instance()->add(Log::ERROR, $post->errors('Valid_mess'));
						Session::instance()->set('e_mess', $post->errors('Valid_mess'));
						$this->redirect('rubic/event');
					}
			break;
			
			
			case 'add_org_to_garage'://Добавление квартиры к гаражу
				//echo Debug::vars('252', $_GET, $_POST); exit;
							
					$post=Validation::factory($this->request->post());
					$post->rule('id_garage', 'not_empty')
						->rule('id_garage', 'digit')
						//->rule('id_org_for_add_garage', 'not_empty')
						//->rule('id_org_for_add_garage', 'is_array')
					;
					if($post->check())
					{
						//echo Debug::vars('262', 'valid OK', $_POST); exit;
						Model::factory('garage')->add_org_to_garage($post);
						
						$this->redirect('garage/edit_garage/'.Arr::get($post, 'id_garage'));
						
					} else 
					{
						//echo Debug::vars('269', 'valid ERR', $post->errors('Valid_mess')); exit;
						Log::instance()->add(Log::ERROR, $post->errors('Valid_mess'));
						Session::instance()->set('e_mess', $post->errors('Valid_mess'));
						$this->redirect('rubic/event');
					}
			break;
			
			case 'del_once_org_from_garage'://удаление квартиры (организации) из гаража 26.02.2023
				//echo Debug::vars('290', $_GET, $_POST); exit;
							
					$post=Validation::factory($this->request->post());
					$post->rule('id_garage', 'not_empty')
						->rule('id_garage', 'digit')
						->rule('id_org_for_del_from_garage', 'not_empty')
						->rule('id_org_for_del_from_garage', 'digit')
					;
					if($post->check())
					{
						//echo Debug::vars('262', 'valid OK', $_POST); exit;
						Model::factory('garage')->del_once_org_from_garage(Arr::get($post, 'id_garage'), Arr::get($post, 'id_org_for_del_from_garage'));
						Session::instance()->set('ok_mess', array('desc'=>'Команда удаления организации из гаража выполнена успешно.'));
						$this->redirect('garage/edit_garage/'.Arr::get($post, 'id_garage'));
						
					} else 
					{
						//echo Debug::vars('269', 'valid ERR', $post->errors('Valid_mess')); exit;
						Log::instance()->add(Log::ERROR, $post->errors('Valid_mess'));
						Session::instance()->set('e_mess', $post->errors('Valid_mess'));
						$this->redirect('rubic/event');
					}
			break;
			
			
			
			
			
			
			default:
				
				Session::instance()->set('e_mess', Debug::vars('156', $_GET, $_POST));
				echo Debug::vars('156', $_GET, $_POST); exit;
				$this->redirect('garage');
			break;
		}
		$content='';
        $this->template->content = $content;
		
	}

	
} 
