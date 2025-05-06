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
		
		$_SESSION['menu_active']='grz';
		$t1=microtime(true);//отмека времени для оценки быстродействия	
		//echo Debug::vars('38', $_GET, $_POST, $id_parking); //exit;
		$getGrzInfo=array_slice(Model::Factory('grz')->getGrzInfoList(), 0, 1000);//список ГРЗ
		$garageList=array_slice(Model::Factory('Garage')->get_list_garage(), 0, 1000);//список ГРЗ
		//echo Debug::vars('57', $garageList);exit;
		$content = View::factory('emul/grzList', array(
			'grz_list'=>$getGrzInfo,
			'garageList'=>$garageList,
			't1'=>$t1,
			));
        $this->template->content = $content;
	}
	
	
	
	
	//отправка http post запроса эмуляция работы cvs
	 public function action_sendGRZ()
	 {	
		//echo Debug::vars('44', $_POST);exit;
		
		//$cam=Arr::get($_POST, 'card');
		$cam=Arr::get(Model::factory('Gates')->get_info_gate(Arr::get($_POST, 'gate')), 'id_cam');
		$grz=Arr::get($_POST, 'card');
		//echo Debug::vars('78', $cam, $grz);exit;
		$data=json_encode(array (
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
			'weight' => 0
			));
			//echo Debug::vars('98', $data);exit;
		$this->sendRequestPostJson($data, 'dashboard/exec');
		$this->redirect('emul/grz');
	 }
	 
	 
	 //отправка http post запроса эмуляция работы МПТ UHF
	 public function action_sendUHF()
	 {	
		//echo Debug::vars('71', $_POST);//exit;
		//echo Debug::vars('108', Model::factory('Gates')->get_info_gate(Arr::get($_POST, 'gate')));exit;
		$gate=Model::factory('Gates')->get_info_gate(Arr::get($_POST, 'gate'));
		
		
	$data=json_encode(array (
			'key' => Arr::get($_POST, 'card'),
			'ip'=>Arr::get($gate, 'box_ip'),
			'channel'=>Arr::get($gate,'channel'),
			
			
			));
		$this->sendRequestPostJson($data, 'dashboard/sendMPT');
		$this->redirect('emul/grz');
	 }
	 
	 
	 
	 
		
		
	//Отправк POST запроса. Данные должны быть в формате json
	public function sendRequestPostJson($data, $url)
	{
			$request = Request::factory('http://localhost/cvs/'.$url)
					->headers("Accept", "application/json")
					->headers("Content-Type", "application/json")
					->method('POST')
					->body($data)
					->execute();
			return $request->body();
	} 
}
