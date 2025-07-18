<?php

/**

* @package    ParkResident/Garage
 * @category   Base
 * @author     Artonit
 * @copyright  (c) 2025 Artonit Team
 * @license    http://artonit/ru 
 
 */

/* Setting 
Класс для работы с таблицей HL_Setting

*/

class Setting
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

	
	
	
	 public function __construct()
    {
      
	}
	
	
	/*
	*30.08.2023
	*Получить массив имен параметров hl_setting
	**/
	public function getNameList()
	{
		$sql='select * from hl_setting';
		echo Debug::vars('72');exit;
		$res=array();
		try
		{
			$res = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();
			
			
		} catch (Exception $e) {
			////echo Debug::vars('30', $sql, $e->getMessage()); exit;
			Log::instance()->add(Log::DEBUG, 'Line 52 '. $e->getMessage());
		
		}
		echo Debug::vars('86', $res);exit;
		return $res;
	}
	
	/*
	*30.08.2023
	*получить данные из таблицы hl_setting
	*@input $name - имя переменной, которое надо получить.
	*@input $type - тип данных, которые надо взять. Значения: str и int
	*@input $smallname - поиск по короткому имени
	*/
	public function get($name)
	{
		$sql='select coalesce(hls.value_str, hls.value_int) from hl_setting hls
			where hls.name=\''.$name.'\'';
		//echo Debug::vars('99', $sql);//exit;
		$res=array();
		try
		{
			$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->get('COALESCE');
			$res=$query;
			
		} catch (Exception $e) {
			////echo Debug::vars('30', $sql, $e->getMessage()); exit;
			Log::instance()->add(Log::DEBUG, 'Line 52 '. $e->getMessage());
		}
		return $res;
	}
	
	
	
	/*
	17.07.2025
	Добавление данных
	*/
	
	public function add()
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
	
	/**
	17.07.2025
		Добавлени или обновление данных в таблице БД
		$input $name - название (имя) ключа
		$input $value - значение ключа
		$input $type - тип значения ключа: 'int' - integer, 'str' - string
	*/
	public function update($name, $value, $type)
	{
		//проверка: есть ли такой ключ в таблице?
		//если ключа нет, то добавляю его.
		//если ключ есть, то обновляю его.
		
		$sql='select * from hl_setting hls
			where hls.name=\''.$name.'\'';
		if(!DB::query(Database::INSERT, iconv('UTF-8', 'CP1251',$sql))
				->execute(Database::instance('fb')))
				{//ключ есть в базе данных. надо просто обновить.
					switch($type){
						case 'int':
							 $sql=__('INSERT INTO hl_setting (NAME, VALUE_INT)
								VALUES (\':NAME\', :value)', array(
									':NAME'=>$name,
									':value'=>$value
								)); 
						break;
						case 'str':
						 $sql=__('INSERT INTO hl_setting (NAME, VALUE_str)
								VALUES (\':NAME\', \':value\')', array(
									':NAME'=>$name,
									':value'=>$value
								)); 
						break;
					}
					//echo Debug::vars('179', $sql);exit;
						try
							{
								$query = DB::query(Database::UPDATE, iconv('UTF-8', 'CP1251',$sql))
								->execute(Database::instance('fb'));
							} catch (Exception $e) {
								Log::instance()->add(Log::DEBUG, 'Line 83 '. $e->getMessage());
							}
							
						
					
				} else {
					//если ключа нет, то добавляю его.
					switch($type){
						case 'int':
							 $sql=__('INSERT INTO hl_setting (NAME, VALUE_INT)
								VALUES (\':NAME\', :value)', array(
									':NAME'=>$name,
									':value'=>$value
								)); 
						break;
						case 'str':
						 $sql=__('INSERT INTO hl_setting (NAME, VALUE_str)
								VALUES (\':NAME\', \':value\')', array(
									':NAME'=>$name,
									':value'=>$value
								)); 
						break;
					}
					//echo Debug::vars('208', $sql);exit;
						try
							{
								$query = DB::query(Database::INSERT, iconv('UTF-8', 'CP1251',$sql))
								->execute(Database::instance('fb'));
							} catch (Exception $e) {
								Log::instance()->add(Log::DEBUG, 'Line 83 '. $e->getMessage());
							}
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
