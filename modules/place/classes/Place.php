<?php



/* Place 
4.04.2025
Класс описывает машиноместо

*/

class Place
{
	public $id;
	public $placenumber;
	public $id_counters;
	public $description;
	public $note;
	public $status;
	public $name;
	public $id_parking;	
	public $is_active;
	public $created;
	public $modify;//дата последнего изменения
	public $mess;//сообщения всякие
	
	
	
	public function __construct($id=null)
    {
       if(!is_null($id))//если указан id, то создаю экземпляр класса с данными из БД.
	   {
	   $this->id = $id;
		$sql='select hlr.id, hlr.placenumber, hlr.description, hlr.note, hlr.status, hlr.name, hlr.id_parking, hlr.created from hl_place hlr where hlr.id ='.$this->id;
		try
		{
			$query = Arr::flatten(DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array());
			$this->name=Arr::get($query, 'NAME');
			$this->description=Arr::get($query, 'DESCRIPTION');
			$this->note=Arr::get($query, 'NOTE');
			$this->status=Arr::get($query, 'STATUS');
			$this->created=Arr::get($query, 'CREATED');
			//$this->modify=Arr::get($query, 'MODIFY');
			$this->placenumber=Arr::get($query, 'PLACENUMBER');
			$this->id_parking=Arr::get($query, 'ID_PARKING');
			
			
			//echo Debug::vars('23', $sql, $query); exit;
			
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
		//echo Debug::vars('36', $this->name, $this->standalone);
		//hlr.id, hlr.name, hlr.is_active, hlr.created, hlr.modify
		
		$sql='UPDATE HL_PLACE
				SET NAME = \''.$this->name.'\',
				PLACENUMBER = '.$this->placenumber.',
				description = \''.$this->description.'\',
				note = \''.$this->note.'\',
				status = '.$this->status.',
				id_parking = '.$this->id_parking.'
			WHERE (ID = '.$this->id.')';
		Log::instance()->add(Log::DEBUG, 'Line 101 '. $sql);
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
