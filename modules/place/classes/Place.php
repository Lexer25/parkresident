<?php
/**
* @package    ParkResident/Place
 * @category   Base
 * @author     Artonit
 * @copyright  (c) 2025 Artonit Team
 * @license    http://artonit/ru 
 
 */


/* Place 
4.04.2025
Класс описывает машиноместо

*/

class Place
{
	public $id;
	public $placenumber;
	public $description;
	public $note;
	public $status;
	public $name;
	public $id_parking;	
	public $created;
	public $modify;//дата последнего изменения
	public $mess;//сообщения всякие
	public $id_garage;//id гаража, куда входит машиноместо
	
	
	
	public function __construct($id=null)
    {
       if(!is_null($id))//если указан id, то создаю экземпляр класса с данными из БД.
	   {
	  
		$sql='select hlr.id, hlr.placenumber, hlr.description, hlr.note, hlr.status, hlr.name, hlr.id_parking, hlr.created , hlg.id_garagename
			from hl_place hlr
			left join hl_garage hlg on hlg.id_place=hlr.id
			where hlr.id ='.$id;
		try
		{
			$query = Arr::flatten(DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array());
			$this->id=Arr::get($query, 'ID');
			$this->name=Arr::get($query, 'NAME');
			$this->description=Arr::get($query, 'DESCRIPTION');
			$this->note=Arr::get($query, 'NOTE');
			$this->status=Arr::get($query, 'STATUS');
			$this->created=Arr::get($query, 'CREATED');
			$this->placenumber=Arr::get($query, 'PLACENUMBER');
			$this->id_parking=Arr::get($query, 'ID_PARKING');
			$this->id_garage=Arr::get($query, 'ID_GARAGENAME');
			
			
			//echo Debug::vars('49', $sql, $query, $this); exit;
			
		} catch (Exception $e) {
			
			Log::instance()->add(Log::DEBUG, 'Line 40 '. $e->getMessage());
		
		}
	   } else { // если не указан id, то создаётся пустой экземпляр класса
		   $sql = 'SELECT GEN_ID(GEN_HL_PLACE_ID,1) FROM RDB$DATABASE';//тут выбирается генератор таблицы hl_garagename имена гаражей
			$query = DB::query(Database::SELECT, $sql)
				->execute(Database::instance('fb'))
				->current();
			
			$this->id=$query['GEN_ID'];
			
	   }
	}
	
	
		
	/**
	*26.08.2023 добавление машиноместа
	*@input PLACENUMBER, DESCRIPTION, NOTE, ID_PARKING номер машиноместа, описание, еще одно описание, id паркинга
	*/
	
	public function add()
	{
		$sql='INSERT INTO HL_place (ID, PLACENUMBER, DESCRIPTION, NOTE, ID_PARKING)
			values ('.$this->id.','.$this->placenumber .', \''.$this->description.'\',\''.$this->note.'\','.$this->id_parking .')';
			//echo Debug::vars('88 '.$sql);exit;
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
	Удаление указанного машиноместа по его id
	*/
	public function del()
	{
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
	
	public function getFromNumberPlaceAndIdParking($numberplace, $id_parking)
	{
		$sql='select hlp.id from hl_place hlp
			where hlp.placenumber ='.$numberplace.'
			and hlp.id_parking='.$id_parking;
		$id = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->get('ID');
		
		return $this->__construct($id);
	}
   
}
