<?php defined('SYSPATH') OR die('No direct access allowed.');

class Model_ResidentPlace extends Model {
	
	
	public function get_list()// получить список жилых комплексов
	{
		$res=array();
				
		$sql='select hlr.id from hl_resident hlr';
		
		
		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();
	//echo Debug::vars('11',$sql, $query); exit;
		
		return $query;	
	}
	
	
	
	public function getResidentPlaceSelect($id_rp)// вспомогательная функция для построения select жилых комлексов.
	{
		$sql='select hlp.id, hlp.name from hl_parking hlp';
	//echo Debug::vars('8', $sql);
		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();
		
		return $query;	
		
	}
}
