<?php



/* Place 
07.04.2025
Класс описывает ворота

*/

class Garage
{
			public $id; //id ворот
            public $id_parking;//id парковки, которую обслуживают ворота
            public $is_enter;//
            public $name;//название
            public $tablo_ip;// IP адрес контроллера табло 
            public $tablo_port;// PORT контроллера табло 
            public $box_ip;//IP адрес контроллера реле 
            public $box_port;//PORT контоллера реле 
            public $id_cam;//id камеры от cvs 
            public $id_dev;// id точки проезда 
            public $mode;// режим работы
            public $messageIdlleTop;//надпись в режиме ожидания верхняя
            public $messageIdlleDown;// надпись в режиме ожидания нижняя
	
	
	
	public function __construct($id=null)
    {
       if(!is_null($id))//если указан id, то создаю экземпляр класса с данными из БД.
	   {
	   $res=array();
		$res=array();
		$sql='select  
			hlp.id,
            hlp.id_parking,
            hlp.is_enter,
            hlp.name,
            hlp.tablo_ip, 
            hlp.tablo_port, 
            hlp.box_ip, 
            hlp.box_port, 
            hlp.id_cam, 
            hlp.id_dev, 
            hlp.mode from HL_PARAM hlp
			where hlp.id='.$id;
		
		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();
		$res=array();
		foreach ($query as $key => $value)
		{
			$this->id=Arr::get($value, 'ID');
			$this->id_parking=Arr::get($value,'ID_PARKING');
			$this->name=Arr::get($value,'NAME');
			$this->is_enter=Arr::get($value,'IS_ENTER');
			$this->tablo_ip=Arr::get($value,'TABLO_IP');
			$this->tablo_port=Arr::get($value,'TABLO_PORT');
			$this->box_ip=Arr::get($value,'BOX_IP');
			$this->box_ip=Arr::get($value,'BOX_IP');
			$this->box_port=Arr::get($value,'BOX_PORT');
			$this->id_cam=Arr::get($value,'ID_CAM');
			$this->id_dev=Arr::get($value,'ID_DEV');
			$this->mode=Arr::get($value,'MODE');
		}
		return $res;	
		} catch (Exception $e) {
			////echo Debug::vars('30', $sql, $e->getMessage()); exit;
			Log::instance()->add(Log::DEBUG, 'Line 40 '. $e->getMessage());
		
		}
	   } else { // если не указан id, то создаю пустой экземпляр класса
			
	   }
	}
	
	/*
	26.08.2023
	Сохранение данных
	*/
	
	public function add()
	{
		//echo Debug::vars('61', $this->name);exit;
		$sql='INSERT INTO HL_place (NAME)
			values (\''.$this->name.'\')';
			
			
		//echo Debug::vars('783', $sql);exit;
		try
				{
				$query = DB::query(Database::INSERT, iconv('UTF-8','windows-1251',$sql))
				->execute(Database::instance('fb'));
				return true;
				} catch (Exception $e) {
					Log::instance()->add(Log::NOTICE, '794 '. $e->getMessage());
					return false;
				}
		return;
	}
	
	/*
	26.08.2023
	Изменение данных для указанного id
	*/
	public function update()
	{
		//echo Debug::vars('36', $this);exit;
		//echo Debug::vars('93', get_object_vars($this));//exit;
		//hlr.id, hlr.name, hlr.is_active, hlr.created, hlr.modify
		
		$sql='UPDATE HL_PLACE
				SET NAME = \''.$this->name.'\',
				PLACENUMBER = '.$this->placenumber.',
				description = \''.$this->description.'\',
				note = \''.$this->note.'\',
				status = '.$this->status.',
				id_parking = '.$this->id_parking.'
			WHERE (ID = '.$this->id.')';
		Log::instance()->add(Log::DEBUG, 'Line 101 '. $sql);//exit;
		//echo Debug::vars('65', $sql); exit;
		try
			{
			$query = DB::query(Database::UPDATE, iconv('UTF-8', 'CP1251',$sql))
			->execute(Database::instance('fb'));
			return true;
			} catch (Exception $e) {
				Log::instance()->add(Log::DEBUG, 'Line 112 '. $e->getMessage());
				
				$this->mess=$e->getMessage();
				return 	false;
			}
	}
	
	
	/*
	26.08.2023
	Удаление указанного ЖК по его id
	*/
	public function del()
	{
		//echo Debug::vars('36', $this->name, $this->standalone);
		
		$sql='delete from hl_place
			where id='.$this->id;
		Log::instance()->add(Log::DEBUG, 'Line 72 '. $sql);
		//echo Debug::vars('65', $sql); exit;
		try
			{
			$query = DB::query(Database::DELETE, iconv('UTF-8', 'CP1251',$sql))
			->execute(Database::instance('fb'));
			
			return true;
			
			} catch (Exception $e) {
				Log::instance()->add(Log::DEBUG, 'Line 139 '. $e->getMessage());
				$this->mess=$e->getMessage();	
				return true;				
			}
	}
	
	
   
}
