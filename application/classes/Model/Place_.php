<?php defined('SYSPATH') OR die('No direct access allowed.');

class Model_Place extends Model {
	
	
	/*
	29.06.2023	проверка наличия id_place в базе данных.
	*/
	public static function unique_place ($placenumber) 
	{
				// Check if the id_org already exists in the database
	//$sql='select ID_ORG from ORGANIZATION where ID_ORG='.$id_org;
	$sql='select hlp.placenumber from hl_place hlp where hlp.placenumber='.$placenumber;
	return ! DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->get('PLACENUMBER');
	}
	
	/*
	29.06.2023	Проверка, что номер машиноместа не превышает количестов машиномест.
	27.12.2024 на момент проверки не хватает информации о том, какаой это паркинг. Этих данных нет, однако.
	*/
	public static function maxcount_place ($placenumber) 
	{
				
	$sql='select hlpr2.maxcount from hl_place hlpl
		join hl_parking hlpr on hlpr.id=hlpl.id_parking
		join hl_parking hlpr2 on hlpr.parent=hlpr2.id
		where hlpl.placenumber='.$placenumber;
	$sql='select hlp.maxcount from hl_parking hlp
where hlp.id=14';
	
	return ($placenumber < DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->get('MAXCOUNT'));
			
			
	}
	
	
	public function get_list_grz($id_parking)// получить список всех ГРЗ , кто может пользоваться парковкой
	{
		$res=array();
		$sql='select hlp.id_dev, hlp.name as gate_name, hlp.id_parking, hlpr.name as name_parking, ssa.id_accessname, an.name as name_access, c.id_card, c.id_pep, c.timestart, c.timeend, c.note, c."ACTIVE" as is_active, c.id_cardtype from hl_param hlp
join access ac on ac.id_dev=hlp.id_dev
			join accessname an on an.id_accessname=ac.id_accessname
			join ss_accessuser ssa on ssa.id_accessname=ac.id_accessname
			join card c on c.id_pep=ssa.id_pep and c.id_cardtype=4
			left join hl_parking hlpr on hlpr.id=hlp.id_parking';
		//echo Debug::vars('12', $sql); exit;
		
		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();
		//echo Debug::vars('11', $query); exit;
		foreach ($query as $key => $value)
		{
			$res[$key]['ID_DEV']=Arr::get($value, 'ID_DEV');
			$res[$key]['ID_PARKING']=Arr::get($value, 'ID_PARKING');
			$res[$key]['ID_ORG']=Arr::get($value,'ID_ORG');
			$res[$key]['NAME_PARKINGNAME_PARKING']=iconv('windows-1251','UTF-8',Arr::get($value,'NAME_PARKING'));
			$res[$key]['ID_ACCESSNAME']=iconv('windows-1251','UTF-8',Arr::get($value,'ID_ACCESSNAME'));
			$res[$key]['NAME_ACCESS']=iconv('windows-1251','UTF-8',Arr::get($value,'NAME_ACCESS'));
			$res[$key]['NOTE']=iconv('windows-1251','UTF-8',Arr::get($value,'NOTE'));
			$res[$key]['GATE_NAME']=iconv('windows-1251','UTF-8',Arr::get($value,'GATE_NAME'));
			$res[$key]['ID_CARD']=Arr::get($value,'ID_CARD');
			$res[$key]['ID_PEP']=Arr::get($value,'ID_PEP');
			$res[$key]['TIMESTART']=Arr::get($value,'TIMESTART');
			$res[$key]['TIMEEND']=Arr::get($value,'TIMEEND');
			$res[$key]['IS_ACTIVE']=Arr::get($value,'IS_ACTIVE');
			$res[$key]['ID_CARDTYPE']=Arr::get($value,'ID_CARDTYPE');
		}
		return $res;	
	}
	
	/*
	Добавление нового машиноместа
	$new_place_name - название машиноместа
	$id_parking = 1 id парковки
	*/
	public function add_new_place($new_place_number, $id_parking = 1)
	{
		
		if(!is_null($new_place_number))
		{
			
				//$sql='INSERT INTO HL_PLACE (NAME, ID_PARKING) VALUES (\''.iconv('UTF-8', 'windows-1251',$new_place_name).'\', '.$id_parking.')';
				$sql='INSERT INTO HL_PLACE (PLACENUMBER, ID_PARKING) VALUES ('.$new_place_number.', '.$id_parking.')';
				//echo Debug::vars('53', $sql, $new_place_name); exit;
				try
				{
					$query = DB::query(Database::INSERT, $sql)
					->execute(Database::instance('fb'));
				} catch (Exception $e) {
					echo Debug::vars('60', $e->getMessage()); exit;
					Log::instance()->add(Log::ERROR, $e->getMessage());
					
				}
		
		}
		return;
		
	}
	
	
}
