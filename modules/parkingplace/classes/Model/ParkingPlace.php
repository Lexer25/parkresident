<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
* @package    ParkResident/Parking
 * @category   Base
 * @author     Artonit
 * @copyright  (c) 2025 Artonit Team
 * @license    http://artonit/ru 
 
 */

class Model_ParkingPlace extends Model {
	
	
	/**
	 * получить список всех парковочных площадок.
	 *
	 * @param   void
	 
	 * @return  array id_parking
	 */
	public function get_list()// получить список всех парковочных площадок
	{
		$res=array();
				
		$sql='select hlr.id from hl_parking hlr';
		
		
		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();
			
		return $query;	
	}
	
	/**
	 * получить список всех парковочных площадок с названиями.
	 * Метод сделан для удобной организации select
	 * @param   void
	 * @return  array id_parking, name, parent
	 */
	public function get_list_for_select()// получить список всех парковочных площадок
	{
		$res=array();
				
		$sql='select hlr.id, hlr.name, hlr.parent from hl_parking hlr';
		$sql='select hlr.id, hlr.name from hl_parking hlr';
		
		
		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();
		$res=array();
		if($query)
		{
			foreach($query as $key=>$value)
			{
				$res[Arr::get($value, 'ID')] = iconv('windows-1251','UTF-8', Arr::get($value, 'NAME'));
				
			}
		}
			
		return $res;	
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
	
	public function getCount($parent)// получить количество машиномест для указанной парковки
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
	
	
	public static function  getCountParking()// получить количество паркингов
	{
		
		$sql='select count(*) from hl_parking';
		
		
		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->get('COUNT');
	//echo Debug::vars('11',$sql, $query); exit;
		
		return $query;	
	}
	
	
	//1.06.2025получить список всех машин на парковке.
	
	public function getItemInparking()
	{
			$sql='select hli.entertime, hli.id_card, hli.id_pep, hli.counterid as id_parking, hlp.name as parkingName, c."ACTIVE", c.id_cardtype, cd.name as cardtypename,
p.surname||\' \'||p.name||\' \'||p.patronymic||\'(\'||o.name||\')\' as pepname, hlo.id_garage, hlgn.name as GARAGENAME from hl_inside hli
join hl_parking hlp on hli.counterid=hlp.id
join card c on c.id_card=hli.id_card
join cardtype cd on cd.id=c.id_cardtype
join people p on p.id_pep=hli.id_pep
join organization o on o.id_org=p.id_org
left join hl_orgaccess hlo on hlo.id_org=p.id_org
left join hl_garagename hlgn on hlgn.id=hlo.id_garage
			';
			//echo Debug::vars('128', $sql );exit;
			$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();
			
		return $query;	
	}
	
	
	//1.06.2025 получить список категорий доступа, в которые включены ворота.
	
	public function getParkingAccessName()
	{
			$sql='  select distinct hli.id_pep,  an.name from hl_inside hli
			  join ss_accessuser ssau on ssau.id_pep=hli.id_pep
			  join access a on ssau.id_accessname=a.id_accessname
			  join hl_param hlpr on a.id_dev=hlpr.id_dev
			  join accessname an on a.id_accessname=an.id_accessname';
			$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();
			
			
			$result=array();
			foreach($query as $key=>$value)
			{
				$result[Arr::get($value, 'ID_PEP')][] = Arr::get($value, 'NAME');
				
			}
			//echo Debug::vars('154', $result);exit;
		return $result;	
	}
	
	
	
}
