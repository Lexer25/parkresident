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
	
	
	
	public function _getResidentPlaceInfo($id_rp)// получить информацию о жилом комплексе
	{
		$sql='select hlp.id, hlp.name, hlp.enabled, hlp.created, hlp.maxcount, hlp.parent from hl_parking hlp where hlp.id='. $id_rp;
	//echo Debug::vars('8', $sql);
		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();
		
		Foreach ($query as $key => $value)
		{
			$res['ID']=Arr::get(Arr::get($query, 0), 'ID');
			$res['NAME']=iconv('windows-1251','UTF-8',Arr::get(Arr::get($query, 0), 'NAME'));
			$res['AS_ACTIVE']=Arr::get(Arr::get($query, 0), 'ENABLED');
		}
		
		return Arr::flatten($query);	
		
	}
}
