<?php defined('SYSPATH') OR die('No direct access allowed.');

class Eventonline {
	
	public $id; //id_event
	public $timestamp; //метка времени
	public $eventname; //название события
	public $eventplace; //место события
	public $eventinfo; //информация по событию
	public $evencolor; //цвет строки
	public $id_pep; //id сотрудника
	public $id_org; //id организации 
	
	

	public function __construct($id)

	{
		$sql='SELECT first 2 eg.*,et.name as eventname, et.color FROM EVENTS_GETLISTFROMID(1, 1, '.$id.', NULL) eg
			join eventtype et on eg.id_eventtype=et.id_eventtype
			 where eg.id_dev in (97, 100, 106, 109)';
			//echo Debug::vars('18', $sql); exit;
		try{
		$id_event = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();
		//echo Debug::vars('24', $id_event); //exit;
		Log::instance()->add(Log::DEBUG, '25 Ответ на запрос но событию '.$id.' '. Debug::vars($sql, $id_event));
		
		//$output=Arr::flatten(Arr::get($id_event, 1));
		$output=Arr::flatten($id_event);
		
		//echo Debug::vars('26', $output); exit;
			
			
		$this->id=Arr::get($output, 'ID_EVENT');	
		$this->timestamp=Arr::get($output, 'DATETIME');	
		$this->eventname=iconv('CP1251','UTF-8', Arr::get($output, 'EVENTNAME'));	
		$this->eventinfo=iconv('CP1251','UTF-8', Arr::get($output, 'NOTE'));	
		$this->eventplace=iconv('CP1251','UTF-8', Arr::get($output, 'DEVICENAME'));	
		$this->evencolor=Arr::get($output, 'COLOR');	
		$this->id_pep=Arr::get($output, 'ESS1');	
		$this->id_org=Arr::get($output, 'ESS2');	
		
		} catch (Exception $e) {

			Log::instance()->add(Log::DEBUG, 'Line 36 '. $e->getMessage());
			//echo Debug::vars('37', $e->getMessage()); exit;
		}
			
	}
	
}
