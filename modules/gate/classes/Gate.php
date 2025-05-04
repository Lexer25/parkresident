<?php
/* Gate 
07.04.2025
Класс описывает ворота

*/

class Gate
{
			public $id; //id ворот
			//public $id_gate; //
            public $id_parking=1;//id парковки, которую обслуживают ворота
            public $is_enter=1;//
            public $name;//название
            public $tablo_ip='192.168.0.100';// IP адрес контроллера табло 
            public $tablo_port = 1985;// PORT контроллера табло 
            public $box_ip='192.168.0.100';//IP адрес контроллера реле 
            public $box_port=1985;//PORT контоллера реле 
            public $channel=0;//канал контроллера (0 или 1 для МПТ)
            public $id_cam=44;//id камеры от cvs 
            public $id_dev=-1;// id точки проезда 
            public $mode=0;// режим работы
            public $messageIdlleTop='Parking';//надпись в режиме ожидания верхняя
            public $messageIdlleDown='ARTSEC';// надпись в режиме ожидания нижняя
            public $dev_name = 'dev_name';// название точки прохода в СКУД
	
	
	
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
            hlp.channel, 
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
				$this->channel=Arr::get($value,'CHANNEL');
				$this->id_cam=Arr::get($value,'ID_CAM');
				$this->id_dev=Arr::get($value,'ID_DEV');
				$this->mode=Arr::get($value,'MODE');
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
		
		$sql='INSERT INTO HL_PARAM (
				TABLO_IP,
				TABLO_PORT,
				BOX_IP,
				BOX_PORT,
				CHANNEL,
				
				ID_CAM,
				ID_DEV,
				MODE,
				NAME,
				ID_PARKING,
				IS_ENTER)
				
				VALUES
				
			(\''.$this->tablo_ip.'\',
				'.$this->tablo_port.',
				\''.$this->box_ip.'\',
				'.$this->box_port.',
				'.$this->channel.',
				
				'.$this->id_cam.',
				'.$this->id_dev.',
				'.$this->mode.',
				\''.$this->name.'\',
				'.$this->id_parking.',
				'.$this->is_enter.'
				)';	
			
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
		$this->mode=1;
		$sql='UPDATE HL_PARAM SET 
			
				TABLO_IP = \''.$this->tablo_ip.'\',
				TABLO_PORT = '.$this->tablo_port.',
				BOX_IP = \''.$this->box_ip.'\',
				BOX_PORT = '.$this->box_port.',
				CHANNEL = '.$this->channel.',
				
				ID_CAM = '.$this->id_cam.',
				ID_DEV = '.$this->id_dev.',
				MODE = '.$this->mode.',
				NAME = \''.$this->name.'\',
				ID_PARKING = '.$this->id_parking.',
				IS_ENTER = '.$this->is_enter.'
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
		
		$sql='delete from hl_param
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
