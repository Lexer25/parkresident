<?php defined('SYSPATH') or die('No direct script access.');
/**
* @package    ParkResident/Application
 * @category   Base
 * @author     Artonit
 * @copyright  (c) 2025 Artonit Team
 * @license    http://artonit/ru 
 
 */

class Controller_Welcome extends Controller {

	public function action_index()
	{
		$t1=microtime(1);
		$sql='select gen_id(gen_event_id,0)
					from RDB$DATABASE';
		$id_event = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->get('GEN_ID');
		
		$output=array(1=>2, 3=>4);
		
		$this->response->body(json_encode($id_event));
		//$this->response->body(json_encode($output));
	}
	
	
	public function action_getidevent()
	{
		$t1=microtime(1);
		$sql='select gen_id(gen_event_id,0)
			from RDB$DATABASE';
		$id_event = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->get('GEN_ID');
		
		
		
		$this->response->headers("Access-Control-Allow-Origin", "*");
		$this->response->headers("Content-Type", "application/json");
		$this->response->body(json_encode(array('GEN_ID'=>$id_event)));
		
	}
	
	
	public function action_geteventfrom()
	{
		$t1=microtime(1);
		$id = $this->request->param('id');
		//$id=3209720;
		Log::instance()->add(Log::DEBUG, '57 запрос события '. $id);
		$event=new Eventonline($id);
		Log::instance()->add(Log::DEBUG, '59 ответ на запрос '.$id.' '.Debug::vars(json_encode(get_object_vars($event))));
		//echo Debug::vars('26', $event, json_encode(get_object_vars($event))); exit;
		$this->response->headers("Access-Control-Allow-Origin", "*");
		$this->response->headers("Content-Type", "application/json");
		$this->response->body(json_encode(get_object_vars($event)));
		
	}
	
	/**
	информация по контакту
	*/
	public function action_getContactInfo()
	{
		$t1=microtime(1);
		$id = $this->request->param('id');
		$id=7582;//фиксированное значение для отладки
		//$id=3209720;
		Log::instance()->add(Log::DEBUG, '57 запрос события '. $id);
		$event=new Contact($id);
		Log::instance()->add(Log::DEBUG, '59 ответ на запрос '.$id.' '.Debug::vars(json_encode(get_object_vars($event))));
		//echo Debug::vars('26', $event, json_encode(get_object_vars($event))); exit;
		$this->response->headers("Access-Control-Allow-Origin", "*");
		$this->response->headers("Content-Type", "application/json");
		$this->response->body(json_encode(get_object_vars($event)));
		
	}
	
	
} // End Welcome
