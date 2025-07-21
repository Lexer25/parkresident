<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
* @package    ParkResident/Emul
 * @category   Base
 * @author     Artonit
 * @copyright  (c) 2025 Artonit Team
 * @license    http://artonit/ru 
 
 */

class Model_Emul extends Model {
	
	Public $requestPort;
	
	 public function __construct()
	 {
		  $this->requestPort = Setting::get('port_cvs', 80);
		 
	 }
	public function get_list()// получить список машиномест
	{
		$res=array();
				
		$sql='select hlr.id from hl_resident hlr';
		
		
		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();
	//echo Debug::vars('11',$sql, $query); exit;
		
		return $query;	
	}
	
	// получить список машиномест для указанного parent
	public function getListForParent($parentList)
	{
		$res=array();
		//echo Debug::vars('25', $parentList);//exit;
		Foreach($parentList as $key=>$value)
		{
			$_res=$this->getChild(Arr::get($value, 'ID'));
			//echo Debug::vars('29', $_res);exit;
			if($_res) array_push($res, $_res);
			
		}
		//echo Debug::vars('31', $res);exit;
		return $res;
	}
	
	// получить список id машиномест для указанной парковки
	public function getChild($parent)
	{
		$sql='select hlp.id from hl_place hlp
			where hlp.id_parking='.$parent;
		//echo Debug::vars('41', $sql);//exit;
		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();
		return $query;
	}
	
	
	// получить id всех машиномест
	public function getAll()
	{
		$sql='select hlp.id from hl_place hlp';
			
		//echo Debug::vars('41', $sql);//exit;
		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();
		return $query;
	}
	
	
	
	
	/*11.04.2025 Проверка номера машиноместа в указанной парковке на уникальность
	*/	
	//public static function unique_numberPlace($placenumber, $id_parking)
	public static function unique_numberPlace($data)
		{
		//echo Debug::vars('68', $data);exit; 
		 // Check if the username already exists in the database
			$sql='select * from hl_place hlp
					where hlp.placenumber=2
					and hlp.id_parking=1';
			return ! DB::select(array(DB::expr('COUNT(id)'), 'total'))
				->from('hl_place')
				->where('placenumber', '=', $placenumber)
				->and_where('id_parking', '=', $id_parking)
				->execute()
				->get('total');
		}
	

		/*06.05.2025 Замена номера ворот на видеокамеру
	*/	
	//public static function unique_numberPlace($placenumber, $id_parking)
	public static function unique_numberPlace_2($data)
		{
		//echo Debug::vars('68', $data);exit; 
		 // Check if the username already exists in the database
			$sql='select * from hl_place hlp
					where hlp.placenumber=2
					and hlp.id_parking=1';
			return ! DB::select(array(DB::expr('COUNT(id)'), 'total'))
				->from('hl_place')
				->where('placenumber', '=', $placenumber)
				->and_where('id_parking', '=', $id_parking)
				->execute()
				->get('total');
		}
	

		
		
	/**02.05.2025 Получить список ГРЗ для работы в эмуляторе
	*/
	public function getListIdCard($id_cardtype)
	{
		$sql='select c.id_card from card c
		where c.id_cardtype='.$id_cardtype.'
		and c.id_card not containing \' \'';
		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();
		return $query;
		
	}
	
	
	/**02.05.2025 Получить список cam из настроект системы
	* с этим видеокамер могут приходить ГРЗ
	*/
	public function getListIdCam()
	{
		$sql='select hlp.id_cam from hl_param hlp';
		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();
		return $query;
		
	}
	
	
	
	/**02.05.2025 Получить список ворот
	* от этих ворот будут приходить коды UHF
	*/
	public function getListIdGate()
	{
		$sql='select hlp.id from hl_param hlp';
		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();
		return $query;
		
	}
	
	
	//Отправк POST запроса. Данные должны быть в формате array
	public function sendRequestPostJson($data, $url, $container='post')
	{
	
			Log::instance()->add(Log::NOTICE, '153 отправлен тестовый запрос на адрес http://localhost:'.$this->requestPort.'/cvs/'. $url);
			 Log::instance()->add(Log::NOTICE, '154 '.Debug::vars($data));
			 Log::instance()->add(Log::NOTICE, '154-1 '.Debug::vars($container));
			$request = Request::factory('http://localhost:'.$this->requestPort.'/cvs/'. $url)
					//->headers("Accept", "application/json")
					//->headers('Content-Type', 'application/json')
					//->headers("Accept", "application/json")
					//->headers("Content-Type", "application/x-www-form-urldecode")
					->method(Request::POST);
			switch($container)
			{
				case 'post':
							$request->post($data);
				break;
				case 'body':
							$request->body(json_encode($data));
				break;
				
			
			}
			try{
				//echo Debug::vars('166', $request->execute());exit;
				$response=$request->execute();
				Log::instance()->add(Log::NOTICE, '168 ответ sendRequestPostJson'.Debug::vars($response));
				$answer=json_decode($response->body());
				Log::instance()->add(Log::NOTICE, '169 ответ sendRequestPostJson'.Debug::vars($response->body()));
				Log::instance()->add(Log::NOTICE, '171 ответ sendRequestPostJson'.Debug::vars($answer));
				Session::instance()->set('ok_mess', array('desc'=>'Комнда выполнена успешно.'));
				return $answer;
			} catch (Exception $e) {
				Log::instance()->add(Log::DEBUG, '#181 '.$e->getMessage());
			//echo Debug::vars('171', $e->getMessage());exit;
			Session::instance()->set('e_mess', array('desc'=>$e->getMessage()));

			return false;

		}	
			
			//return $request->body();
	} 
	
	
	public function sendCurl($data, $url)
	{
			Log::instance()->add(Log::NOTICE, '193'.Debug::vars($data));
			$ch = curl_init('http://localhost:'.$this->requestPort.'/cvs/'. $url);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'Content-Type:  text/plain',
				'Content-Length: ' . strlen($data),
			));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			try{
				$response = curl_exec($ch);
				Log::instance()->add(Log::NOTICE, '203 ответ sendCurl'.Debug::vars($response));
				//$answer=json_decode($response->body());
				Log::instance()->add(Log::NOTICE, '205 ответ sendCurl'.Debug::vars($response));
				//Log::instance()->add(Log::NOTICE, '206 ответ sendCurl'.Debug::vars($answer));
			} catch(Exception $e) {
				Log::instance()->add(Log::DEBUG, '#208 '.$e->getMessage());
				curl_close($ch);
				return false;
			}
				curl_close($ch);
			return true;
			
		
	}
	
	
	//Команда на открывание ворот. Отправляется в другую систему 
	public function sendOpen($id_gate)
	{
		$data=array (
				'id' => $id_gate,
				);
			//	echo Debug::vars('138', $data); exit;
			$answer=$this->sendRequestPostJson($data, 'dashboard/opengate', 'post');
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
            ->body(json_encode($answer));
		
	}
	
	
	/**20.07.2025
	* список карт людей, не входящих в гаражи
	*/
	public function getListCardNonGarage($count=10, $type=1)
	{
		$sql='select first '.$count.' skip 25 distinct c.id_card, hli.counterid from hl_orgaccess hlo
				join people p on p.id_org<>hlo.id_org
				join card c on c.id_pep=p.id_pep
				left join hl_inside hli on hli.id_card=c.id_card
				where c.id_cardtype='.$type;
			
			$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();

		return $query;
		
	}
}
