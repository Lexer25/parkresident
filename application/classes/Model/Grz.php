<?php defined('SYSPATH') OR die('No direct access allowed.');

class Model_Grz extends Model {
	
	public $eventcode_manual_out=4;
	public $id_cardtype=4;
	
	
	/*
	4.05.2023
	Получить историю по ГРЗ
	
	*/
	public function getHistory($grz)
	{
		$sql='select hle.id, hle.event_time, hle.event_code,hlec.color, hlec.name as eventName, hle.grz, hlp.name as gateName, hlp.is_enter from hl_events hle
			join hl_eventcode hlec on hlec.id=hle.event_code
			join hl_param hlp on hlp.id_dev=hle.id_gate
			where hle.grz=\''.$grz.'\'
			and hle.event_code not in (13)
			order by hle.id desc';
		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();
		//echo Debug::vars('20', $query); exit;
		return $query;
	}
	
	/*
	Получить информацию по всем ГРЗ
	
	*/
	public function getGrzInfoList()
	{
		$sql='select c.id_card from card c
		where c.id_cardtype='.$this->id_cardtype.'
		order by c.id_card';
		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();
		//echo Debug::vars('20', $query); exit;
		
		foreach ($query as $key=>$value)
		{
			$res[]=$this->getGrzInfo(Arr::get($value, 'ID_CARD'));
			
		}
		
		return $res;
	}
	
	
	/*
		информация о ГРЗ
		$grz - номер идентификатора
		$model - модель транспортнорго средства
		$idGarageName - номер гаража, куда приписан ГРЗ
		$idAccessName - номера категорий доступа
		$parking - номер и название парковок, куда может заезжать ГРЗ
	*/
	public function getGrzInfo($grz)
	{
		$sql='select c.id_card, c.id_pep, c.timestart, c.timeend, c.note, c.status, c."ACTIVE", c.flag, c.id_cardtype, p.name , p.surname as GRZ_MODEL, p.patronymic from card c
			join people p on p.id_pep=c.id_pep
			where c.id_card=\''.$grz.'\'';
		try
		{
		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();
			$res=Arr::get($query, 0);
		} catch (Exception $e) {
			Log::instance()->add(Log::ERROR, $e->getMessage());
			
		}
			
		//список категорий доступа для ГРЗ	
		$sql='select an.id_accessname, an.name from ss_accessuser ssa
				join accessname an on an.id_accessname=ssa.id_accessname
				where ssa.id_pep='.Arr::get($res, 'ID_PEP');
		try
		{
		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();
		$res['accessList']=$query;	
		} catch (Exception $e) {
			Log::instance()->add(Log::ERROR, $e->getMessage());
		}
		
		
		//список гаражей для ГРЗ
		$sql='select hlo.id_garage, hlo.is_active, hlg.name from hl_orgaccess hlo
				join people p on p.id_org=hlo.id_org
				join hl_garagename hlg on hlg.id=hlo.id_garage
				where p.id_pep='.Arr::get($res, 'ID_PEP');
				
		 $sql='select hlo.id_garage, hlo.is_active, hlg.name, hlgg.id_place, hlp.id_parking, hlpp.id as id_parking, hlpp.name as parking_name from hl_orgaccess hlo
                join people p on p.id_org=hlo.id_org
                join hl_garagename hlg on hlg.id=hlo.id_garage
                join hl_garage hlgg on hlgg.id_garagename=hlg.id
                join hl_place hlp on hlp.id=hlgg.id_place
                join hl_parking hlpp on hlp.id_parking=hlpp.id
                where p.id_pep='.Arr::get($res, 'ID_PEP');
				
				
			try
		{
		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();
		$res['garageList']=$query;	
		} catch (Exception $e) {
			Log::instance()->add(Log::ERROR, $e->getMessage());
		}
		
		
		//список парковок для ГРЗ
		
				
		 $sql='select distinct hlpp.id  as id_parking, hlpp.name as parking_name from hl_orgaccess hlo
                join people p on p.id_org=hlo.id_org
                join hl_garagename hlg on hlg.id=hlo.id_garage
                join hl_garage hlgg on hlgg.id_garagename=hlg.id
                join hl_place hlp on hlp.id=hlgg.id_place
                join hl_parking hlpp on hlp.id_parking=hlpp.id
                where p.id_pep='.Arr::get($res, 'ID_PEP');
				
				
			try
		{
		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();
		$res['parkingList']=$query;	
		} catch (Exception $e) {
			Log::instance()->add(Log::ERROR, $e->getMessage());
		}
		
		
		
		
		
		//список парковок, где находится ГРЗ
		$sql='select hli.entertime, hli.id_card, hli.counterid, hlp.name from hl_inside hli
		join hl_parking hlp on hlp.id=hli.counterid
		where hli.id_card=\''.$grz.'\'';
		try
		{
		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();
		//echo Debug::vars('116', $sql, $query, $res); exit;
		if($res!==NULL) $res['inParking']=$query;	
		} catch (Exception $e) {
			Log::instance()->add(Log::ERROR, $e->getMessage());
			
		}
		
		//echo Debug::vars('101', $sql, $query, $res); exit;
		return $res;
	}
	
