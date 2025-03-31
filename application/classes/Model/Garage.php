<?php defined('SYSPATH') OR die('No direct access allowed.');

class Model_Garage extends Model {
	
	public $rootParking=14;
	
	public function getAllGarageInfo()
	{
		$garageList=$this->get_list_garage();
		foreach ($garageList as $key=>$value)
		{
			$res[Arr::get($value,'ID')]=$this->getGarageInfo(Arr::get($value,'ID'));
			
		}
		return $res;
		
		
	}
	
	public function getGarageInfo($id_garage)
	{
		/* public $name;// название гаража
		$id_garage;
		public $orgList;//список организаций гаража
		public $placeList;//список машномест гаража
		public $grzInGarageList;//список ГРЗ, находящихся на территории гаража
		*/
		
		$info=$this->get_garage_info($id_garage);
		$result=array(
			'name'=>Arr::get($info, 'NAME'),
			'id_garage'=>Arr::get($info, 'ID'),
			'not_count'=>Arr::get($info, 'NOT_COUNT'),
			'orgList'=>$this->org_income_garage($id_garage),
			'placeList'=>$this->place_income_garage($id_garage),
			'grzList'=>$this->place_grz_garage_($id_garage),
			'grzInGarageList'=>$this->get_grz_in_parking($id_garage),
			'parkingList'=>Model::Factory('parking')->get_list_parking($this->rootParking),
			);
			return $result;
			
	}
	
	public function place_income_garage($id_garage)// Список машиномест, входящих в указанный гараж
	{
		//echo Debug::vars('7', $id_garage);
		$sql='select hlp.id, hlp.name, hlp.placenumber, hlp.note, hlp.id_parking, hlpr.name as parking_name from hl_garage hlg
            join hl_place hlp on hlp.id=hlg.id_place
            join hl_parking hlpr on hlp.id_parking=hlpr.id
			where hlg.id_garagename='.$id_garage;
		//echo Debug::vars('11', $sql); exit;
		try
		{
			$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();
			$res=array();
			Foreach ($query as $key => $value)
			{
				$res[Arr::get($value,'ID')]['ID']=Arr::get($value,'ID');
				$res[Arr::get($value,'ID')]['ID_PARKING']=Arr::get($value,'ID_PARKING');
				$res[Arr::get($value,'ID')]['NAME']=iconv('windows-1251','UTF-8',Arr::get($value,'NAME'));
				$res[Arr::get($value,'ID')]['PLACENUMBER']=Arr::get($value,'PLACENUMBER');
				$res[Arr::get($value,'ID')]['PARKING_NAME']=iconv('windows-1251','UTF-8',Arr::get($value,'PARKING_NAME'));
				$res[Arr::get($value,'ID')]['NOTE']=iconv('windows-1251','UTF-8',Arr::get($value,'NOTE'));
			}
			//echo Debug::vars('7',$query $res); exit;
			return $res;
		} catch (Exception $e) {
			Log::instance()->add(Log::ERROR, $e);
		}
		
	}
	
	public function place_grz_garage_($id_garage)// Список ГРЗ, входящих в указанный гараж
	{
		
		$sql='select distinct c.id_card, c."ACTIVE", p.surname from hl_orgaccess hlo
			join people p on p.id_org=hlo.id_org
			join card c on c.id_pep=p.id_pep and c.id_cardtype=4
			where hlo.id_garage='.$id_garage;
		//echo Debug::vars('38', $sql); exit;
		try
		{
			$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();
			$res=array();
			Foreach ($query as $key => $value)
			{
				$res[Arr::get($value,'ID_CARD')]['ID']=Arr::get($value,'ID');
				$res[Arr::get($value,'ID_CARD')]['NAME']=iconv('windows-1251','UTF-8',Arr::get($value,'SURNAME'));
				$res[Arr::get($value,'ID_CARD')]['GRZ']=iconv('windows-1251','UTF-8',Arr::get($value,'ID_CARD'));
				$res[Arr::get($value,'ID_CARD')]['ACTIVE']=Arr::get($value,'ACTIVE');
			}
			//echo Debug::vars('89', $res); exit;
			return $res;
		} catch (Exception $e) {
			Log::instance()->add(Log::ERROR, $e);
		}
		
	}
	
