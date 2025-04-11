<?php defined('SYSPATH') OR die('No direct access allowed.');

class Model_Parkdb extends Model {
	
	/*11.04.2025 информация по подключенной базе данных
	*/
	public function aboutDB($sourcename)
	{
		$_fbinfo=Kohana::$config->load('database')->$sourcename;
		$_connection=Arr::get($_fbinfo, 'connection');
		$_dsn=Arr::get($_connection, 'dsn');
		//echo Debug::vars('13', Arr::get(explode(":", $_dsn), 1));

		//$reg=shell_exec('C:\Windows\system32\reg.exe query "HKEY_LOCAL_MACHINE\SOFTWARE\Wow6432Node\ODBC\ODBC.INI\SDuo" /v "Database"');
		$reg=shell_exec('C:\Windows\system32\reg.exe query "HKEY_LOCAL_MACHINE\SOFTWARE\Wow6432Node\ODBC\ODBC.INI\\'.Arr::get(explode(":", $_dsn), 1).'" /v "Database"');
		$_aaa=explode("REG_SZ", $reg);
		return array('connectName'=>$sourcename,
				'dsn'=>$_dsn,
				'pathDB'=>trim(Arr::get($_aaa, 1))
				);
		
	}
	
	/*
	20.03.2025 Проверка наличия указанных таблицы
	
	*/
	public function checkTableIsPresent($table)
	{
		$res=array();
		$sql='select distinct RDB$RELATION_NAME
				from RDB$RELATION_FIELDS
				where RDB$RELATION_NAME=\''.$table.'\'';
		//echo Debug::vars('12', $sql); exit;
		
		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();
		if($query) return true;
		return false;
		
	}
	
	
	/*
	20.03.2025 Проверка наличия указанных процедур
	
	*/
	public function checkProcedureIsPresent($name)
	{
		$res=array();

		$sql='select distinct * from  RDB$PROCEDURES
			where RDB$PROCEDURE_name = \''.$name.'\'';
		
		//echo Debug::vars('12', $sql); exit;
		
		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();
		if($query) return true;
		return false;
	}
	
	/*
	20.03.2025 Проверка наличия указанных процедур
	
	*/
	public function checkGeneratorIsPresent($name)
	{
		$res=array();

		$sql='select distinct * from rdb$GENERATORS
    where rdb$GENERATOR_name=\'GEN_'.$name.'_ID\'';
		
		//echo Debug::vars('12', $sql); exit;
		
		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();
		if($query) return true;
		return false;
	}
	
	
	
	
	public function makeQuery($query)
	{
		try{
			Database::instance('fb')->query(NULL, $query);
		} catch (Exception $e) {
			echo Debug::vars('99', $e->getMessage());
		}
		
	}
	
	
	public function aboutTable($tableName)
	{
		
		$sql='select Rdb$Description from Rdb$Relations
			where Rdb$Relation_Name=\''.$tableName.'\'';
		//	echo Debug::vars('87', $sql);exit;
		//$this->makeQuery($sql);
		
		$query = Arr::flatten(DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array());
		return $query;
		
		
	}
	
	public function delTable($tableName)
	{
		$this->delGenerator($tableName);
		$this->makeQuery('DROP TABLE '. $tableName);
		
	}
	
	
	
	
	public function delTableData($tableName)
	{
		$this->delGenerator($tableName);
		$this->makeQuery('delete from '. $tableName);
		
	}
	
	//Добавление данных в указанную таблицу
	public function addTableData($name)
	{
		//echo Debug::vars('99', $name.'.sql');exit;
		$ttt='"C:\Program Files (x86)\Firebird\Firebird_1_5_6\bin\isql.exe" localhost/3050:c:\vnii\vnii.GDB -user sysdba -pass temp -i C:\xampp\htdocs\parkresident\modules\setup\config\sql\data\\'.$name.'.sql';
		exec(iconv('UTF-8', 'CP1251', $ttt));
		
	}
	
	
	
	//31.03.2025 Добавление таблицы сводится к выполнению нескольких sql запросов, взятых из файла конфигурации.
	public function addTable($tableName)
	{
		$this->makeQuery('DROP TABLE '. $tableName);
		//выбираю набор команд для указанной таблицы
		
		/* $sqlarray=Arr::get(Kohana::$config->load('artonitparking_table'), $tableName, null);//выбираю набор команд для добавления таблицы.
			if($sqlarray)
			{
				//выполняю команды в цикле
				foreach($sqlarray as $key=>$value){
					$result = $this->makeQuery(iconv('UTF-8', 'CP1251', $value));
				}
				
			} else {
				
				echo Debug::vars('102 нет данных для таблицы '. Arr::get($_POST, 'addTable'));//exit;
			}
			 */
		$ttt='"C:\Program Files (x86)\Firebird\Firebird_1_5_6\bin\isql.exe" localhost/3050:c:\vnii\vnii.GDB -user sysdba -pass temp -i C:\xampp\htdocs\parkresident\modules\setup\config\sql\\'.$tableName.'.sql';
			
			
		 exec(iconv('UTF-8', 'CP1251', $ttt));
	}
	
	public function delGenerator($name)
	{
		$this->makeQuery('DROP GENERATOR GEN_'. $name.'_ID');
		
		
	}
	
	public function delProcedure($name)
	{
		$this->makeQuery('DROP PROCEDURE '. $name);
	}
	
	
	//31.03.2025 ДОбавление процедуры сводится к выполнению скрипта, взятого из файлов.
	public function addProcedure($name)
	{
		$ttt='"C:\Program Files (x86)\Firebird\Firebird_1_5_6\bin\isql.exe" localhost/3050:c:\vnii\vnii.GDB -user sysdba -pass temp -i C:\xampp\htdocs\parkresident\modules\setup\config\sql\\'.$name.'.sql';
			
			
		 exec(iconv('UTF-8', 'CP1251', $ttt));
	}
	
	
	
}
