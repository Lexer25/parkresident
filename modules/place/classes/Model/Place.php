<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
* @package    ParkResident/Place
 * @category   Base
 * @author     Artonit
 * @copyright  (c) 2025 Artonit Team
 * @license    http://artonit/ru 
 
 */

class Model_Place extends Model {
	
	
	
	/**Получить список всех машиномест для указанной парковки
	*
	*/
	public function getPlaceListforParking()
	{
	$sql='select hlr.id, hlr.placenumber, hlr.description, hlr.note, hlr.status, hlr.name, hlr.id_parking, hlr.created , hlg.id_garagename
			from hl_place hlr
			left join hl_garage hlg on hlg.id_place=hlr.id';
	$query=array();		
		try
		{
			$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();
			
			
			//echo Debug::vars('49', $sql, $query, $this); exit;
			
		} catch (Exception $e) {
			////echo Debug::vars('30', $sql, $e->getMessage()); exit;
			Log::instance()->add(Log::DEBUG, 'Line 40 '. $e->getMessage());
		
		}
		return $query;
	}
	
	/**Получить список всех машиномест для указанного паркинга
	*
	*/
	public function getPlaceListforAllParking()
	{
	$sql='select hlr.id, hlr.placenumber, hlr.description, hlr.note, hlr.status, hlr.name, hlr.id_parking, hlr.created , hlg.id_garagename as id_garage,
     hlp.name as parkingName, hlgn.name as garageName
            from hl_place hlr
            left join hl_parking hlp on hlp.id =hlr.id_parking
            left join hl_garage hlg on hlg.id_place=hlr.id
            left join hl_garagename hlgn on hlgn.id=hlg.id_garagename';
	$query=array();		
		try
		{
			$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();
			
			
			//echo Debug::vars('49', $sql, $query, $this); exit;
			
		} catch (Exception $e) {
			////echo Debug::vars('30', $sql, $e->getMessage()); exit;
			Log::instance()->add(Log::DEBUG, 'Line 40 '. $e->getMessage());
		
		}
		return $query;
	}
	
	/**Получить список всех машиномест
	*
	*/
	public function _getPlaceListforAllParking()
	{
	$sql='select hlr.id, hlr.placenumber, hlr.description, hlr.note, hlr.status, hlr.name, hlr.id_parking, hlr.created , hlg.id_garagename
			from hl_place hlr
			left join hl_garage hlg on hlg.id_place=hlr.id';
	$query=array();		
		try
		{
			$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();
			
			
			//echo Debug::vars('49', $sql, $query, $this); exit;
			
		} catch (Exception $e) {
			////echo Debug::vars('30', $sql, $e->getMessage()); exit;
			Log::instance()->add(Log::DEBUG, 'Line 40 '. $e->getMessage());
		
		}
		return $query;
	}
	
	
	
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
	
	// получить список id машиномест для указанной парковки
	public function getChild($parent)
	{
		$sql='select hlp.id from hl_place hlp
			where hlp.id_parking='.$parent.'
			order by hlp.placenumber';
		
		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();
		return $query;
	}
	
	/**10.05.2025
	*@info получить набор данных для указанной парковочной площадки
	*/
	public function getChild_2($parent)
	{
		$sql='select * from hl_place hlp
			where hlp.id_parking='.$parent.'
			order by hlp.placenumber';
		//echo Debug::vars('41', $sql);//exit;
		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array()
			;
		return $query;
	}
	
	
	// получить id всех машиномест
	public function getAll()
	{
		$sql='select hlp.id from hl_place hlp';
			
		//echo Debug::vars('41', $sql);//exit;
		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();
		return $query;
	}
	
	
	
	
	/*11.04.2025 Проверка номера машиноместа в указанной парковке на уникальность
	*если номер машиноместа в парковке есть, то возвращается true
	*если номера машиноместа в парковке нет, то возвращается false
	*/	
	public static function isPresent_numberPlace($placenumber, $id_parking)
	//public static function unique_numberPlace($data)
		{
		//echo Debug::vars('68', $placenumber, $id_parking, Database::instance('fb'));exit; 
		
		// echo Debug::vars('181', $placenumber, $id_parking);//exit; 
			$sql='select count(id) as total from hl_place hlp
					where hlp.placenumber='.$placenumber.'
					and hlp.id_parking='.$id_parking;
		//echo Debug::vars('184', $sql);//exit;			
			$total= DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->get('TOTAL');	
		if($total == 0) return false;
			return true;		
					
	
		}
	
	
}