	public function get_garage_parking_list($id_garage)// Список парковок, входящих в указанный гараж
	{
		
		$sql='select distinct p.id_parking, hlp.name from hl_garage g
            join hl_place p on p.id=g.id_place
            join hl_parking hlp on p.id_parking=hlp.id
            where g.id_garagename='.$id_garage;
		//echo Debug::vars('38', $sql); exit;
		try
		{
			$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();
			return $query;
		} catch (Exception $e) {
			Log::instance()->add(Log::ERROR, $e->getMessage()); exit;
			Log::instance()->add(Log::ERROR, $e->getMessage());
		}
		
	}
	
	
	
	public function org_income_garage($id_garage)// Список квартир, входящих в указанный гараж
	{
		//echo Debug::vars('33', $id_garage);
		$sql='select hlo.id_org, hlo.id_garage, o.name from hl_orgaccess hlo
			join organization o on o.id_org=hlo.id_org
			where hlo.id_garage='.$id_garage;
		//echo Debug::vars('36', $sql); //exit;
		try
		{
			$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();
			$res=array();
			//echo Debug::vars('36', $sql, $query); //exit;
			Foreach ($query as $key => $value)
			{
				//echo Debug::vars('49', $key, $value); //exit;
				$res[Arr::get($value,'ID_ORG')]['ID']=Arr::get($value,'ID_ORG');
				$res[Arr::get($value,'ID_ORG')]['NAME']=iconv('windows-1251','UTF-8',Arr::get($value,'NAME'));
				//$res[Arr::get($value,'ID_ORG')]['FULLNAME']=iconv('windows-1251','UTF-8',$this->get_stringParent(Arr::get($value,'ID_ORG')));
				$res[Arr::get($value,'ID_ORG')]['NOTE']=iconv('windows-1251','UTF-8',Arr::get($value,'NOTE'));
				//echo Debug::vars('49', $key, $value, $res); exit;
			}
			//echo Debug::vars('49', $res); //exit;
			return $res;
		} catch (Exception $e) {
			Log::instance()->add(Log::ERROR, $e);
		}
		
	}
	
	public function get_stringParent ($id_org) // получить лист родителей в виде строки
	{
		$sql='select  og.id_org, og.name, og.id_parent, og.flag from ORGANIZATION_GETPARENT(1, '.$id_org.') og
		order by og.id_org';
		
		$sql='select  og.name from ORGANIZATION_GETPARENT(1, '.$id_org.') og
		order by og.id_org';
		//echo Debug::vars('69', $sql); exit;
		
		try
		{
			$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();
			
			$res=implode("/", $query);
			return $res;
		} catch (Exception $e) {
			Log::instance()->add(Log::ERROR, $e);
		}
		
		return 'get_stringParent';
	}
	
	public function org_can_garage($id_garage = null)// Список квартир, имеющих право на въезд в гараж кандидат на удаление
	{
		$sql='SELECT * FROM ORGANIZATION_GETCHILD(1, 2451)';
		
		
		//echo Debug::vars('11', $sql); exit;
		
		
		
		try
		{
			$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();
			$res=array();
			$res[1]= array(
			"id" => 0,
			"title" => "Все",
			"parent" => 0
			);
		foreach ($query as $key=>$value)
		{
			//echo Debug::vars('58', $value); exit;
			$res[Arr::get($value, 'ID_ORG')]['id']=Arr::get($value, 'ID_ORG');
			$res[Arr::get($value, 'ID_ORG')]['title']=iconv('windows-1251','UTF-8', Arr::get($value, 'NAME'));
			$res[Arr::get($value, 'ID_ORG')]['parent']=Arr::get($value, 'ID_PARENT');
			
		}
			
			return $res;
		} catch (Exception $e) {
			Log::instance()->add(Log::ERROR, $e);
		}
		
	}
	
