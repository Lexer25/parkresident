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
	
	public function action_index() // получить список гаражей
	{
		$_SESSION['menu_active']='garage';
		
		$id_parking=1;
		//echo Debug::vars('20', $_SESSION);
		
		$garageLst=Model::Factory('garage')->getAllGarageInfo();// список гаражей в виде класса
		$content = View::factory('garage/garageList', array(
			'garageLst'=>$garageLst,
				
		));
        $this->template->content = $content;
		//echo View::factory('profiler/stats');
	}
	
		public function action_edit_garage()//редактировать и просматривать  гараж
	{
		$_SESSION['menu_active']='kp_park_menu';
		//echo Debug::vars('43', $_GET, $_POST, $this->request->param('id')); exit;
		$id_garage = $this->request->param('id');
		$garage_info=Model::Factory('garage')->get_garage_info($id_garage); //получить информация о гараже
		$place_list=Model::Factory('rubic')->get_list_parking_place();//список парковочный мест
		$place_income_garage=Model::Factory('garage')->place_income_garage($id_garage); //список машиномест, входящих в гараж
		$place_busy=Model::Factory('garage')->place_busy_garage(); //список машиномест, входящих в другие гаражи
		$org_income_garage=Model::Factory('garage')->org_income_garage($id_garage); //список квартир, входящих в гараж
		$place_grz_garage_=Model::Factory('garage')->place_grz_garage_($id_garage); //список ГРЗ, входящих в гараж
		//echo Debug::vars('56 debug'); exit;
		
		
		$org_busy=Model::Factory('garage')->org_busy_garage(); //список квартир с пометкой принадлежности к другим гаражам. Их надо пометить как неактивные и запретить выбор.
		//$org_can=Model::Factory('garage')->org_can_garage(); //список квартир, учитывающихся для въезда в гараж. Их надо пометить как активные и разрешить выбор. Кандидат на удаление
		$org_can_view=Model::Factory('treeorg')->make_tree($org_busy, $id_garage);
		
	
		//echo Debug::vars('59', $org_income_garage); exit;
		
		$content = View::factory('garage/edit_garage', array(
			'garage_info'=>$garage_info,
			'place_list'=>$place_list,
			'place_income_garage'=>$place_income_garage,
			'place_busy'=>$place_busy,
			'org_can_view'=>$org_can_view,
			'org_income_garage'=>$org_income_garage,
			'place_grz_garage_'=>$place_grz_garage_,
			
		));
        $this->template->content = $content;
		//echo View::factory('profiler/stats');
	}
	

	public function action_control()
	{
		echo Debug::vars('30', $_GET, $_POST); exit;
		
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
						Log::instance()->add(Log::ERROR, $post->errors('Valid_mess'));
						Session::instance()->set('e_mess', $post->errors('Valid_mess'));
						$this->redirect('rubic/event');
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
							;
					if($post->check())
					{
						//echo Debug::vars('415', 'valid OK'); exit;
						Model::factory('garage')->add_garage($post);
						
						$this->redirect('garage');
						
					} else 
					{
						//echo Debug::vars('415', 'valid ERR'); exit;
						Log::instance()->add(Log::ERROR, $post->errors('Valid_mess'));
						Session::instance()->set('e_mess', $post->errors('Valid_mess'));
						$this->redirect('rubic/event');
					}
			break;
			
			
			case 'del_garage'://удаление гаража
				//echo Debug::vars('176', $_GET, $_POST); exit;
							
					$post=Validation::factory($this->request->post());
					$post->rule('name', 'not_empty');
					if($post->check())
					{
						//echo Debug::vars('415', 'valid OK'); exit;
						$res=Model::factory('garage')->del_garage(Arr::get($post, 'id_garage'));
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
