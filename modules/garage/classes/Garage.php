<?php



/* Garage 
Класс для работы с сущностью Гараж

*/

class Garage
{
   private $result_ok='OK';
	private $result_err='Err';
	public $result;//результат выполнение метода OK - выполнен правильно, Err - выполнен с ошибкой
	public $rdesc;// результат выполнения метода: набор данных или ошибок
	
	public $id;// id указатель на уникальной номер сущности
	public $name;//имя сущности
	public $is_present=false;//true - есть данные для указанного id, false - нет данных для указанного id
	public $not_count=false;//1 - НЕ вести подсчет кол-ва свободных мест. NULL и другие значения - вести подсчет
	public $div_code;//код уникальные идентификатор гаража
	public $eventList;//список кодов событий, связанных с этим гаражом

	
	
	
	 public function __construct($id=null)
    {
       if(!is_null($id))//если указан id, то создаю экземпляр класса с данными из БД.
	   {
	   $this->id = $id;
		$sql='select hln.name, hln.created, hln.not_count, hln.div_code from hl_garagename hln
				where hln.id='.$this->id;
		try
		{
			$query = Arr::flatten(DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array());
			$this->name=Arr::get($query, 'NAME');
			$this->div_code=Arr::get($query, 'DIV_CODE');
			$this->not_count=Arr::get($query, 'NOT_COUNT');
			$this->is_present=true;
			
			//echo Debug::vars('23', $sql, $query); exit;
			
		} catch (Exception $e) {
			////echo Debug::vars('30', $sql, $e->getMessage()); exit;
			Log::instance()->add(Log::DEBUG, 'Line 52 '. $e->getMessage());
		
		}
	   } else { // если не указан id, то создаю пустой экземпляр класса
			
	   }
	}
	
	
	/*
	30.08.2023
	получить список id событий по этому гаражу
	*/
	public function getEvents()
	{
		$sql='select first 1500 e.id from hl_orgaccess hlo
			join people p on p.id_org=hlo.id_org
			join card c on c.id_pep=p.id_pep and c.id_cardtype=4
			join hl_events e on e.grz=c.id_card
			join hl_eventcode ec on ec.id=e.event_code
			where hlo.id_garage='.$this->id.'
			and ec.id not in (46)
			 order by e.id';
		
		
		
		$res=array();
		try
		{
			$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();
			foreach($query as $key=>$value)
			{
				//$res[]=$value;
				$res[]=Arr::get($value, 'ID');
			}
			
			return $res;
		} catch (Exception $e) {
			////echo Debug::vars('30', $sql, $e->getMessage()); exit;
			Log::instance()->add(Log::DEBUG, 'Line 52 '. $e->getMessage());
		
		}
	 
	}
	
	
	
	/*
	26.08.2023
	Сохранение данных
	*/
	
	public function save()
	{
		//echo Debug::vars('36', $this->name, $this->standalone);
		 $sql=__('INSERT INTO TABLE (NAME, standalone)
			VALUES (\':NAME\', :standalone)', 
		array(
			':NAME'=>$this->name,
			':standalone'=>$this->standalone,
			));
	//echo Debug::vars('45', $sql); exit;
		try
			{
				$query = DB::query(Database::INSERT, iconv('UTF-8', 'CP1251',$sql))
				->execute(Database::instance('fb'));
				$this->result=$this->result_ok;
				$this->edesc='OK';
			} catch (Exception $e) {
				Log::instance()->add(Log::DEBUG, 'Line 83 '. $e->getMessage());
				$this->result=$this->result_err;
				$this->edesc=$e->getMessage();				
			}
	}
	
	/*
	26.08.2023
	Изменение данных для указанного id
	*/
	public function update()
	{
		//echo Debug::vars('36', $this->name, $this->standalone);
		
		$sql='UPDATE TABLE
				SET NAME = \''.$this->name.'\',
				STANDALONE = '.$this->standalone.'
			WHERE (ID_DEVTYPE = '.$this->id.') AND (ID_DB = 1)';
		Log::instance()->add(Log::DEBUG, 'Line 101 '. $sql);
		//echo Debug::vars('65', $sql); exit;
		try
			{
			$query = DB::query(Database::UPDATE, iconv('UTF-8', 'CP1251',$sql))
			->execute(Database::instance('fb'));
			
			$this->result=$this->result_ok;
			$this->edesc=$this->id;
			
			} catch (Exception $e) {
				Log::instance()->add(Log::DEBUG, 'Line 112 '. $e->getMessage());
				$this->result=$this->result_err;
				$this->edesc=$e->getMessage();				
			}
	}
	
	
	/*
	26.08.2023
	Удаление данных для указанного id
	*/
	public function delete()
	{
		//echo Debug::vars('36', $this->name, $this->standalone);
		
		$sql='delete from ...';
		Log::instance()->add(Log::DEBUG, 'Line 72 '. $sql);
		//echo Debug::vars('65', $sql); exit;
		try
			{
			$query = DB::query(Database::DELETE, iconv('UTF-8', 'CP1251',$sql))
			->execute(Database::instance('fb'));
			
			$this->result=$this->result_ok;
			$this->edesc=$this->id;
			
			} catch (Exception $e) {
				Log::instance()->add(Log::DEBUG, 'Line 139 '. $e->getMessage());
				$this->result=$this->result_err;
				$this->edesc=$e->getMessage();				
			}
	}
	
	
   
}
