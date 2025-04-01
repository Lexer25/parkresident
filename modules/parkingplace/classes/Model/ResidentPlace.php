<?php defined('SYSPATH') OR die('No direct access allowed.');

class Model_ResidentPlace extends Model {
	
	
	public function get_list_rp()// получить список жилых комплексов
	{
		$res=array();
				
		$sql='select hlr.id from hl_resident hlr';
		
		
		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();
	//echo Debug::vars('11',$sql, $query); exit;
		
		return $query;	
	}
	
	
	public function getResidentPlaceInfo($id_rp)// получить информацию о жилом комплексе
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
	

	public function _add_rp($rp_name) //добавление нового жилого комплекса
	{
		//echo Debug::vars('783', $rp_name);exit;
		if($rp_name === NULL) $rp_name=$this->getOrgName($id_org);
		$sql='INSERT INTO HL_PARKING (NAME, PARENT)
			values (\''.$rp_name.'\', 0)';
			
		$sql='INSERT INTO HL_resident (NAME)
			values (\''.$rp_name.'\')';
			
			
		//echo Debug::vars('783', $sql);exit;
		try
				{
				$query = DB::query(Database::INSERT, iconv('UTF-8','windows-1251',$sql))
				->execute(Database::instance('fb'));
				} catch (Exception $e) {
					Log::instance()->add(Log::NOTICE, '794 '. $e->getMessage());
				}
		return;
	}

	
	public function del_rp($id_rp)// удаление жилого комплекса
	{
		
		$sql='delete from hl_parking hlp where hlp.id='.$id_rp;
		//echo Debug::vars('806', $sql);exit;
		try
				{
				$query = DB::query(Database::UPDATE, $sql)
					->execute(Database::instance('fb'));
				} catch (Exception $e) {
					Log::instance()->add(Log::NOTICE, '811 '. $e->getMessage());
				}
		return;
	}
	
	public function update_rp($data)// удаление жилого комплекса
	{
		//echo Debug::vars('62', $data, Arr::get($data, 'id_rp'), Arr::get($data, 'name'));exit;
		$sql='UPDATE HL_PARKING
			SET NAME = \''.Arr::get($data, 'name').'\',
				MAXCOUNT = \''.Arr::get($data, 'maxcount', 0).'\',
				ENABLED = 1
				WHERE (ID = '.Arr::get($data, 'id_rp').')';
			//echo Debug::vars('95', $sql);exit;
		try
				{
				$query = DB::query(Database::UPDATE, iconv('UTF-8','windows-1251',$sql))
					->execute(Database::instance('fb'));
					
				} catch (Exception $e) {
					Log::instance()->add(Log::NOTICE, '102 '. $e->getMessage());
				}
		return;
	}
	
	
	
}
