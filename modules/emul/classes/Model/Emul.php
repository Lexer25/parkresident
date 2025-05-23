<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
* @package    ParkResident/Emul
 * @category   Base
 * @author     Artonit
 * @copyright  (c) 2025 Artonit Team
 * @license    http://artonit/ru 
 
 */

class Model_Emul extends Model {
	
	
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
			where hlp.id_parking='.$parent;
		//echo Debug::vars('41', $sql);//exit;
		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();
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
	*/	
	//public static function unique_numberPlace($placenumber, $id_parking)
	public static function unique_numberPlace($data)
		{
		//echo Debug::vars('68', $data);exit; 
		 // Check if the username already exists in the database
			$sql='select * from hl_place hlp
					where hlp.placenumber=2
					and hlp.id_parking=1';
			return ! DB::select(array(DB::expr('COUNT(id)'), 'total'))
				->from('hl_place')
				->where('placenumber', '=', $placenumber)
				->and_where('id_parking', '=', $id_parking)
				->execute()
				->get('total');
		}
	

		/*06.05.2025 Замена номера ворот на видеокамеру
	*/	
	//public static function unique_numberPlace($placenumber, $id_parking)
	public static function unique_numberPlace_2($data)
		{
		//echo Debug::vars('68', $data);exit; 
		 // Check if the username already exists in the database
			$sql='select * from hl_place hlp
					where hlp.placenumber=2
					and hlp.id_parking=1';
			return ! DB::select(array(DB::expr('COUNT(id)'), 'total'))
				->from('hl_place')
				->where('placenumber', '=', $placenumber)
				->and_where('id_parking', '=', $id_parking)
				->execute()
				->get('total');
		}
	

		
		
	/**02.05.2025 Получить список ГРЗ для работы в эмуляторе
	*/
	public function getListIdCard($id_cardtype)
	{
		$sql='select c.id_card from card c
		where c.id_cardtype='.$id_cardtype.'
		and c.id_card not containing \' \'';
		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();
		return $query;
		
	}
	
	
	/**02.05.2025 Получить список cam из настроект системы
	* с этим видеокамер могут приходить ГРЗ
	*/
	public function getListIdCam()
	{
		$sql='select hlp.id_cam from hl_param hlp';
		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();
		return $query;
		
	}
	
	
	
	/**02.05.2025 Получить список ворот
	* от этих ворот будут приходить коды UHF
	*/
	public function getListIdGate()
	{
		$sql='select hlp.id from hl_param hlp';
		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();
		return $query;
		
	}
	
	
	
	
	
	
	
}