	public function org_busy_garage() //список квартир, уже входящих в другие гаражи. Их надо пометить как неактивные и запретить выбор.
	{
		//https://xhtml.ru/2022/html/tree-views/		
		$sql='SELECT  og.id_org, og.name, og.id_parent, og.flag,  hlo.id_garage FROM ORGANIZATION_GETCHILD(1, 1)  og
                left join  hl_orgaccess hlo on hlo.id_org = og.id_org
                order by og.id_org ';

		try
		{
			$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();
			$res=array();
			$res[1]= array(
			"id" => 1,
			"title" => "Все",
			"parent" => 0
			);
		foreach ($query as $key=>$value)
		{
			//echo Debug::vars('58', $value); exit;
			$res[Arr::get($value, 'ID_ORG')]['id']=Arr::get($value, 'ID_ORG');
			$res[Arr::get($value, 'ID_ORG')]['title']=iconv('windows-1251','UTF-8', Arr::get($value, 'NAME'));
			$res[Arr::get($value, 'ID_ORG')]['parent']=Arr::get($value, 'ID_PARENT');
			$res[Arr::get($value, 'ID_ORG')]['busy']=Arr::get($value, 'ID_GARAGE');
			
		}
			$res[1]['parent']=0;
			//echo Debug::vars('126', Arr::get($res, 1), $res); exit;
			return $res;
		} catch (Exception $e) {
			Log::instance()->add(Log::ERROR, $e);
		}
		
	}
	
	/*
	6.06.2023
	Добавление машиноместа в гараж в таблицу HL_GARAGE. Список машиномест и номер гаража приходит массивом.
	Сначала удаляются все машиноместа для указанного гаража, а затем добавляются новые машиноместа
	$data содержит массивы:
	"id_garage" => string(3) "757"
    "id_place" => array(1) (
        168 => string(3) "168"
    )
	*/
	
	public function add_place_to_garage($data)
	{
		
		$this->del_place_from_garage($data);// удаляю старые данные
		
		$id_place=Arr::get($data, 'id_place');
		//echo Debug::vars('33', $data, $id_place); exit;
		if(!is_null($id_place))
		{
			foreach ($id_place as $key=>$value)
			{
				$sql='INSERT INTO HL_GARAGE (ID_PLACE, ID_GARAGENAME) 
				VALUES ('.$value.','.Arr::get($data, 'id_garage').')';
				//echo Debug::vars('10', $sql, $id_place); exit;
				try
				{
					$query = DB::query(Database::INSERT, $sql)
					->execute(Database::instance('fb'));
				} catch (Exception $e) {
					Log::instance()->add(Log::ERROR, $e);
				}
			}
		}
		return;
	}
	
	public function del_garage($id_garage)// удалдение гаража из таблицы HL_GARAGENAME. Удаление возможно только для "пустого" гаража.
	{
		//echo Debug::vars('23', $data);
		$sql='select count(*) from hl_garage hlg
				where hlg.id_garagename='.$id_garage;
					try
				{
				$query = DB::query(Database::SELECT, $sql)
				->execute(Database::instance('fb'))
				->get('COUNT');
				} catch (Exception $e) {
					Log::instance()->add(Log::ERROR, $e);
				}
		//echo Debug::vars('202', $query); exit;
		if($query == 0)
		{			
		$sql='delete from hl_garagename hlg
				where hlg.id='.$id_garage;
		//echo Debug::vars('12', $sql); exit;
		try
				{
				$query = DB::query(Database::DELETE, $sql)
				->execute(Database::instance('fb'));
				} catch (Exception $e) {
					Log::instance()->add(Log::ERROR, $e);
				}
		return 0;
		} else {
			return 1;
			
		}
	}
	
