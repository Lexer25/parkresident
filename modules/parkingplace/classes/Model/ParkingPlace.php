<?php defined('SYSPATH') OR die('No direct access allowed.');

class Model_ParkingPlace extends Model {
	
	
	public function get_list()// получить список всех парковочных площадок
	{
		$res=array();
				
		$sql='select hlr.id from hl_parking hlr';
		
		
		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();
	//echo Debug::vars('11',$sql, $query); exit;
		
		return $query;	
	}
	
	public function get_list_for_parent($parent)// получить список парковочных площадок для указанного родителя
	{
		$res=array();
				
		$sql='select hlr.id from hl_parking hlr
		where hlr.parent='.$parent;
		
		
		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();
	//echo Debug::vars('11',$sql, $query); exit;
		
		return $query;	
	}
	
	
	
}
