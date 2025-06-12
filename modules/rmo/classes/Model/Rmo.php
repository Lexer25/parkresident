<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
* @package    ParkResident/Rmo
 * @category   Base
 * @author     Artonit
 * @copyright  (c) 2025 Artonit Team
 * @license    http://artonit/ru 
 
 */

class Model_Rmo extends Model {
	
	private $requestPort=80;
	//Команда на открывание ворот. Отправляется в другую систему 
	public function sendOpen($id_gate)
	{
		echo Debug::vars('17 готовлю отправку команды на открытие ворот '.$id_gate);//exit;
		$data=array (
				'id' => $id_gate,
				);
			//	echo Debug::vars('138', $data); exit;
			$answer=$this->sendRequestPostJson($data, 'dashboard/opengate');
			Log::instance()->add(Log::NOTICE, '142 '. Debug::vars($answer));
			//echo Debug::vars('41 получил ответ на открытие ворот', $answer, is_object($answer));exit;
			 if(is_object($answer))
			{
				if($answer->result=='OK') 
				{
					return array('result'=>true);
				} else {
					Log::instance()->add(Log::NOTICE, '144 ворота не открылись. Причина: '. $answer->edesc);
					return array('result'=>false, 'edesc'=>$answer->edesc);
				}

			}	else {
				Log::instance()->add(Log::NOTICE, '146 ворота Не открылись, смотри ошибку в логах.');
				Log::instance()->add(Log::NOTICE, '148 '. Debug::vars($answer));
				return array('result'=>false, 'edesc'=>$answer->edesc);
			}				
			
			
			
		
	}
	
	
	//Отправк POST запроса. Данные должны быть в формате json
	public function sendRequestPostJson($data, $url)
	{
			 Log::instance()->add(Log::NOTICE, '153 отправлен тестовый запрос на адрес http://localhost:'.$this->requestPort.'/cvs/'. $url);
			 Log::instance()->add(Log::NOTICE, '154 '.Debug::vars($data));
			
			$request = Request::factory('http://localhost:'.$this->requestPort.'/cvs/'. $url)
					
					->method(Request::POST)
					
					//->body($data)
					->post($data);
			
			try{
				//echo Debug::vars('166', $request->execute());exit;
				$response=$request->execute();
				Log::instance()->add(Log::NOTICE, '168 ответ sendRequestPostJson'.Debug::vars($response));
				$answer=json_decode($response->body());
				Log::instance()->add(Log::NOTICE, '169 ответ sendRequestPostJson'.Debug::vars($response->body()));
				Log::instance()->add(Log::NOTICE, '171 ответ sendRequestPostJson'.Debug::vars($answer));
				return $answer;
			} catch (Exception $e) {
			Log::instance()->add(Log::DEBUG, '#31 '.$e->getMessage());
			//echo Debug::vars('171', $e->getMessage());exit;
			return false;

		}	
			
			//return $request->body();
	} 
	
	//Отправк POST запроса через CURL. Данные должны быть в формате json
	public function _sendRequestPostJson($data, $url)
	{
			 Log::instance()->add(Log::NOTICE, '153 отправлен тестовый запрос на адрес http://localhost:'.$this->requestPort.'/cvs/'. $url);
			 Log::instance()->add(Log::NOTICE, '154 '.Debug::vars($data));
			
			$response = Request_Curl::post('http://localhost:'.$this->requestPort.'/cvs/'. $url, 
				$data, 
				array('Content-Type: application/json')
			);
			
		Log::instance()->add(Log::NOTICE, '91 ответ sendRequestPostJson'.Debug::vars($response));
				echo Debug::vars('92', $response);exit;

				Log::instance()->add(Log::NOTICE, '168 ответ sendRequestPostJson'.Debug::vars($response));
				$answer=json_decode($response->body());
				Log::instance()->add(Log::NOTICE, '169 ответ sendRequestPostJson'.Debug::vars($response->body()));
				Log::instance()->add(Log::NOTICE, '171 ответ sendRequestPostJson'.Debug::vars($answer));
				return $answer;
		
			
			//return $request->body();
	} 
	
	
}