	/*
	удаление одного машиноместа id_place из гаража
	$id_place - номер машиноместа
	*/	
	public function del_place_from_garage($data)
	{
	//echo Debug::vars('66', $data, Arr::get($data, 'id_place')); exit;
		$sql='delete from hl_garage hlp
			where hlp.id_garagename='.Arr::get($data, 'id_garage');
		
		try
				{
				$query = DB::query(Database::DELETE, $sql)
				->execute(Database::instance('fb'));
				} catch (Exception $e) {
					Log::instance()->add(Log::ERROR, $e);
				}
		return;
	}
	
	/*
	27.06.2023
	Добавление организации в гараж в таблицу HL_ORGACCESS. Список id организаций и номер гаража приходит массивом.
	Сначала удаляются все организации для указанного гаража, а затем добавляются новые организации
	$data содержит массивы:
	"id_garage" 
    "id_org_for_add_garage" => array(1) (
        168 => string(3) "168"
    )
	*/
	public function add_org_to_garage($data)// Добавление организаций в гараж.
	{
		//echo Debug::vars('194', $data); exit;
		// сначала удаляем организации для этого гаража
		$this->del_org_from_garage(Arr::get($data, 'id_garage'));
		//echo Debug::vars('157',$data, Arr::get($data, 'id_org_for_add_garage')); exit;
		//а затем добавляем организации для этого гаража
		$id_org=Arr::get($data, 'id_org_for_add_garage');
		if(!is_null($id_org))
		{		
			foreach ($id_org as $key)
			{
				//echo Debug::vars('157',$data, Arr::get($data, 'id_garage'), implode(",", Arr::get($data, 'id_org_for_add_garage')) ); exit;
				$sql='INSERT INTO HL_ORGACCESS (ID_ORG, ID_GARAGE) VALUES ('.$key.','.Arr::get($data, 'id_garage').')';
				//echo Debug::vars('228', $sql); exit;
				try
						{
						$query = DB::query(Database::DELETE, $sql)
						->execute(Database::instance('fb'));
						} catch (Exception $e) {
							Log::instance()->add(Log::ERROR, $e);
							
						}
			}
		}
		return;
	}
	
	
	public function del_org_from_garage($data)// удаление организации из гаража
	{
		//echo Debug::vars('66', $data, Arr::get($data, 'id_place')); exit;
		$sql='delete from HL_ORGACCESS hlg where hlg.id_garage='.$data;
		
		//echo Debug::vars('181', $sql); exit;
		try
				{
				$query = DB::query(Database::DELETE, $sql)
				->execute(Database::instance('fb'));
				} catch (Exception $e) {
					Log::instance()->add(Log::ERROR, $e);
					
				}
		return;
	}
	
	
	public function del_once_org_from_garage($id_garage, $id_org)// удаление одной организации из гаража
	{
		//echo Debug::vars('66', $data, Arr::get($data, 'id_place')); exit;
		$sql='delete from HL_ORGACCESS hlg 
			where hlg.id_garage='.$id_garage.'
			and hlg.id_org='.$id_org;
		
		//echo Debug::vars('292', $sql); exit;
		try
				{
				$query = DB::query(Database::DELETE, $sql)
				->execute(Database::instance('fb'));
				} catch (Exception $e) {
					Log::instance()->add(Log::ERROR, $e);
					
				}
		return;
	}
	
	
	
	
	public function update_garage($data)// обновление информации о гараже в таблице HL_GARAGENAME
	{
		//echo Debug::vars('39');
		$sql='UPDATE HL_GARAGENAME
			SET NAME = \''.Arr::get($data, 'name').'\',
			NOT_COUNT = '.(Arr::get($data, 'not_count')? 1 : 0 ).'
			WHERE (ID = '.Arr::get($data, 'id_garage').')';
		//echo Debug::vars('12',$data, $sql); exit;
		try
				{
					Log::instance()->add(Log::NOTICE, $sql);
				$query = DB::query(Database::UPDATE, iconv('UTF-8','windows-1251',$sql))
		->execute(Database::instance('fb'));
				} catch (Exception $e) {
					Log::instance()->add(Log::ERROR, $e);
				}
		return;
	}
	
	
	public function get_list_garage($id_garagename=null)// Список гаражей для указанной парковке
	{
		//echo Debug::vars('41');

		$sql='select hlg.id, hlg.name, hlg.created from hl_garagename hlg
			order by hlg.id';
		
		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();
		$res=array();
		Foreach ($query as $key => $value)
		{
			$res[$key]['ID']=Arr::get($value,'ID');
			$res[$key]['HL_ORG_COUNT']=Arr::get($value,'HL_ORG_COUNT', 0);
			$res[$key]['HL_PLACE_COUNT']=Arr::get($value,'HL_PLACE_COUNT', 0);
			$res[$key]['ID']=Arr::get($value,'ID');
			$res[$key]['NAME']=iconv('windows-1251','UTF-8',Arr::get($value,'NAME'));
			$res[$key]['CREATED']=Arr::get($value,'created');
			
		}
		return $res;	
	}
	
	
	public function get_stat_garage_place( $id_garagename=null)// статистические данные: количество машиномест, зарегистрированных в гаражах
	{

		if(is_null($id_garagename))
		{
		$sql='select hlg1.id_garagename, hlg1.id_place, hlp.placenumber, hlp.id_parking from hl_garage hlg1
			join hl_place hlp on hlp.id=hlg1.id_place';
		} else {
		$sql='select hlg1.id_garagename, hlg1.id_place, hlp.placenumber, hlp.id_parking from hl_garage hlg1
			join hl_place hlp on hlp.id=hlg1.id_place
			where hlg1.id_garagename='.$id_garagename;	
			
		}
		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();
		$res=array();
		Foreach ($query as $key => $value)
		{
			//$res[Arr::get($value,'ID_GARAGENAME')][]=Arr::get($value,'PLACENUMBER');
			$res[Arr::get($value,'ID_GARAGENAME')][Arr::get($value,'PLACENUMBER')]=Arr::get($value,'ID_PARKING');
				
		}
		//echo Debug::vars('334', $res); exit;
		return $res;	
	}
	
	
	public function get_stat_garage_org($id_garagename=null)// ;//статистические данные: количество квартир, зарегистрированных в гаражах
	{
		
		if(is_null($id_garagename))
		{
		$sql='select hlg.id, hlo.id_org, o.id_org, o.name, p.surname||p.name||p.patronymic as car_model, c.id_card  from hl_garagename hlg
            left join hl_orgaccess hlo on hlo.id_garage=hlg.id
            left join organization o on hlo.id_org=o.id_org
            left join people p on p.id_org=o.id_org
            left join card c on c.id_pep=p.id_pep and c.id_cardtype=4
            order by hlg.id';
		} else {
			$sql='select hlg.id, hlo.id_org, o.id_org, o.name, p.surname||p.name||p.patronymic as car_model, c.id_card  from hl_garagename hlg
            left join hl_orgaccess hlo on hlo.id_garage=hlg.id
            left join organization o on hlo.id_org=o.id_org
            left join people p on p.id_org=o.id_org
            left join card c on c.id_pep=p.id_pep and c.id_cardtype=4
            where hlg.id='.$id_garagename.'
            order by hlg.id';
		}
		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();
		$res=array();
		Foreach ($query as $key => $value)
		{
			//$res[Arr::get($value,'ID')]=Arr::get($value,'ORG_COUNT');
			$res[Arr::get($value,'ID')]['org'][Arr::get($value,'ID_ORG')]=iconv('windows-1251','UTF-8',Arr::get($value,'NAME'));
			$res[Arr::get($value,'ID')]['grz'][Arr::get($value,'ID_CARD')]=iconv('windows-1251','UTF-8',Arr::get($value,'CAR_MODEL'));
			
				
		}
		//echo Debug::vars('384', $res); exit;
		return $res;	
	}
	
	
	public function get_grz_in_parking($id_garage=null)// перечень ГРЗ, находящихся на парковке
	{
		if(is_null($id_garage))
		{
		$sql='select hlo.id_garage, hli.id_card, hli.entertime, hli.counterid as id_parking, c."ACTIVE"  from hl_inside  hli
				left join card c on c.id_card=hli.id_card
				left join people p on p.id_pep=c.id_pep
				left join hl_orgaccess hlo on hlo.id_org=p.id_org
				order by hlo.id_garage';
		} else {
			$sql='select hlo.id_garage, hli.id_card, hli.entertime, hli.counterid as id_parking, c."ACTIVE"  from hl_inside  hli
                left join card c on c.id_card=hli.id_card
                left join people p on p.id_pep=c.id_pep
                left join hl_orgaccess hlo on hlo.id_org=p.id_org
                where hlo.id_garage='.$id_garage.'
                order by hlo.id_garage';
			
		}
			
			

		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();
		$res=array();
		//echo Debug::vars('404', $query); exit;
		Foreach ($query as $key => $value)
		{
			//$res[Arr::get($value,'ID')]=Arr::get($value,'ORG_COUNT');
			$res[Arr::get($value,'ID_CARD')]['ENTER_TIME']=Arr::get($value,'ENTERTIME');
			$res[Arr::get($value,'ID_CARD')]['ID_PARKING']=Arr::get($value,'ID_PARKING');
			$res[Arr::get($value,'ID_CARD')]['ACTIVE']=Arr::get($value,'ACTIVE', 0);
		}
		//echo Debug::vars('384', $res); exit;
		return $res;	
	}
	
	
	
	
	public function add_garage($data) //добавление гаража
	{
		
		$sql='INSERT INTO HL_GARAGENAME (NAME) VALUES (\''.Arr::get($data,'name').'\')';
		//echo Debug::vars('815', $sql); exit;
		try
				{
				$query = DB::query(Database::INSERT, iconv('UTF-8','windows-1251',$sql))
				->execute(Database::instance('fb'));
				} catch (Exception $e) {
				}
		return;
	}
	
