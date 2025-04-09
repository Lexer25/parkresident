<?php defined('SYSPATH') or die('No direct script access.');
/*
20.03.2025 
Checkdb - контроллера для проверки базы данных.
цель проверки - проверить наличие необходимых таблиц в базе данных и возможность установить эти таблицы.

*/


class Controller_Checkdb extends Controller_Template { // класс описывает въезды и вызды (ворота) для парковочных площадок
	
	
	public $template = 'template';
	public $tableList=array(
			'HL_EVENTCODE',
			'HL_EVENTS',
			'HL_GARAGENAME',
			'HL_ORGACCESS',
			'HL_GARAGE',
			'HL_RESIDENT',
			'HL_INSIDE',
			'HL_MESSAGES',
			'HL_COUNTERS',
			'HL_PARAM',
			'HL_PARKING',
			'HL_PLACE',
			//'HL_PLACEGROUP',
			'HL_SETTING'
		);
		
	public	$procedureList=array(
				'HL_UPDATE_GARAGE_NAME',
				'VALIDATEPASS_HL_PARKING',
				'VALIDATEPASS_HL_PARKING_2',
				'VALIDATEPASS_HL_PARKING_3',
				'REGISTERPASS_HL_2',
			);
			
	public	$dataList=array(
				'HL_EVENTCODE',
				'HL_MESSAGES',
				'HL_RESIDENT',
				
			);
			
	
	
	public function before()
	{
			
			parent::before();
			$session = Session::instance();
	
	}
	
	
	public function action_index()
	{
		$_SESSION['menu_active']='rmo';
		$id_garage = $this->request->param('id');
		//echo Debug::vars('37');exit;
		$tableList=$this->tableList;
		$procedureList=$this->procedureList;
		$dataList=$this->dataList;
		
		
		$db=Model::factory('Parkdb');
		foreach($tableList as $key=>$value)
		{
			//echo Debug::vars('54', $value, $db->checkTableIsPresent($value));//exit;
			$tableListCheck[$value]=$db->checkTableIsPresent($value);
			
		}
		//echo Debug::vars('58', $tableListCheck);exit;
		//$db=Model::factory('Parkdb');
		foreach($procedureList as $key=>$value)
		{
			//echo Debug::vars('60', $value, $db->checkProcedureIsPresent($value));//exit;
			$procedureListCheck[$value]=$db->checkProcedureIsPresent($value);
			
		}
		
		$content = View::factory('tableList', array(
			'tableList'=>$tableList,
			'tableListCheck'=>$tableListCheck,
			'procedureList'=>$procedureList,
			'procedureListCheck'=>$procedureListCheck,
			'dataList'=>$dataList,
				
		));
        $this->template->content = $content;
		
	}
	
	
	//25.03.2025 метод для обработки запросов в части добавления и удаления таблиц.
	//для добавления таблицы должен быть создан массив, в котором последовательно перечислены нужные команды.
	
	public function action_worker()
	{
		echo Debug::vars('81', $_POST);
		echo Debug::vars('82', Arr::get($_POST, 'addTable'));
		echo Debug::vars('83', Arr::get($_POST, 'delTable'));//exit;
		//обработка добавления таблицы.
		$parkDB=Model::factory('Parkdb');
		
		
		
		//31.03.2025 удалить все таблицы
		if(Arr::get($_POST, 'delAllTable'))
		{
			//сначала все удаляю
			foreach($this->procedureList as $key=>$value)
			{
				
				
				$parkDB->delTable(iconv('UTF-8', 'CP1251', $value));
				
			}
				
			
			foreach($this->tableList as $key=>$value)
			{
				try{
					Database::instance('fb')->query(NULL, 'DROP TABLE '. $value);
				} catch (Exception $e) {
				echo Debug::vars('105', $e->getMessage());
			}	
			}
			$this->redirect('/checkdb');
		}
		
		
		//31.03.2025 добавить все таблицы и процедуры
		if(Arr::get($_POST, 'addAllTable'))
		{
						
			foreach($this->tableList as $key=>$value)
			{
				//$parkDB->delTable($value);//удаляю таблицу (вдруг она есть в базе данных)
				$parkDB->addTable($value);
			}
			
			
			foreach($this->procedureList as $key=>$value)
			{
				$parkDB->addProcedure($value);
			}	
			
			
			
			
			
			$this->redirect('/checkdb');
		}
		
		
		
		if(Arr::get($_POST, 'addTable'))
		{
			
			$tableData=Arr::get($_POST, 'addTable'); //получил название таблицы
			$parkDB->delTable($tableData);//удаляю таблицу (вдруг она есть в базе данных)
			$parkDB->addTable($tableData);
			$this->redirect('/checkdb');
		}
		
		//7.04.2025 добавить данные
		if(Arr::get($_POST, 'addData'))
		{
			
			$tableData=Arr::get($_POST, 'addData'); //получил название таблицы
			//$parkDB->delTable($tableData);//удаляю таблицу (вдруг она есть в базе данных)
			$parkDB->addData($tableData);
			$this->redirect('/checkdb');
		}
		
		
		
		
		if(Arr::get($_POST, 'delTable'))
		{
		
			$db=Model::factory('Parkdb');
		
			try{
				echo Debug::vars('103 drop table result: ', Database::instance('fb')->query(NULL, 'DROP TABLE '. Arr::get($_POST, 'delTable')));
			} catch (Exception $e) {
				echo Debug::vars('105', $e->getMessage());
			}
			try{
				echo Debug::vars('95 drop table result: ', Database::instance('fb')->query(NULL, 'DROP GENERATOR GEN_'. Arr::get($_POST, 'delTable').'_ID'));
			} catch (Exception $e) {
				echo Debug::vars('99', $e->getMessage());
			}	
			//exit;
			$this->redirect('/checkdb');
		}
		//31.03.2025 добавление процедур
		if(Arr::get($_POST, 'addProcedure'))
		{
			
			$procSql=Arr::get($_POST, 'addProcedure'); //получил название процедуры
			$parkDB->addProcedure($procSql);
			$this->redirect('/checkdb');
			
		}
		
		if(Arr::get($_POST, 'delProcedure'))
		{
		
			$parkDB->delProcedure(Arr::get($_POST, 'delProcedure'));
			$this->redirect('/checkdb');
		}
		
		
		//07.04.2025 работа с данными в таблице
		if(Arr::get($_POST, 'addData'))
		{
			
			$procSql=Arr::get($_POST, 'addProcedure'); //получил название процедуры
			$parkDB->addData($procSql);
			$this->redirect('/checkdb');
			
		}
		
		
		
		if(Arr::get($_POST, 'delTableData'))
		{
		//echo Debug::vars('235', $_POST);exit;
			$parkDB->delTableData(Arr::get($_POST, 'delTableData'));
			$this->redirect('/checkdb');
		}
		
		/*9.04.2025 добавить данные в указанную таблицу
		* будеть произведен поиск файла с именем таблицы в папке data
		*/
		if(Arr::get($_POST, 'addTableData'))
		{
			//echo Debug::vars('245', $_POST);exit;
			$parkDB->addTableData(Arr::get($_POST, 'addTableData'));
			$this->redirect('/checkdb');
		}
		
	}
	
	
} 
