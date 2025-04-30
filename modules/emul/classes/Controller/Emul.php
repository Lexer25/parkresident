<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Emul extends Controller_Template { // класс для программной эмуляции работы парковочной системы
//предполагаю в нем имитировать сигналы от обрудования и наблюдать реакцию парковочной системы 
	
	
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
		
		
		$content = View::factory('emul/emul', array(
			
			
		
		));
        $this->template->content = $content;
	}
	
	
	
	
	//отправка http post запроса эмуляция работы cvs
	 public function action_sendGRZ()
	 {	
	$data=json_encode(array (
			'camera' => 44,
			'channel' => 3,
			'count' => 16,
			'dateTime' => '20250429T141918Z',
			'description' =>'---',
			'direction' => 0,
			'groupId' => -1,
			'id' => 624101,
			'image' =>  '/9j/4AAQSkZJRgABAQAAAQABAAD//gALQ1ZTIMDi8u4r/9sAQwAGBAUGBQQGBgUGBwcGCAoQCgoJCQoUDg8MEBcUGBgXFBYWGh0lHxobIxwWFiAsICMmJykqKRkfLTAtKDAlKCko/9sAQwEHBwcKCAoTCgoTKBoWGigoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgo/8QBogAAAQUBAQEBAQEAAAAAAAAAAAECAwQFBgcICQoLEAACAQMDAgQDBQUEBAAAAX0BAgMABBEFEiExQQYTUWEHInEUMoGRoQgjQrHBFVLR8CQzYnKCCQoWFxgZGiUmJygpKjQ1Njc4OTpDREVGR0hJSlNUVVZXWFlaY2RlZmdoaWpzdHV2d3h5eoOEhYaHiImKkpOUlZaXmJmaoqOkpaanqKmqsrO0tba3uLm6wsPExcbHyMnK0tPU1dbX2Nna4eLj5OXm5+jp6vHy8/T19vf4+foBAAMBAQEBAQEBAQEAAAAAAAABAgMEBQYHCAkKCxEAAgECBAQDBAcFBAQAAQJ3AAECAxEEBSExBhJBUQdhcRMiMoEIFEKRobHBCSMzUvAVYnLRChYkNOEl8RcYGRomJygpKjU2Nzg5OkNERUZHSElKU1RVVldYWVpjZGVmZ2hpanN0dXZ3eHl6goOEhYaHiImKkpOUlZaXmJmaoqOkpaanqKmqsrO0tba3uLm6wsPExcbHyMnK0tPU1dbX2Nna4uPk5ebn6Onq8vP09fb3+Pn6/8AAEQgC0AUAAwEhAAIRAQMRAf/aAAwDAQACEQMRAD8A8zUs7okcckkkjrGiRoXZmY4CgDkkkgYFd5p/hfwv4X0CHUvic1xc6nfqj2mg2d0wlEciqcumI2DqRIGLNsABHLYoAx4/FwaJGk8HeAVcqMr/AGGDg46ZEnP1ro/BcHhDx3eyaVrmm22heIZnC2MuhQPawzRKpkI27nj3jbJnzBypGM9gDK+HOh2H/CY6v4V8&nbsp;&hellip;',
			'inList' => 0,
			'passed' => 1,
			'plate' => 'C023CA797',
			'quality' =>  '555555555000',
			'stayTimeMinutes' => 0,
			'type' => 0,
			'weight' => 0
			));
		$this->sendRequestPostJson($data, 'dashboard/exec');
		$this->redirect('emul');
	 }
	 
	 
	 //отправка http post запроса эмуляция работы МПТ UHF
	 public function action_sendUHF()
	 {	
	$data=json_encode(array (
			'key' => '1234FEDC',
			'ip'=>'192.168.0.100',
			'channel'=>0
			
			));
		$this->sendRequestPostJson($data, 'dashboard/sendMPT');
		$this->redirect('emul');
	 }
	 
	 
	 
	 
		
		
	//Отправк POST запроса. Данные должны быть в формате json
	public function sendRequestPostJson($data, $url)
	{
			$request = Request::factory('http://localhost/cvs/'.$url)
					->headers("Accept", "application/json")
					->headers("Content-Type", "application/json")
					//->headers("Authorization", 'Bearer '.$this->tokenTTT)
					->method('POST')
					->body($data)
					->execute();
			return $request->body();
	} 
}