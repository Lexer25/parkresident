<?php defined('SYSPATH') OR die('No direct access allowed.');

class Model_Parkdb extends Model {
	
	/*11.04.2025 информация по подключенной базе данных
	*/
	
	//путь к базе данных
	public $_connectName='fb';
	public $db_path;
	public $mess;
	
	public function __construct($_connectName='fb')
	{
		
		
		$this->db_path = iconv('cp866','UTF-8//IGNORE', Arr::get($this->aboutDB($_connectName), 'pathDB'));
		
	}
	
		
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
		Log::instance()->add(Log::DEBUG, '173 выполняется запрос: '. $query);
		try{
			$this->mess = Database::instance('fb')->query(NULL, $query);
			Log::instance()->add(Log::DEBUG, '108 Запрос выполнен успешно  '.$this->mess);
			return true;
		} catch (Exception $e) {
			//echo Debug::vars('99', $e->getMessage());
			$this->mess = $e->getMessage();
			Log::instance()->add(Log::DEBUG, '113 Запрос выполнен с ошибкой  '.$this->mess);
			return false;
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
		/* Log::instance()->add(Log::DEBUG, '117 Удадение таблицы '.$tableName);
		if($this->checkGeneratorIsPresent($name)) 
		{
			Log::instance()->add(Log::DEBUG, '173 Генератора :gen присутвует. Начинается его удаление.', array(':gen'=>$name));
			$this->makeQuery('DROP GENERATOR GEN_'. $name.'_ID');
		} else {
			Log::instance()->add(Log::DEBUG, '176 Генератора :gen Отсутсвует, удалять ничего не надо.', array(':gen'=>$name));
		}
		
		$this->delGenerator($tableName); */
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
		$ttt='"C:\Program Files (x86)\Firebird\Firebird_1_5_6\bin\isql.exe" localhost/3050:'.$this->db_path.' -user sysdba -pass temp -i C:\xampp\htdocs\parkresident\modules\setup\config\sql\data\\'.$name.'.sql';
		exec(iconv('UTF-8', 'CP1251', $ttt));
		
	}
	
	
	
	//31.03.2025 Добавление таблицы сводится к выполнению нескольких sql запросов, взятых из файла конфигурации.
	public function addTable($tableName)
	{
		$retval=null;	
		$output=null;		
		$ttt='"C:\Program Files (x86)\Firebird\Firebird_1_5_6\bin\isql.exe" localhost/3050:'.$this->db_path.' -user sysdba -pass temp -i C:\xampp\htdocs\parkresident\modules\setup\config\sql\\'.$tableName.'.sql';
			
		Log::instance()->add(Log::DEBUG, Debug::vars('158 выполняю команду добавления таблицы :', iconv('UTF-8', 'CP1251', $ttt)));	
		$result=exec(iconv('UTF-8', 'CP1251', $ttt), $retval, $output);
		/* echo Debug::vars('181', $result, $output);exit;
		Log::instance()->add(Log::DEBUG, '159 результат добавления таблицы : '. Debug::vars($result));	*/
		Log::instance()->add(Log::DEBUG, '159-1 результат добавления таблицы : '. Debug::vars($output)); 	
		if($output==0) return true;
		return false;
		// echo Debug::vars('159 результат добавления таблицы :', exec(iconv('UTF-8', 'CP1251', $ttt)));
	}
	
	public function delGenerator($name)
	{
		
			$this->makeQuery('DROP GENERATOR GEN_'. $name.'_ID');
		
		
		
	}
	
	public function delProcedure($name)
	{
		
		return $this->makeQuery('DROP PROCEDURE '. $name);
	}
	
	
	//31.03.2025 ДОбавление процедуры сводится к выполнению скрипта, взятого из файлов.
	public function addProcedure($name)
	{
		$ttt='"C:\Program Files (x86)\Firebird\Firebird_1_5_6\bin\isql.exe" localhost/3050:'.$this->db_path.' -user sysdba -pass temp -i C:\xampp\htdocs\parkresident\modules\setup\config\sql\\'.$name.'.sql';
			
			
		 exec(iconv('UTF-8', 'CP1251', $ttt));
	}
	
	
	
}
