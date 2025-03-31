<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Parking extends Controller_Template { // класс описывает парковочные площадки
	
	
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
	
	
	public function action_index()// главная страница при входе. Показываю жилые комплексы + возможность добавить ЖК
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
		$rubic_list=Model::Factory('parking')->get_list_parking($id_parking);//список парковок
		$count_busy=Model::Factory('parking')->count_busy();//список количества свободных мест
		
		$content = View::factory('rubic/parking', array(
			'rubic_list'=>$rubic_list,
			'count_busy'=>$count_busy,
			//'setup'=>$setup,
			
		
		));
        $this->template->content = $content;
	}
	
	public function action_control()
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
			
			case 'edit_parking'://просмотр и редакция парковки
				$post=Validation::factory($this->request->post());
				$post->rule('id_parking', 'not_empty')
						->rule('id_parking', 'digit')
						;
				if($post->check())
				{
					$this->redirect('parking/edit/'.Arr::get($post, 'id_parking'));
				} else 
				{
					Session::instance()->set('e_mess', $post->errors('Valid_mess'));
					$this->redirect('parking');
				}
		
			break;
			
		
			case 'del_grz_from_parking'://удалить ГРЗ из парковки
				//echo Debug::vars('95', $_POST); exit;
				$post=Validation::factory($this->request->post());
				$post->rule('grz_for_del', 'not_empty')
					->rule('id_parking', 'not_empty')
					->rule('id_parking', 'digit')
						
						;
				if($post->check())
				{
					Model::factory('parking')->del_grz($post);
					Session::instance()->set('ok_mess', array('ok_mess' => Arr::get($post,'grz_for_del'). ' удален из списка inside успешно'));
					$this->redirect('parking/load/'.Arr::get($post,'id_parking'));
				} else 
				{
					Session::instance()->set('e_mess', $post->errors('Valid_mess'));
					$this->redirect('parking');
				}
		
			break;
			
			default:
				//echo Debug::vars('95 controller parking', $_GET, $_POST); exit;
				$this->redirect('/');
			break;
		}
		$content='';
        $this->template->content = $content;
		
	}
	
	public function action_edit()//редактировать и просматривать  парковку
	{
		$_SESSION['menu_active']='kp_park_menu';
		//echo Debug::vars('43', $_GET, $_POST, $this->request->param('id')); exit;
		$id_parking = $this->request->param('id');
		$info_parking=Model::Factory('rubic')->get_info_parking($id_parking); //получить общую информацию о парковке
		
		
		//echo Debug::vars('45', $door_list, $id_rubic); exit;
		
		$content = View::factory('rubic/edit_parking', array(
			'rubic_getinfo'=>$info_parking,
			//'list_key_into_parking' => $list_key_into_parking,
			//'apb_device_list'=>$apb_device_list,
			//'door_list'=>$door_list,
		));
        $this->template->content = $content;
		
	}
	
	public function action_load()//просмотр списка автомобилей на парковке
	{
		$_SESSION['menu_active']='kp_park_menu';
		//echo Debug::vars('43', $_GET, $_POST, $this->request->param('id')); exit;
		$id_parking = $this->request->param('id');
		$load_list_parking=Model::Factory('parking')->get_load($id_parking); //получить список ГРЗ на парковке
		$info_parking=Model::Factory('rubic')->get_info_parking($id_parking); //получить общую информацию о парковке
	
		//echo Debug::vars('133', $load_list_parking); exit;
		
		$content = View::factory('rubic/parking_load', array(
			'load_list_parking'=>$load_list_parking,
			'info_parking' => $info_parking,
		));
        $this->template->content = $content;
		
	}
	
	
} 
