<?php defined('SYSPATH') or die('No direct script access.');
/**
* @package    ParkResident/Emul
 * @category   Base
 * @author     Artonit
 * @copyright  (c) 2025 Artonit Team
 * @license    http://artonit/ru 
 
 */
class Controller_Emul extends Controller_Template { // класс для программной эмуляции работы парковочной системы
//предполагаю в нем имитировать сигналы от обрудования и наблюдать реакцию парковочной системы 
	
	
	public $template = 'templateWidth';
	private $requestPort=8080;
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
			$this->requestPort = Kohana::$config->load('emul_config')->get('requestPort', 80);

	}
	
	
	public function action_index()//Показываю список машиномест для указанных паркингов
	{
		
		$content = View::factory('emul/emul', array(
		));
        $this->template->content = $content;
	}
	
	public function action_test()//
	{
		//echo Debug::vars('43', $_GET, $_POST);exit;
		$content = View::factory('emul/emul', array(
		));
        $this->template->content = $content;
	}
	
	
	public function action_grz()//
	{
		$t1=microtime(true);//отмека времени для оценки быстродействия	
		//echo Debug::vars('38', $_GET, $_POST); exit;
		$getGrzInfo=array_slice(Model::Factory('grz')->getGrzInfoList(), 0, 1000);//список ГРЗ
		$garageList=array_slice(Model::Factory('Garage')->get_list_garage(), 0, 1000);//список ГРЗ
		//echo Debug::vars('57', $garageList);exit;
		$content = View::factory('emul/grzList', array(
			'grz_list'=>$getGrzInfo,
			'garageList'=>$garageList,
			't1'=>$t1,
			));
        $this->template->content = $content;
		//echo View::factory('profiler/stats');
	}
	
	
	
	
	//отправка http post запроса эмуляция работы cvs
	 public function action_sendGRZ()
	 {	
		
		//Log::instance()->add(Log::NOTICE, '75 '.Debug::vars($_POST));
		//echo Debug::vars('74', $_POST);exit;
		//$cam=Arr::get($_POST, 'card');
		$cam=Arr::get(Model::factory('Gates')->get_info_gate(Arr::get($_POST, 'gate')), 'id_cam');
		$grz=Arr::get($_POST, 'card');
		//echo Debug::vars('78', $cam, $grz);exit;
		$data_0=array (
			'camera' => $cam,
			'channel' => 3,
			'count' => 16,
			'dateTime' => '20250429T141918Z',
			'description' =>'---',
			'direction' => 0,
			'groupId' => -1,
			'id' => 624101,
			//'image' =>  '/9j/4AAQSkZJRgABAQAAAQABAAD//gALQ1ZTIMDi8u4r/9sAQwAGBAUGBQQGBgUGBwcGCAoQCgoJCQoUDg8MEBcUGBgXFBYWGh0lHxobIxwWFiAsICMmJykqKRkfLTAtKDAlKCko/9sAQwEHBwcKCAoTCgoTKBoWGigoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgo/8QBogAAAQUBAQEBAQEAAAAAAAAAAAECAwQFBgcICQoLEAACAQMDAgQDBQUEBAAAAX0BAgMABBEFEiExQQYTUWEHInEUMoGRoQgjQrHBFVLR8CQzYnKCCQoWFxgZGiUmJygpKjQ1Njc4OTpDREVGR0hJSlNUVVZXWFlaY2RlZmdoaWpzdHV2d3h5eoOEhYaHiImKkpOUlZaXmJmaoqOkpaanqKmqsrO0tba3uLm6wsPExcbHyMnK0tPU1dbX2Nna4eLj5OXm5+jp6vHy8/T19vf4+foBAAMBAQEBAQEBAQEAAAAAAAABAgMEBQYHCAkKCxEAAgECBAQDBAcFBAQAAQJ3AAECAxEEBSExBhJBUQdhcRMiMoEIFEKRobHBCSMzUvAVYnLRChYkNOEl8RcYGRomJygpKjU2Nzg5OkNERUZHSElKU1RVVldYWVpjZGVmZ2hpanN0dXZ3eHl6goOEhYaHiImKkpOUlZaXmJmaoqOkpaanqKmqsrO0tba3uLm6wsPExcbHyMnK0tPU1dbX2Nna4uPk5ebn6Onq8vP09fb3+Pn6/8AAEQgC0AUAAwEhAAIRAQMRAf/aAAwDAQACEQMRAD8A8zUs7okcckkkjrGiRoXZmY4CgDkkkgYFd5p/hfwv4X0CHUvic1xc6nfqj2mg2d0wlEciqcumI2DqRIGLNsABHLYoAx4/FwaJGk8HeAVcqMr/AGGDg46ZEnP1ro/BcHhDx3eyaVrmm22heIZnC2MuhQPawzRKpkI27nj3jbJnzBypGM9gDK+HOh2H/CY6v4V8&nbsp;&hellip;',
			'inList' => 0,
			'passed' => 1,
			//'plate' => 'C023CA797',
			'plate' => $grz,
			'quality' =>  '555555555000',
			'stayTimeMinutes' => 0,
			'type' => 0,
			'weight' => 0,
			'test' => 1
			);
			//echo Debug::vars('98', $data);exit;
			
			
		//$data=json_encode($data_0);
		//$this->sendRequestPostJson(Array('plate'=>$data), 'dashboard/exec');
		Model::factory('Emul')->sendRequestPostJson(Array('plate'=>$data_0), 'dashboard/exec', 'body');
		
		$this->redirect('emul/grz');
	 }
	 
	 
	 //отправка http post запроса эмуляция работы МПТ UHF
	 public function action_sendUHF()
	 {	
		//echo Debug::vars('71', $_POST);exit;
		$gate=Model::factory('Gates')->get_info_gate(Arr::get($_POST, 'gate'));
		
		//в режиме ТЕСТ добавляю данные IP и PORT из базы данных. В реальных условиях эти данные будут извлекаться из запроса от контроллера.
		$data_0=array (
				'key' => strtoupper(dechex(Arr::get($_POST, 'card'))),
				'test' => Arr::get($_POST, 'test'),
				'ip'=>Arr::get($gate, 'box_ip'),
				'ch'=>Arr::get($gate,'channel'),
				);
			
	//$data=json_encode($data_0);
			
		//$this->sendRequestPostJson($data_0, 'dashboard/sendMPT');
		Model::factory('Emul')->sendRequestPostJson($data_0, 'dashboard/sendMPT', 'post');
		$this->redirect('emul/grz');

	 }
	 
	 
	  //отправка http post запроса на открытие двери
	  //Запрос отправляется в другую систему, которая управляет реальным оборудованием.
	  //ответ передается назад, в ответе на request
	 public function action_sendOpen()
	 {	
		//echo Debug::vars('136', $_POST);exit;
		
		/* $data=array (
				'id' => Arr::get($_POST, 'id'),
				);
			//	echo Debug::vars('138', $data); exit;
			$answer=$this->sendRequestPostJson($data, 'dashboard/opengate');
			Log::instance()->add(Log::NOTICE, '142 '. Debug::vars($answer));
			 if($answer)
			{
				if($answer->result=='OK') 
				{
					Log::instance()->add(Log::NOTICE, '143 ворота открылись, все в порядке.');
				} else {
					Log::instance()->add(Log::NOTICE, '144 ворота не открылись. Причина: '. $answer->edesc);
				}

			}	else {
				Log::instance()->add(Log::NOTICE, '146 ворота Не открылись, смотри ошибку в логах.');
				Log::instance()->add(Log::NOTICE, '148 '. Debug::vars($answer));
			}				
			 
			
			//$this->redirect('emul/grz');
			
			
			$this->response
            ->headers('Content-Type', 'application/json')
            ->body(json_encode($answer)); */
			
			//===================
			
			
			$result=Model::factory('Emul')->sendOpen(Arr::get($_POST, 'id'));//открыть указанные ворота
			echo Debug::vars('169', $result);exit;
			
			$this->redirect($this->request->referrer());
	 }
	 
	 
	 
	 
		
		
	
}
