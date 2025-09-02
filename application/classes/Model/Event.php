<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
* @package    ParkResident/Application
 * @category   Base
 * @author     Artonit
 * @copyright  (c) 2025 Artonit Team
 * @license    http://artonit/ru 
 
 */

class Model_Event extends Model {
	
	
	
	/*
	Получить список кодов событий и из названия для формирования фильтра
	
	*/
	public function get_events_name_list()
	{
		$res=array();
		$sql='select hle.id, hle.name, hle.color from hl_eventcode hle
			where hle.id in (3, 4, 5, 6, 46, 50, 65, 81)
			order by hle.id';
		//лист событий, которые не надо показывать с отчете. Номера событий настраиваются в файле конфигурации приложения	
			if(isset(Kohana::$config->load('artonitparking_config')->noViewEventsList))
			{
				$noViewEventsList=Kohana::$config->load('artonitparking_config')->noViewEventsList;
				$sql='select hle.id, hle.name, hle.color from hl_eventcode hle
				where hle.id not in ('.implode(",", $noViewEventsList).')
				order by hle.id';
			} else {
				
				$noViewEventsList=array();
				$sql='select hle.id, hle.name, hle.color from hl_eventcode hle
				order by hle.id';
			}
			
		
		//echo Debug::vars('12', $sql); exit;
		
		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();
		return $query;	
	}
	
	
	public function insertEvents()
	{
		$sql='INSERT INTO HL_EVENTS (
			ID,
			EVENT_CODE,
			EVENT_TIME,
			IS_ENTER,
			RUBI_CARD,
			PARK_CARD,
			GRZ,
			COMMENT,
			PHOTO,
			ID_PEP,
			ID_GATE,CREATED) 
			VALUES (
				25,
				6,
				\'17-MAY-2025 12:10:39\',
				NULL,
				NULL,
				NULL,
				\'1922384\',
				NULL,
				NULL,
				2413,
				104,
				\'17-MAY-2025 12:10:39\'
				)';
		
	}
	
	
	
}
