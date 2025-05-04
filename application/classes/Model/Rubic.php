<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
* @package    ParkResident/Application
 * @category   Base
 * @author     Artonit
 * @copyright  (c) 2025 Artonit Team
 * @license    http://artonit/ru 
 
 */

class Model_Rubic extends Model {
	
	public function del_door_parking($data)// удаление точек въеда и выезда в указанную парковку
	{
		//echo Debug::vars('7', $data  ); exit;
		$sql='delete from hl_parking_gate hlpg
				where hlpg.id_parking='.Arr::get($data, 'id_parking').'
				and hlpg.id_dev='.Arr::get($data, 'id_dev');
		
		try
				{
				$query = DB::query(Database::DELETE, $sql)
				->execute(Database::instance('fb'));
				} catch (Exception $e) {
				
				}
	}
	
	public function add_door_parking($data, $is_enter=1)// добавление точек въеда и выезда в указанную парковку
	{
		//echo Debug::vars('26', $data  ); exit;
		$sql='INSERT INTO HL_PARKING_GATE (ID_PARKING, ID_DEV, ID_DB, IS_ENTER)
					VALUES ('.
					Arr::get($data, 'id_parking').','.
					Arr::get($data, 'id_dev').
					',1,'.
					$is_enter.')';
		//echo Debug::vars('13', $sql); exit;
		try
				{
				$query = DB::query(Database::INSERT, $sql)
				->execute(Database::instance('fb'));
				} catch (Exception $e) {
				
				}
	}
	
	public function door_list()// список точек прохода для настройки въезда и выезда парковок
	{
		$sql='select d2.id_dev, d2.name from hl_devicelist hld
			join device d on d.id_dev=hld.id_dev
			join device d2 on d2.id_ctrl=d.id_ctrl and d2.id_reader is not null
			left join hl_parking_gate hlpg on hlpg.id_dev=d2.id_dev
			where d."ACTIVE">0
			and d2."ACTIVE">0
			and hlpg.id_parking is null';
		$res=array();
		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();
		//echo Debug::vars('39', $query); exit;
		foreach ($query as $key => $value)
		{
			$res[Arr::get($value,'ID_DEV')]=iconv('windows-1251','UTF-8',Arr::get($value,'NAME'));
		}
		return $res;	
		
	}
	
	
	public function get_list_dev($id_apb)// получить список точек прохода для указанной парковки
	{
		$res=array();
			
		$sql='select hlpg.id, hlpg.is_enter, hlpg.id_parking, hlpg.id_dev, d.name from hl_parking_gate hlpg
            join device d on d.id_dev=hlpg.id_dev
            where hlpg.id_parking='.$id_apb;
			
			
		
		
		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();
		//echo Debug::vars('39', $query); exit;
		foreach ($query as $key => $value)
		{
			$res[$key]['ID_PERIMETER']=Arr::get($value,'ID');
			$res[$key]['ID_DEV']=Arr::get($value,'ID_DEV');
			$res[$key]['IS_ENTER']=Arr::get($value,'IS_ENTER');
			$res[$key]['NAME']=iconv('windows-1251','UTF-8',Arr::get($value,'NAME'));
			$res[$key]['DELAY']=Arr::get($value,'DELAY');
						
		}
		return $res;	
	}
	
	/*
	
	Подготовка журнала событий из таблицы hl_events в заданном диапазон дат
	
	
	*/
	
	public function getEventsFromTo($data)
	{
		//echo Debug::vars('415', $data); exit;
		
		$sql='select hle.event_time, hle.event_code, hlp.is_enter, hlp.name as gate_name, hlp.id_parking, hle.grz, hle.id_pep, hle.id_gate, hle.comment, et.name as event_name,  p.surname, p.name, p.surname, hl_org.id_garage
            from hl_events  hle
            left join hl_param hlp on hlp.id_dev=hle.id_gate
            left join hl_eventcode  et on et.id=hle.event_code
            left join card c on c.id_card=hle.grz
            left join people p on p.id_pep=c.id_pep
			left join hl_orgaccess hl_org on hl_org.id_org=p.id_org
			where hle.event_time>\''.Date::formatted_time(Arr::get($data, 'timeFrom'), 'Y-m-d H:i:s').'\'
			and hle.event_time<\''.Date::formatted_time(Arr::get($data, 'timeTo'), 'Y-m-d H:i:s').'\'
			and hle.event_code not in (13, 31, 35, 41, 42)';
			
			if(count(Arr::get($data, 'id_event_filter')))
		{
			$sql.= ' and hle.event_code in ('.implode(",", Arr::get($data, 'id_event_filter')).') ';
			
		} 
		
			$sql.=' order by hle.event_time desc';
		//echo Debug::vars('71', date('Y-m-d H:i:s'), $data, $sql); exit;	
		Log::instance()->add(Log::DEBUG, '106 '.$sql);
		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();
			
		foreach($query as $key=>$value)
		{
			$query[$key]['EVENT_NAME']=iconv('windows-1251','UTF-8',Arr::get($value, 'EVENT_NAME'));
			$query[$key]['COMMENT']=$this->selectComment(iconv('windows-1251','UTF-8',Arr::get($value, 'COMMENT')));
			$query[$key]['SURNAME']=iconv('windows-1251','UTF-8',Arr::get($value, 'SURNAME'));
			$query[$key]['NAME']=iconv('windows-1251','UTF-8',Arr::get($value, 'NAME'));
			$query[$key]['PATRONYMIC']=iconv('windows-1251','UTF-8',Arr::get($value, 'PATRONYMIC'));
			$query[$key]['GATE_NAME']=iconv('windows-1251','UTF-8',Arr::get($value, 'GATE_NAME'));
			$query[$key]['ID_GARAGE']=iconv('windows-1251','UTF-8',Arr::get($value, 'ID_GARAGE'));
			//echo Debug::vars('28', $value); exit;
			
		}
	
		return $query;
		
		
	}
	
	public function selectComment($comment)// фукнция заменят код причина на её текстовое значение.
	{
		
		$desc='';
		if(strpos($comment,'причины отказа: '))
		{
		
			$id_event = preg_replace("/[^0-9]/", '', $comment);
			$sql='select et.name from eventtype et
			where et.id_eventtype='.$id_event;
			$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->get('NAME');
			$desc= ' ('.iconv('windows-1251','UTF-8',$query).').';
			
		}

		return $comment.$desc;
	}
	
	public function saveSetupTablo($data)//сохранение параметров работы табло
	{
		
	$header=array(
	"Text=".Arr::get($data, 'header_text'), 
	"X=".Arr::get($data,'header_x'),
	"Y=".Arr::get($data,'header_y'),
	"Color=".Arr::get($data,'header_color'),
	"Font=".Arr::get($data,'header_font'),
	);
	
	$footer=array(
	"Text=".Arr::get($data, 'footer_text'), 
	"X=".Arr::get($data,'footer_x'),
	"Y=".Arr::get($data,'footer_y'),
	"Color=".Arr::get($data,'footer_color'),
	"Font=".Arr::get($data,'footer_font'),
	);
	
	$counters=array(
	"X1=".Arr::get($data, 'count_shift_1'), 
	"X2=".Arr::get($data,'count_shift_2'),
	"Y=".Arr::get($data,'count_shift_top'),
	"Height=".Arr::get($data,'count_font_heigh'),
	"Color=".Arr::get($data,'count_color'),
	"Font=".Arr::get($data,'count_font'),
	
	
	);
	
$dm="\r\n";
	$config_string='[Header]'
		.$dm
		.implode("\r\n", $header)
		.$dm
		.'[Footer]'
		.$dm
		. implode("\r\n", $footer)
		.$dm
		.'[Counters]'
		.$dm
		.implode("\r\n", $counters);
	
	
	
		$sql='UPDATE KP_SETUP
			SET STRVALUE = \''.iconv('UTF-8','windows-1251',$config_string).'\' 
			WHERE (ID = 6)';
		//echo Debug::vars('55', $config_string, $sql); exit;
		$query = DB::query(Database::UPDATE, $sql)
		->execute(Database::instance('fb'));

		return;
	}	
	
	public function getConfig()//получить конфигурационные данные
	{
		$sql='select * from kp_setup';
		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();
	
		$res=array();
		return $query;
	}	
	
	public function getConfigTablo()//получить конфигурационные данные для работы с табло
	{
		$sql='select * from kp_setup kps
		where kps.id in (3,6)';
		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();
		return $query;
	}	
	
	

	public function getListHistory($id_card)
	{
		$sql='select kpe. from kp_events kpe
		join kp_cards kpc on kpc.cardcode=kpe.park_card
		left join people p on kpe.id_pep=p.id_pep
		where kpc.id='.$id_card.'
		and kpe.event_code in (1,2)';
		
		$sql='select kpe.id, kpe.event_code, kpe.event_time, kpe.is_enter, kpe.rubi_card, kpe.park_card, kpe.comment, kpe.id_pep, p.surname, p.name, p.patronymic  from kp_events kpe
        join kp_cards kpc on kpc.cardcode=kpe.park_card
        left join people p on kpe.id_pep=p.id_pep
        where kpe.event_code in (1,2)
        and kpc.id='. $id_card.'
		order by kpe.event_time' ;
		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();
	
	$res=array();
	
	//echo Debug::vars('191', $sql); exit;	
		Foreach ($query as $key => $value)
		{
			$res[$key]['EVENT_TIME']=Arr::get($value, 'EVENT_TIME');
			$res[$key]['PARK_CARD']=Arr::get($value, 'PARK_CARD');
			$res[$key]['IS_ENTER']=Arr::get($value, 'IS_ENTER');
			$res[$key]['ID_PEP']=Arr::get($value, 'ID_PEP');
			$res[$key]['SURNAME']=iconv('windows-1251','UTF-8',Arr::get($value, 'SURNAME'));
			$res[$key]['SURNAME']=iconv('windows-1251','UTF-8',Arr::get($value, 'SURNAME'));
			$res[$key]['SURNAME']=iconv('windows-1251','UTF-8',Arr::get($value, 'SURNAME'));
			$res[$key]['NAME']=iconv('windows-1251','UTF-8',Arr::get($value, 'NAME'));
			$res[$key]['PATRONYMIC']=iconv('windows-1251','UTF-8',Arr::get($value, 'PATRONYMIC'));
			
					
		}
		//echo Debug::vars('18', $res); exit;
		return $res;

	}	


	
	public function get_setup()
	{
		$sql='select kps.id, kps.name, kps.intvalue, kps.strvalue from kp_setup kps
		where kps.id in (4, 37, 53)';
		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();
	
	$res=array();
		
		Foreach ($query as $key => $value)
		{
			$res[Arr::get($value, 'ID')]['NAME']=Arr::get($value, 'NAME');
			$res[Arr::get($value, 'ID')]['INTVALUE']=Arr::get($value, 'INTVALUE');
			$res[Arr::get($value, 'ID')]['STRVALUE']=Arr::get($value, 'STRVALUE');
					
		}
		//echo Debug::vars('18', $res); exit;
		return $res;

	}		
	
	
	
	
	public function save_setup($data)// сохранить настройки
	{
		
		//echo Debug::vars('51', $data); exit;
		$sql='UPDATE KP_SETUP SET 
			INTVALUE = '.Arr::get($data, 'autoreg').'
			WHERE ID=4';

		$query = DB::query(Database::UPDATE, $sql)
		->execute(Database::instance('fb'));
		
		$sql='UPDATE KP_SETUP SET 
			INTVALUE = '.Arr::get($data, 'exit_mode').'
			WHERE ID=53';

		//$query = DB::query(Database::UPDATE, $sql)
		//->execute(Database::instance('fb'));
		
		
		$sql='UPDATE KP_SETUP SET 
			INTVALUE = '.Arr::get($data, 'enter_mode').'
			WHERE ID=37';

		// $query = DB::query(Database::UPDATE, $sql)
		// ->execute(Database::instance('fb'));
		
		
		
		
		
		return;
		
	}
	
	
	public function save_place($data)// сохранить информацию о машиноместе
	{
		//echo Debug::vars('12', $data); //exit;
		$sql='UPDATE HL_PLACE SET
			DESCRIPTION =\''.Arr::get($data, 'description').'\',
			NOTE = \''.Arr::get($data, 'note').'\',
			STATUS = '.Arr::get($data, 'status').',
			NAME = \''.Arr::get($data, 'name').'\',
			ID_PARKING = '.Arr::get($data, 'id_parking').'
			
			
			WHERE (ID = '.Arr::get($data, 'id_place').')';

		
		//echo iconv('UTF-8','windows-1251',Debug::vars('297', $sql)); exit;
	
		try{		
		$query = DB::query(Database::UPDATE, iconv('UTF-8','windows-1251',$sql))
		->execute(Database::instance('fb'));
		Session::instance()->set('ok_mess', array('ok_mess' => __('Данные записаны успешно')));
		
		} catch (Exception $e) {
			
			Log::instance()->add(Log::DEBUG, '#28 '.$e->getMessage());
			Session::instance()->set('e_mess', $e->getMessage());
		}
		
		$sql='delete from hl_garage hlp
			where hlp.id_garagename='.Arr::get($data, 'id_place');
		
		try
				{
				$query = DB::query(Database::DELETE, $sql)
				->execute(Database::instance('fb'));
				} catch (Exception $e) {
					Log::instance()->add(Log::ERROR, $e);
					
				}
		
		
		$sql='insert into HL_GARAGE (ID_GARAGENAME, ID_PLACE) values ('.Arr::get($data, 'id_garagename').','.Arr::get($data, 'id_place').')';

		
		//echo iconv('UTF-8','windows-1251',Debug::vars('362', $sql)); exit;
	
		try{		
		$query = DB::query(Database::INSERT, iconv('UTF-8','windows-1251',$sql))
		->execute(Database::instance('fb'));
		Session::instance()->set('ok_mess', array('ok_mess' => __('Данные записаны обновлены')));
		
		} catch (Exception $e) {
			
			Log::instance()->add(Log::DEBUG, '#371 '.$e->getMessage());
			Session::instance()->set('e_mess', $e->getMessage());
		}
		
		
		
		return;
		
	}
	
	
	
	
	public function get_info_place($id_place)// получить информацию о машиноместе
	{
		
		//echo Debug::vars('10', $id_parking);
		

		$sql='select hlp.id, 
				hlp.placenumber, 
				hlp.id_counters, 
				hlp.description, 
				hlp.note, 
				hlp.status, 
				hlp.name, 
				hlp.id_parking, 
				hlgn.id as id_garage, 
				hlgn.name as garage_name,
				hlpk.name as parkingname,
				hlg.id_garagename				
				from hl_place hlp
				left join hl_garage hlg on hlg.id_place=hlp.id
				left join hl_garagename hlgn on hlgn.id=hlg.id
				left join hl_parking hlpk on hlp.id_parking=hlpk.id
				where hlp.placenumber='.$id_place;
		//echo Debug::vars('384', $sql); exit;
		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();
		
		Foreach ($query as $key => $value)
		{
			$res[$key]['ID']=Arr::get(Arr::get($query, 0), 'ID');
			$res[$key]['CARDCODE']=iconv('windows-1251','UTF-8',Arr::get(Arr::get($query, 0), 'CARDCODE'));
			$res[$key]['AS_ACTIVE']=Arr::get(Arr::get($query, 0), 'AS_ACTIVE');
			$res[$key]['COMMENT']=iconv('windows-1251','UTF-8',Arr::get(Arr::get($query, 0), 'COMMENT'));
			$res[$key]['DESCRIPTION']=iconv('windows-1251','UTF-8',Arr::get(Arr::get($query, 0), 'DESCRIPTION'));
			$res[$key]['NAME']=iconv('windows-1251','UTF-8',Arr::get(Arr::get($query, 0), 'NAME'));
			$res[$key]['description']=iconv('windows-1251','UTF-8',Arr::get(Arr::get($query, 0), 'description'));
			$res[$key]['COMMENT']=iconv('windows-1251','UTF-8',Arr::get(Arr::get($query, 0), 'COMMENT'));
			$res[$key]['CREATEDAT']=Arr::get(Arr::get($query, 0), 'CREATEDAT');
			$res[$key]['CHANGEDAT']=Arr::get(Arr::get($query, 0), 'CHANGEDAT');
			$res[$key]['DELETEDAT']=Arr::get(Arr::get($query, 0), 'DELETEDAT');
			
			
		}
		return $query;	
		
	}
	
	
	public function get_list_parking_card()// Список парковочных карт
	{
		//echo Debug::vars('10', $id_parking);
		$sql='select kpc.id, kpc.cardcode, kpc."ACTIVE" as as_active, kpc.comment, kpc.createdat, kpc.changedat, kpc.deletedat 
		from kp_cards kpc
		order by kpc.id';
		
		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();
		$res=array();
		Foreach ($query as $key => $value)
		{
			$res[$key]['ID']=$value['ID'];
			$res[$key]['CARDCODE']=iconv('windows-1251','UTF-8',$value['CARDCODE']);
			$res[$key]['AS_ACTIVE']=$value['AS_ACTIVE'];
			$res[$key]['COMMENT']=iconv('windows-1251','UTF-8',$value['COMMENT']);
			$res[$key]['CREATEDAT']=$value['CREATEDAT'];
			$res[$key]['CHANGEDAT']=$value['CHANGEDAT'];
			$res[$key]['DELETEDAT']=$value['DELETEDAT'];
		}
		return $res;	
	}
	
	
		
	
	public function get_list_parking_place()// Список парковочных мест
	{
		//echo Debug::vars('10', $id_parking);

		
		
		
		$sql='select hlp.placenumber,
         hlp.name as place_name,
         hlp.description,
         hlp.note,
         hlp.id,
          hlpk.name as parking_name,
          hlgn.name as garage_name,
          hlgn.id as id_garage
        from hl_place hlp
        left join hl_parking hlpk on hlpk.id=hlp.id_parking
        left join hl_garage hlgg on hlgg.id_place=hlp.id
        left join hl_garagename hlgn on hlgn.id=hlgg.id_garagename
		order by hlp.placenumber';

		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();
		/* $res=array();
		Foreach ($query as $key => $value)
		{
			$res[$key]['ID']=Arr::get($value,'ID');
			$res[$key]['PLACENUMBER']=Arr::get($value,'PLACENUMBER');
			$res[$key]['ID_COUNTERS']=Arr::get($value,'ID_COUNTERS');
			$res[$key]['DESCRIPTION']=iconv('windows-1251','UTF-8',Arr::get($value,'DESCRIPTION'));
			$res[$key]['NAME']=iconv('windows-1251','UTF-8',Arr::get($value,'NAME'));
			$res[$key]['PARKINGNAME']=iconv('windows-1251','UTF-8',Arr::get($value,'PARKINGNAME'));
			$res[$key]['STATUS']=Arr::get($value,'STATUS');
			$res[$key]['NOTE']=iconv('windows-1251','UTF-8',Arr::get($value,'NOTE'));
			$res[$key]['GARAGE_NAME']=iconv('windows-1251','UTF-8',Arr::get($value,'GARAGE_NAME'));
			$res[$key]['PLACE_NAME']=iconv('windows-1251','UTF-8',Arr::get($value,'PLACE_NAME'));
			$res[$key]['PARKING_NAME']=iconv('windows-1251','UTF-8',Arr::get($value,'PARKING_NAME'));
		} */
		return $query;	
	}
	
	
	public function get_info_parking($id_parking)// информация о парковке для указанного ID парковки
	{
		
		//echo Debug::vars('10', $id_parking);
		$sql='select kpc.id, 
				kpc.id_org, 
				kpc.name, 
				kpc.maxcount, 
				o.name as org_name,
				kpc."POSITION"
				from kp_counters  kpc
				join organization o on o.id_org=kpc.id_org where kpc.id='.$id_parking;
		
		$sql='select *
			from hl_counters  hlc
			left join organization o on o.id_org=hlc.id_org where hlc.parkingnumber='.$id_parking;
				
		$sql='select *
			from hl_parking  hlc
			where hlc.id='.$id_parking;
				
		
		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();
		//echo Debug::vars('22', $query); exit;
		
		$info_parking['name']=iconv('windows-1251','UTF-8', Arr::get(Arr::get($query, 0), 'NAME'));
		$info_parking['id_parking']=Arr::get(Arr::get($query, 0), 'ID');
		$info_parking['maxcount']=Arr::get(Arr::get($query, 0), 'MAXCOUNT');
		$info_parking['id_org']=Arr::get(Arr::get($query, 0), 'ID_ORG');
		$info_parking['POSITION']=Arr::get(Arr::get($query, 0), 'POSITION');
		$info_parking['org_name']=iconv('windows-1251','UTF-8',Arr::get(Arr::get($query, 0), 'ORG_NAME'));
		//echo Debug::vars('22', $info_parking); exit;
		return $info_parking;
		
	}
	
	public function list_key_into_parking($id_parking)// список карт на парковке для указанной организации
	{
		$res=array();
		
	//echo Debug::vars('38', $id_parking);	
	$sql='select kpi.cardid, 
        kpi.entertime, 
        kpi.duocard, 
        kpi.counterid,
        kpc.comment,
        c.id_card, 
        c.id_pep, 
        p.surname, 
        p.name, 
        p.patronymic 
        from kp_inside kpi
        join card c on c.id_card=kpi.duocard
        join kp_cards kpc on kpc.id=kpi.cardid
        join people p on p.id_pep=c.id_pep
        where kpi.counterid='.$id_parking.'
		order by kpi.entertime';
		
		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();
			
		Foreach ($query as $key => $value)
		{
			$res[$key]['CARDID']=$value['CARDID'];
			$res[$key]['ENTERTIME']=$value['ENTERTIME'];
			$res[$key]['DUOCARD']=$value['DUOCARD'];
			$res[$key]['COUNTERID']=$value['COUNTERID'];
			$res[$key]['COMMENT']=iconv('windows-1251','UTF-8',$value['COMMENT']);
			$res[$key]['ID_CARD']=$value['ID_CARD'];
			$res[$key]['ID_PEP']=$value['ID_PEP'];
			$res[$key]['NAME']=iconv('windows-1251','UTF-8',$value['NAME']);
			$res[$key]['SURNAME']=iconv('windows-1251','UTF-8',$value['SURNAME']);
			$res[$key]['PATRONYMIC']=iconv('windows-1251','UTF-8',$value['PATRONYMIC']);
		}
		return $res;	 
	}
	
	public function list_plate_into_parking($id_parking)// список ГРЗ на парковке для указанной парковки
	{
		$res=array();
		
	//echo Debug::vars('38', $id_parking);	
	$sql='select kpi.cardid, 
        kpi.entertime, 
        kpi.duocard, 
        kpi.counterid,
        kpc.comment,
        c.id_card, 
        c.id_pep, 
        p.surname, 
        p.name, 
        p.patronymic 
        from kp_inside kpi
        join card c on c.id_card=kpi.duocard
        join kp_cards kpc on kpc.id=kpi.cardid
        join people p on p.id_pep=c.id_pep
        where kpi.counterid='.$id_parking.'
		order by kpi.entertime';
		
		
	$sql='select hli.*, o.* from hl_inside hli
		left join card c on c.id_card=hli.id_card
		left join people p on p.id_pep=c.id_pep
		left join organization o on o.id_org=p.id_org
		where hli.counterid='.$id_parking;	
		
		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();
			
		Foreach ($query as $key => $value)
		{
			$res[$key]['CARDID']=Arr::get($value,'CARDID');
			$res[$key]['ENTERTIME']=Arr::get($value,'ENTERTIME');
			$res[$key]['DUOCARD']=Arr::get($value,'DUOCARD');
			$res[$key]['COUNTERID']=Arr::get($value,'COUNTERID');
			$res[$key]['COMMENT']=iconv('windows-1251','UTF-8',Arr::get($value,'COMMENT'));
			$res[$key]['ID_CARD']=Arr::get($value,'ID_CARD');
			$res[$key]['NAME']=iconv('windows-1251','UTF-8',Arr::get($value,'NAME'));
		}
		return $res;	 
	}
	
	
	
	public function update_delay_pass($id_rubic, $id_dev)// изменение параметров периметра АПБ
	{
		//echo Debug::vars('10',$id_rubic, $id_dev ); exit;
		foreach ($id_dev as $key=>$value)
		{
			if(is_numeric($value))
			{
				$sql='update perimeter_gate pg
						set pg.delay = '.(int)$value.'
						where pg.id_dev='.(int)$key.'
						and pg.id_perimeter='.(int)$id_rubic.'
						AND (ID_DB = 1)';
						$query = DB::query(Database::UPDATE, $sql)
						->execute(Database::instance('fb'));
			}
		}
		
	}
	
	
	public function change_config($id_parking, $name, $id_parking, $maxcount, $position)// изменение параметров периметра АПБ
	{
		$sql='UPDATE KP_COUNTERS SET 
			NAME = \''.iconv('UTF-8','windows-1251',$name).'\',
			MAXCOUNT = '.$maxcount.'
			,"POSITION" = '.$position.'
			WHERE (ID = '.$id_parking.') AND (ID_DB = 1)';
		
		
		$query = DB::query(Database::UPDATE, $sql)
		->execute(Database::instance('fb'));
		return;
		
	}
	
	
	public function get_info_rubic($id_rubic)// получить список настроек указанного rubic
	{
		$sql='select p.id, p.id_db, p.name, p.guest_duration as DURATION, p.enabled from PERIMETER p where p.id='.$id_rubic;
		
		
		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();
		//echo Debug::vars('11', $query); exit;
		foreach ($query as $key => $value)
		{
			$res['ID']=$value['ID'];
			$res['NAME']=iconv('windows-1251','UTF-8',$value['NAME']);
			$res['DURATION']=$value['DURATION'];
			$res['ENABLED']=$value['ENABLED'];
		}
		return $res;	
	}
	
	public function get_list_parking_dell($id_parking)// получить список парковок для указанного родителя
	{
		$res=array();
		$sql='select hlp.id, hlp.name, hlp.enabled, count(hlc.id) as MAXCOUNT from hl_parking hlp
				left join hl_counters hlc on hlc.parkingnumber=hlp.id
				where hlp.parent ='.$id_parking.'
				group by hlp.id, hlp.name, hlp.enabled, hlp.maxcount';
		
		
		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();
		//echo Debug::vars('11', $query); exit;
		foreach ($query as $key => $value)
		{
			$res[$key]['ID']=Arr::get($value, 'ID');
			$res[$key]['ID_ORG']=Arr::get($value,'ID_ORG');
			$res[$key]['NAME']=iconv('windows-1251','UTF-8',Arr::get($value,'NAME'));
			$res[$key]['MAXCOUNT']=Arr::get($value,'MAXCOUNT');
			$res[$key]['POSITION']=Arr::get($value,'POSITION');
			
			
		}
		return $res;	
	}
	
	
	public function get_list_parking_select($id_parking)// получить список парковок для указанного родителя
	{
		$res=array();
		$sql='select hlp.id, hlp.name from hl_parking hlp
				where hlp.id > 0';
		try{
		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();
		//echo Debug::vars('11', $query); exit;
		foreach ($query as $key => $value)
		{
			$res[Arr::get($value,'ID')]=iconv('windows-1251','UTF-8',Arr::get($value,'NAME'));
		}
		} catch (Exception $e) {
			
		}
		return $res;	
	}
	
	
	
	public function get_list_garageName()// получить список гаражен
	{
		$res=array();
		$sql='select hlgn.id, hlgn.name from hl_garagename hlgn
			order by hlgn.name';
		try{
		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();
		//echo Debug::vars('11', $query); exit;
		foreach ($query as $key => $value)
		{
			$res[Arr::get($value,'ID')]=iconv('windows-1251','UTF-8',Arr::get($value,'NAME'));
		}
		} catch (Exception $e) {
			
		}
		return $res;	
	}
	
	
	
	
	
	
	public function get_list_org()// получить список корневых организаций rubic для добавления парковок
	{
		/* $sql='select o.id_org, o.name from organization o
left join kp_counters kpc on kpc.id_org=o.id_org
where o.id_parent=1 and o.id_org>3
and kpc.id_org is null';
		
		
		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();
		//echo Debug::vars('11', $query); exit;
		
		foreach ($query as $key => $value)
		{
			//$res[$key]['ID_ORG']=$value['ID_ORG'];
			//$res[$key]['NAME']=iconv('windows-1251','UTF-8',$value['NAME']);
			$res[$value['ID_ORG']]=iconv('windows-1251','UTF-8',$value['NAME']);
			
		} */
		$res[]='test_org';
		
		return $res;	
	}
	
	
	
	

	
	public function get_dev_info($id_dev,$id_rubic)// получить информацию по указанной точке прохода rubic
	{
		$res=array();
		$sql='select pg.id_PERIMETER, pg.id_dev, pg.id_db, pg.is_enter, p.name from PERIMETER_gate pg
            join PERIMETER p on p.id=pg.id_PERIMETER
            where pg.id_PERIMETER='.$id_rubic.' 
            and pg.id_dev='.$id_dev;
				
		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();
		//echo Debug::vars('39', $query); exit;
		foreach ($query as $key => $value)
		{
			$res['ID_PERIMETER']=$value['ID_PERIMETER'];
			$res['ID_DEV']=$value['ID_DEV'];
			$res['IS_ENTER']=$value['IS_ENTER'];
			$res['NAME']=iconv('windows-1251','UTF-8',$value['NAME']);
						
		}
		return $res;	
	}
	
	

	public function getOrgName($id_org)
	{
		$sql='select o.name, o.id_org, o.id_parent from organization o where o.id_org='.$id_org;
		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			//->as_array();
			->get('NAME');
		return iconv('windows-1251','UTF-8',$query);
	}
	
	public function add_rubic($park_name, $id_org) //добавление новой парковки для выбранного жилого комплекса
	{
		echo Debug::vars('851');//exit;
		//if($park_name === NULL) $park_name=$this->getOrgName($id_org);
		//$sql='insert into kp_counters (id_org, name, maxcount) values ('.$id_org.', \''.$park_name.'\', 0)';
		//$sql='insert into hl_parking (id_org, name, maxcount) values ('.$id_org.', \''.$park_name.'\', 0)';
		
		$sql='INSERT INTO HL_PARKING (NAME, PARENT)
			values (\''.$park_name.'\','. $id_org.')';
		//echo Debug::vars('858', $sql);exit;
		try
				{
				$query = DB::query(Database::INSERT, iconv('UTF-8','windows-1251',$sql))
				->execute(Database::instance('fb'));
				} catch (Exception $e) {
				}
		return;
	}
	
	

	public function insertCard($keycard) //добавление новой карты в базу данных
	{
		
		$sql='INSERT INTO KP_CARDS (CARDCODE,"ACTIVE",COMMENT,CREATEDAT,CHANGEDAT,DELETEDAT)
			VALUES (\''.$keycard.'\',1,\'Новая карта\',\'now\',\'now\',NULL);';
			//echo Debug::vars('599', $sql); exit;
		try
				{
				$query = DB::query(Database::INSERT, iconv('UTF-8','windows-1251',$sql))
				->execute(Database::instance('fb'));
				} catch (Exception $e) {
				}
		return;
	}
	

	
	
	public function del_rubic($del_rubic)
	{
		
		$sql='delete from kp_counters kpc where kpc.id='.$del_rubic;
		
		$query = DB::query(Database::UPDATE, $sql)
		->execute(Database::instance('fb'));
	return;	
		
	}
	

	
	public function del_card_rubic($id_card)
	{
		$sql='delete from KP_CARDS kpc where kpc.id='.$id_card;
		
		$query = DB::query(Database::DELETE, $sql)
		->execute(Database::instance('fb'));
		return;
	}
	
	
	public function clear_parking_inside($id_parking)
	{
		$sql='delete from kp_inside kpi where kpi.counterid='.$id_parking;
		
		$query = DB::query(Database::DELETE, $sql)
		->execute(Database::instance('fb'));
		return;
	}
	
	
	
	
}