	public function get_garage_info($id_garage) //информация о гараже
	{
		
		$sql='select hlg.id, hlg.name, hlg.created, hlg.not_count
			from hl_garagename hlg
			where hlg.id='.$id_garage;
		//echo Debug::vars('815', $sql); exit;
		$res=array();
		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();
		$res=array();
		Foreach ($query as $key => $value)
		{
			$res['ID']=Arr::get($value,'ID');
			$res['NOT_COUNT']=Arr::get($value,'NOT_COUNT');
			$res['NAME']=iconv('windows-1251','UTF-8',Arr::get($value,'NAME'));
			$res['CREATED']=Arr::get($value,'created');
		}
		return $res;
	}
	
	public function place_busy_garage() //список машиномест, входящих в гаражи
	{
		
		$sql='select distinct hlg.id_place from hl_garage hlg';
		//echo Debug::vars('815', $sql); exit;
		$res=array();
		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();
		foreach ($query as $key=>$value)
		{
			$res[Arr::get($value, 'ID_PLACE')]=Arr::get($value, 'ID_PLACE');
		}
		
		return $res;
	}
	
	public function get_id_garage_from_place($num_place) //получить номер гаража по номеру машиноместа
	{
		
		$sql='select hlg.id_garagename from HL_PLACE hlp
join hl_garage hlg on hlg.id_place=hlp.placenumber
where hlp.placenumber='.$num_place;
		//echo Debug::vars('815', $sql); exit;
		$res=array();
		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->get('ID_GARAGENAME');
		
		
		return $query;
	}
	
	
	
}
