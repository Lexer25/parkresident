<?php



/* Events 
Класс для работы с событиями

*/

class Events
{
   private $result_ok='OK';
	private $result_err='Err';
	public $result;//результат выполнение метода OK - выполнен правильно, Err - выполнен с ошибкой
	public $rdesc;// результат выполнения метода: набор данных или ошибок
	
	public $id;// id указатель на код события
	public $name;//название события
	public $color;//цвет события
	
	public $is_present=false;//true - есть данные для указанного id, false - нет данных для указанного id
	public $not_count=false;//1 - НЕ вести подсчет кол-ва свободных мест. NULL и другие значения - вести подсчет
	public $div_code;//код уникальные идентификатор гаража
	public $eventList;//список кодов событий, связанных с этим гаражом

	
	
	
	 public function __construct($id=null)
    {
       if(!is_null($id))//если указан id, то создаю экземпляр класса с данными из БД.
	   {
	   $this->id = $id;
		$sql='select hlec.id, hlec.name, hlec.color from hl_eventcode hlec
			where hlec.id='.$this->id;
		try
		{
			$query = Arr::flatten(DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array());
			$this->name=Arr::get($query, 'NAME');
			$this->color=Arr::get($query, 'COLOR');
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
	
	*/
	public function getListEventsForGarage($id_events)
	{
		
	$sql='select hle.event_time, hle.event_code, hlp.is_enter, hlp.name as gate_name, hlp.id_parking, hle.grz, hle.id_pep, hle.id_gate, hle.comment, et.name as event_name,  p.surname, p.name, p.PATRONYMIC, hl_org.id_garage
            from hl_events  hle
            left join hl_param hlp on hlp.id_dev=hle.id_gate
            left join hl_eventcode  et on et.id=hle.event_code
            left join card c on c.id_card=hle.grz
            left join people p on p.id_pep=c.id_pep
            join hl_orgaccess hl_org on hl_org.id_org=p.id_org
            where hle.id in ('.implode(",", $id_events).')
			order by hle.event_time desc';	
			

		
		try
		{
			$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();
			
			return $query;
		} catch (Exception $e) {
			////echo Debug::vars('30', $sql, $e->getMessage()); exit;
			Log::instance()->add(Log::DEBUG, 'Line 52 '. $e->getMessage());
		
		}
		
		return ($id_events);
	}
	
	/*
	30.08.2023
	получить список id событий по этому гаражу
	*/
	public function getEvents()
	{
		$sql='select e.id from hl_orgaccess hlo
			join people p on p.id_org=hlo.id_org
			join card c on c.id_pep=p.id_pep and c.id_cardtype=4
			join hl_events e on e.grz=c.id_card
			join hl_eventcode ec on ec.id=e.event_code
			where hlo.id_garage='.$this->id.'
			and ec.id not in (46)';
		
		$sql2='select ec.name, e.id, e.event_time, e.grz, e.id_pep, e.id_gate, e.created  from hl_orgaccess hlo
			join people p on p.id_org=hlo.id_org
			join card c on c.id_pep=p.id_pep and c.id_cardtype=4
			join hl_events e on e.grz=c.id_card
			join hl_eventcode ec on ec.id=e.event_code
			where hlo.id_garage='.$this->id.'
			and ec.id not in (46)';
		
		
		try
		{
			$query = DB::query(Database::SELECT, $sql1)
			->execute(Database::instance('fb'))
			->as_array();
			foreach($query as $key=>$value)
			{
				$res[]=$value;
				//$res[]=Arr::get($value, '');
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