	public function get_list_grz($id_parking)// получить список всех ГРЗ , кто может пользоваться парковкой
	{
		$res=array();
	
		
		$sql='select distinct hlp.id_parking,
            hlpr.name as name_parking,
            ssa.id_accessname,
            an.name as name_access,
            c.id_card, c.id_pep,
            c.timestart,
            c.timeend,
            c.note,
            c."ACTIVE" as is_active,
            c.id_cardtype,
            hli.entertime, 
            p.surname as grz_model,
            p.id_org   ,
            hlgn.id as id_garage,
            hlgn.name as garage_name
            from hl_param hlp
            join access ac on ac.id_dev=hlp.id_dev
            join accessname an on an.id_accessname=ac.id_accessname
            join ss_accessuser ssa on ssa.id_accessname=ac.id_accessname
            join card c on c.id_pep=ssa.id_pep and c.id_cardtype=4
            join people p on p.id_pep=c.id_pep
            left join hl_parking hlpr on hlpr.id=hlp.id_parking
            left join hl_inside hli on hli.id_card=c.id_card
            left join hl_orgaccess hlo on hlo.id_org=p.id_org
            left join hl_garagename hlgn on hlgn.id=hlo.id_garage';
		//echo Debug::vars('12', $sql); exit;
		
		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();
		//echo Debug::vars('11', $query); exit;
		foreach ($query as $key => $value)
		{
			$res[$key]['ID_DEV']=Arr::get($value, 'ID_DEV', -1);
			$res[$key]['ID_PARKING']=Arr::get($value, 'ID_PARKING');
			$res[$key]['ID_ORG']=Arr::get($value,'ID_ORG');
			$res[$key]['NAME_PARKINGNAME_PARKING']=iconv('windows-1251','UTF-8',Arr::get($value,'NAME_PARKING'));
			$res[$key]['ID_ACCESSNAME']=iconv('windows-1251','UTF-8',Arr::get($value,'ID_ACCESSNAME'));
			$res[$key]['NAME_ACCESS']=iconv('windows-1251','UTF-8',Arr::get($value,'NAME_ACCESS'));
			$res[$key]['GRZ_MODEL']=iconv('windows-1251','UTF-8',Arr::get($value,'GRZ_MODEL'));
			$res[$key]['NOTE']=iconv('windows-1251','UTF-8',Arr::get($value,'NOTE'));
			//$res[$key]['GATE_NAME']=iconv('windows-1251','UTF-8',Arr::get($value,'GATE_NAME'));
			$res[$key]['ID_CARD']=Arr::get($value,'ID_CARD');
			$res[$key]['ID_PEP']=Arr::get($value,'ID_PEP');
			$res[$key]['TIMESTART']=Arr::get($value,'TIMESTART');
			$res[$key]['TIMEEND']=Arr::get($value,'TIMEEND');
			$res[$key]['IS_ACTIVE']=Arr::get($value,'IS_ACTIVE');
			$res[$key]['ID_CARDTYPE']=Arr::get($value,'ID_CARDTYPE');
			$res[$key]['ENTERTIME']=Arr::get($value,'ENTERTIME');
			$res[$key]['ID_GARAGE']=Arr::get($value,'ID_GARAGE');
			$res[$key]['GARAGE_NAME']=iconv('windows-1251','UTF-8',Arr::get($value,'GARAGE_NAME'));
		}
		return $res;	
	}
	
