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
		//echo Debug::vars('12', $sql); exit;
		
		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();
		return $query;	
	}
	
	
	
	
}
