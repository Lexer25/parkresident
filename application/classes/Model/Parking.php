<?php defined('SYSPATH') OR die('No direct access allowed.');

class Model_Parking extends Model {
	
/**
* @package    ParkResident/Application
 * @category   Base
 * @author     Artonit
 * @copyright  (c) 2025 Artonit Team
 * @license    http://artonit/ru 
 
 */
	 
	public function get_list_parking($id_parking=null)// получить список парковок для указанного жилого копмлекса
	{
		$res=array();
		if ($id_parking==null)
		{$sql='select hlp.id, hlp.name, hlp.enabled, count(hlpl.id) as MAXCOUNT from hl_parking hlp
                left join hl_place hlpl on hlpl.id_parking=hlp.id
				where hlp.parent =14
				group by hlp.id, hlp.name, hlp.enabled, hlp.maxcount';
		} else 
		{
		$sql='select hlp.id, hlp.name, hlp.enabled, count(hlpl.id) as MAXCOUNT from hl_parking hlp
                left join hl_place hlpl on hlpl.id_parking=hlp.id
				where hlp.parent ='.$id_parking.'
				group by hlp.id, hlp.name, hlp.enabled, hlp.maxcount';
		}
		//echo Debug::vars('12', $sql); exit;
		
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
	
	
	public function count_busy()// подсчет количество занятых мест на парковке
	{
		
		//echo Debug::vars('12', Arr::get($data, 'id_card'), Arr::get($data, 'COMMENT')); exit;
		
		$sql='select hli.counterid, count(*) from hl_inside hli
group by hli.counterid';		
				
		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();
		$res=array();
		
		Foreach ($query as $key => $value)
		{
			$res[Arr::get($value, 'COUNTERID')]=Arr::get($value, 'COUNT');
					
		}
		
		return $res;	
	}
	

	
	public function action_edit()//редактировать и просматривать  парковку
	{
		$_SESSION['menu_active']='kp_park_menu';
		//echo Debug::vars('43', $_GET, $_POST, $this->request->param('id')); exit;
		$id_rubic = $this->request->param('id');
		$info_parking=Model::Factory('rubic')->get_info_parking($id_rubic); //получить общую информацию о парковке
		$list_key_into_parking=Model::Factory('rubic')->list_plate_into_parking($id_rubic); //получить список ГРЗ, находящихся на парковке
		$apb_device_list=Model::Factory('rubic')->get_list_dev($id_rubic); //получить лист точек прохода, уже входящих в периметр
		$door_list=Model::Factory('rubic')->door_list($id_rubic); //получить лист точек прохода, которые планирую включить во въезд-выезд парковки
		
		
		//echo Debug::vars('45', $door_list, $id_rubic); exit;
		
		$content = View::factory('rubic/edit_rubic', array(
			'rubic_getinfo'=>$info_parking,
			'list_key_into_parking' => $list_key_into_parking,
			'apb_device_list'=>$apb_device_list,
			'door_list'=>$door_list,
		));
        $this->template->content = $content;
		
	}
	
	public function get_load($id)// список автомобилей на парковке
	{
		$sql='select * from hl_inside hli
			where hli.counterid='.$id;
		//echo Debug::vars('43', $sql); exit;
		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();
		return $query;
		
	}
	
	public function del_grz($id)// удаление ГРЗ из списка inside
	{
		//echo Debug::vars('91', Arr::get($id, 'grz_for_del'), Arr::get($id, 'id_parking')); exit;
		$sql='delete from hl_inside hli
			where hli.id_card=\''.Arr::get($id, 'grz_for_del').'\'
			and hli.counterid='.Arr::get($id, 'id_parking');
		//echo Debug::vars('95', $sql); exit;
		try
				{
		$query = DB::query(Database::DELETE, $sql)
			->execute(Database::instance('fb'))
			;
			} catch (Exception $e) {
					Log::instance()->add(Log::NOTICE, '102 '. $e->getMessage());
				}
		return;
		
	}
	
	/*
	 получить список ворот для указанной парковки.
	 id_parking - номер парковки
	 is_enter - направление движения: 0 - выезд, 1 - выезд. если не указан, то будет передан список и въездов и выездов. 
	
	*/
	public function getListgate($id_parking, $is_enter=null)
	{
		
		$sql='select * from hl_param param
		where param.id_parking='.$id_parking;
		if(!is_null($is_enter))
		{
			$sql=$sql.' and param.is_enter='.$is_enter;
			
		}
		
		try
		{
		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array()
			;
			} catch (Exception $e) {
					Log::instance()->add(Log::NOTICE, '102 '. $e->getMessage());
				}
		//echo Debug::vars('95', $sql, $query); exit;
		return $query;
	
	}
	
	
	
	
}