	public function find_grz($grz)// поиск ГРЗ по цифрам
	{
		$sql='select * from card c
			where c.id_cardtype=4
			and c.id_card containing \''.$grz.'\'
			and c.id_cardtype=4';
			
		$sql='select distinct c.*, hlo.id_garage from card c
            join people p on p.id_pep=c.id_pep
            join ss_accessuser ssa on ssa.id_pep=c.id_pep
            join access a on a.id_accessname=ssa.id_accessname
            join hl_param hlp on hlp.id_dev=a.id_dev
            left join hl_orgaccess hlo on hlo.id_org=p.id_org
            where c.id_card containing \''.$grz.'\'
            and c.id_cardtype=4';
			
		$query = DB::query(Database::SELECT, $sql)
					->execute(Database::instance('fb'))
					->as_array();
		return $query;
	}
	
	public function get_id_garage_from_grz($grz)// получить ID гаража по ГРЗ
	{
		$sql='select distinct hlo.id_garage from card c
			join people p on p.id_pep=c.id_pep
			join ss_accessuser ssa on ssa.id_pep=c.id_pep
			join access a on a.id_accessname=ssa.id_accessname
			join hl_param hlp on hlp.id_dev=a.id_dev
			left join hl_orgaccess hlo on hlo.id_org=p.id_org
			where c.id_card = \''.$grz.'\'
			and c.id_cardtype=4';

		
	}
	
	
	/*
	Удаление ГРЗ из таблицы INSIDE
	
	*/
	public function delFromInside($grz)
	{
		$sql='delete from hl_inside hli
		where hli.id_card= \''.$grz.'\'';
		$query = DB::query(Database::DELETE, $sql)
		->execute(Database::instance('fb'));
	return;
	}
	
	/*
	Добавление ГРЗ в таблицу INSIDE
	
	*/
	public function addToInside($grz)
	{
		$this->delFromInside($grz);
		$sql='INSERT INTO HL_INSIDE (ID_CARD, COUNTERID) VALUES (\''.$grz.'\', 4)';
		//echo Debug::vars('142', $sql); exit;
		try {
			$query = DB::query(Database::INSERT, $sql)
			->execute(Database::instance('fb'));
			} catch (Exception $e) {
				Log::instance()->add(Log::DEBUG, '146 '.$e->getMessage());
			}
	return;
	}
	
	/*
	вставка события о ручном удалении ГРЗ из таблицы INSIDE
	
	*/
	public function addEventsDeleteGRZFromInside($grz)
	{
		$eventcode_manual_out=4;
		$sql='INSERT INTO HL_EVENTS (EVENT_CODE, GRZ, ID_GATE)
		VALUES ('.$eventcode_manual_out.', \''.$grz.'\', 1)';
		//echo Debug::vars('144', $sql); exit;
		try {
			$query = DB::query(Database::INSERT, $sql)
			->execute(Database::instance('fb'));
			} catch (Exception $e) {
				Log::instance()->add(Log::DEBUG, '148 '.$e->getMessage());
			}
	return;
	}
	
	/*
	вставка события о ручном добавлении ГРЗ в таблицу INSIDE
	
	*/
	public function addEventsInsertGRZTomInside($grz)
	{
		$eventcode_manual_out=3;
		$sql='INSERT INTO HL_EVENTS (EVENT_CODE, GRZ, ID_GATE)
		VALUES ('.$eventcode_manual_out.', \''.$grz.'\', 1)';
		//echo Debug::vars('179', $sql); exit;
		try {
			$query = DB::query(Database::INSERT, $sql)
			->execute(Database::instance('fb'));
			} catch (Exception $e) {
				Log::instance()->add(Log::DEBUG, '148 '.$e->getMessage());
			}
	return;
	}
	
	/*
	1.06.2023
	вставка события о ручном открытии въезда при нераспознанном ГРЗ
	
	*/
	public function addEventsInsertGRZTomInsideManual($grz, $id_gate, $comment=null)
	{
		$eventcode_manual_out=5;
		if(is_null($comment))
		{
			$sql='INSERT INTO HL_EVENTS (EVENT_CODE, GRZ, ID_GATE)
			VALUES ('.$eventcode_manual_out.', \''.$grz.'\', '.$id_gate.')';
		} else {
			$sql='INSERT INTO HL_EVENTS (EVENT_CODE, GRZ, ID_GATE, COMMENT)
			VALUES ('.$eventcode_manual_out.', \''.$grz.'\', '.$id_gate.', \''.iconv('UTF-8','windows-1251',$comment).'\')';
		}
		//echo Debug::vars('179', $sql); exit;
		try {
			$query = DB::query(Database::INSERT, $sql)
			->execute(Database::instance('fb'));
			} catch (Exception $e) {
				Log::instance()->add(Log::DEBUG, '148 '.$e->getMessage());
			}
	return;
	}
	
	
	
}
