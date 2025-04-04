<?php defined('SYSPATH') OR die('No direct access allowed.');

class Model_Place extends Model {
	
	
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
	
	// получить наследников
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
	
	
	// получить всех
	public function getAll()
	{
		$sql='select hlp.id from hl_place hlp';
			
		//echo Debug::vars('41', $sql);//exit;
		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();
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
