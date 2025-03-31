<?php defined('SYSPATH') OR die('No direct access allowed.');

class Model_Gates extends Model {
	
	public $res_ok = 0;
	public $res_err = 1;
	public $res_hz = 2;
	
	/*
	Получить список gate для указанной парковки

	*/
	public function get_list_gate($id_parking=0, $is_enter = null)// 
	{
		$res=array();
		if($id_parking==0){
		$sql='select   hlp.id,
            hlp.id_parking,
            hlp.is_enter,
            hlp.name,
            hlp.tablo_ip, 
            hlp.tablo_port, 
            hlp.box_ip, 
            hlp.box_port, 
            hlp.id_cam, 
            hlp.id_dev, 
            hlp.mode,
            d.name as dev_name from HL_PARAM hlp
            left join device d on d.id_dev=hlp.id_dev';
		} else {
			$sql='select   hlp.id,
            hlp.id_parking,
            hlp.is_enter,
            hlp.name,
            hlp.tablo_ip, 
            hlp.tablo_port, 
            hlp.box_ip, 
            hlp.box_port, 
            hlp.id_cam, 
            hlp.id_dev, 
            hlp.mode,
            d.name as dev_name from HL_PARAM hlp
            left join device d on d.id_dev=hlp.id_dev
            where hlp.id_parking='.$id_parking;
			if($is_enter != null) $sql=$sql.' and hlp.is_enter=1';
			
			
		}
		//echo Debug::vars('35', $sql); exit;
		try{
			$query = DB::query(Database::SELECT, $sql)
				->execute(Database::instance('fb'))
				->as_array();
			$res=array();
			foreach ($query as $key => $value)
			{
				$res[$key]['id']=Arr::get($value, 'ID');
				$res[$key]['id_parking']=Arr::get($value,'ID_PARKING');
				$res[$key]['name']=iconv('windows-1251','UTF-8',Arr::get($value,'NAME'));
				$res[$key]['dev_name']=iconv('windows-1251','UTF-8',Arr::get($value,'DEV_NAME'));
				$res[$key]['is_enter']=Arr::get($value,'IS_ENTER');
				$res[$key]['tablo_ip']=Arr::get($value,'TABLO_IP');
				$res[$key]['tablo_port']=Arr::get($value,'TABLO_PORT');
				$res[$key]['box_ip']=Arr::get($value,'BOX_IP');
				$res[$key]['box_port']=Arr::get($value,'BOX_PORT');
				$res[$key]['id_cam']=Arr::get($value,'ID_CAM');
				$res[$key]['id_dev']=Arr::get($value,'ID_DEV');
				$res[$key]['mode']=Arr::get($value,'MODE');
			}
			return array('result'=>$this->res_ok, 'res'=>$res);	
		} catch  (Exception $e) {
			Log::instance()->add(Log::ERROR, $e->getMessage());
			return array('result'=>$this->res_err, 'res'=>$res);	
		}
		//echo Debug::vars('26', $res);exit;
		return array('result'=>$this->res_hz, 'res'=>array());	;		
	}
	
	public function  get_info_gate($id_gate)//получить информацию о воротах 
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
			where hlp.id='.$id_gate;
		
		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();
		$res=array();
		foreach ($query as $key => $value)
		{
			$res['id']=Arr::get($value, 'ID');
			$res['id_parking']=Arr::get($value,'ID_PARKING');
			$res['name']=iconv('windows-1251','UTF-8',Arr::get($value,'NAME'));
			$res['is_enter']=Arr::get($value,'IS_ENTER');
			$res['tablo_ip']=Arr::get($value,'TABLO_IP');
			$res['tablo_port']=Arr::get($value,'TABLO_PORT');
			$res['box_ip']=Arr::get($value,'BOX_IP');
			$res['box_ip']=Arr::get($value,'BOX_IP');
			$res['box_port']=Arr::get($value,'BOX_PORT');
			$res['id_cam']=Arr::get($value,'ID_CAM');
			$res['id_dev']=Arr::get($value,'ID_DEV');
			$res['mode']=Arr::get($value,'MODE');
		}
		return $res;	
		
	}
	
	
	public function add_gate($data) //добавление новых ворот для указанной парковки
	{
		//echo Debug::vars('26', $data);exit;
		/*
		"id" => string(2) "23"
        "new_gate_name" => string(23) "Новые ворота"
        "id_parking" => string(1) "0"
        "todo" => string(8) "add_gate"
	*/
			
		$sql='INSERT INTO HL_PARAM (ID_PARKING, NAME) VALUES ('.Arr::get($data, 'id_parking') .', \''. Arr::get($data, 'new_gate_name').'\')';
			
		
		//echo Debug::vars('31', $sql);exit;
		try
				{
				$query = DB::query(Database::INSERT, iconv('UTF-8','windows-1251',$sql))
				->execute(Database::instance('fb'));
				} catch (Exception $e) {
					Log::instance()->add(Log::NOTICE, '794 '. $e->getMessage());
				}
		return;
	}
	
	
	public function update_gate($data) //добавление новых ворот для указанной парковки
	{
		//echo Debug::vars('26', $data);exit;
		/*
		array(11) (
   "id" => string(2) "23"
    "is_enter" => string(1) "1"
    "name" => string(10) "Gate id=60"
    "id_parking" => string(1) "1"
    "tablo_ip" => string(13) "192.168.8.110"
    "tablo_port" => string(4) "1985"
    "box_ip" => string(12) "192.168.1.57"
    "box_port" => string(4) "1985"
    "id_cam" => string(3) "222"
    "id_dev" => string(2) "97"
    "mode" => string(1) "3"
    "todo" => string(6) "update"
)
*/
			
		$sql='UPDATE HL_PARAM
				SET TABLO_IP = \''.Arr::get($data, 'tablo_ip').'\',
					TABLO_PORT = '.Arr::get($data, 'tablo_port').',
					BOX_IP = \''.Arr::get($data, 'box_ip').'\',
					BOX_PORT = '.Arr::get($data, 'box_port').',
					ID_CAM = '.Arr::get($data, 'id_cam').',
					ID_DEV = '.Arr::get($data, 'id_dev').',
					MODE = '.Arr::get($data, 'mode').',
					NAME = \''.Arr::get($data, 'name').'\',
					ID_PARKING = '.Arr::get($data, 'id_parking').',
					IS_ENTER = '.Arr::get($data, 'is_enter').'
				WHERE (ID = '.Arr::get($data, 'id').')';
	
		//echo Debug::vars('31', $sql);exit;
		try
				{
				$query = DB::query(Database::UPDATE, iconv('UTF-8','windows-1251',$sql))
				->execute(Database::instance('fb'));
				} catch (Exception $e) {
					Log::instance()->add(Log::NOTICE, '80 '. $e->getMessage());
				}
		return;
	}
	
	
	public function del_gate($data) //удаление ворот для указанной парковки
	{
		//echo Debug::vars('26', $data);exit;
			
		$sql='delete from HL_PARAM hlp
				where hlp.id='.Arr::get($data, 'id');
			
		
		//echo Debug::vars('31', $sql);exit;
		try
				{
				$query = DB::query(Database::DELETE, $sql)
				->execute(Database::instance('fb'));
				} catch (Exception $e) {
					Log::instance()->add(Log::NOTICE, '794 '. $e->getMessage());
				}
		return;
	}
	
	
	
	/*
	4.06.2023
	Открыть указанный gate
	$id_gate - номер гейта
	
	*/
	public function opengate($id_gate) //открыть ворота (gate)
	{
		
		
			$connect_param= $this->get_info_gate($id_gate);
		//echo Debug::vars('222', $id_gate, $connect_param, Arr::get($connect_param,'box_ip'), Arr::get($connect_param, 'box_port'), Arr::get($connect_param,'mode'));exit;
		
		//Тут надо отправить запрос на сервер cvs на другом компьютере
		
		//создаю объект MPT
			$mpt=new phpMPT(Arr::get($connect_param,'box_ip'), Arr::get($connect_param, 'box_port'));
			$mpt->openGate(Arr::get($connect_param,'mode'));// открыть ворота
		return $mpt->result;
	}
	
	
	
	/*
	4.06.2023
	вывести надпись на табло для указанного гейта
	$id_gate - номер гейта
	$mess - текст для вывода
	$line - строка: 0 - верхняя, 1 - нижняя
	
	*/
	public function sendMess($id_gate, $mess, $line=null) //вывести надпись на табло 
	{
		
		
			$connect_param= $this->get_info_gate($id_gate);
		//echo Debug::vars('222', $id_gate, $connect_param, Arr::get($connect_param,'box_ip'), Arr::get($connect_param, 'box_port'), Arr::get($connect_param,'mode'));exit;
		//создаю объект MPT
			$tablo=new phpMPT(Arr::get($connect_param,'box_ip'), Arr::get($connect_param, 'box_port'));
			
			$tablo->command='text';// вывод ГРЗ на табло
			$tablo->commandParam=$mess;
			$tablo->coordinate="\x00\x00\x02";
			$tablo->execute();
		
		
		return $mpt->result;
	}
	
	
	
	
	
	
	
	
	public function checkplaceenable() //получить статус счетчиков: считать или не считать 4.04.2023
	{
		//echo Debug::vars('26', $data);exit;
			
		$sql='select hlt.value_int as CHECKPLACEENABLE from hl_setting hlt where hlt.name=\'CHECKPLACEENABLE\'';
			
		
		//echo Debug::vars('31', $sql);exit;
		try
				{
				$query = DB::query(Database::SELECT, $sql)
				->execute(Database::instance('fb'))
				->get('CHECKPLACEENABLE');;
				} catch (Exception $e) {
					Log::instance()->add(Log::NOTICE, '794 '. $e->getMessage());
					//throw new ExceptionParkResident($e->getMessage());
					throw new Exception($e->getMessage());
				}
				
				//echo Debug::vars('285', $query);exit;
				$_SESSION['checkplaceenable']=$query;
		return $query;
	}
	
	public function tabloMessages() //получить данные о сообщениях табло
	{
		//echo Debug::vars('26', $data);exit;
			
		$sql='select et.name as eventName, hlm.eventcode, hlm.text as eventMessage, hlm.param, hlm.smalname from hl_messages hlm
				left join eventtype  et on et.id_eventtype=hlm.eventcode
				where hlm.eventcode is not null';
		
		try
				{
				$query = DB::query(Database::SELECT, $sql)
				->execute(Database::instance('fb'))
				->as_array();
				} catch (Exception $e) {
					Log::instance()->add(Log::NOTICE, '794 '. $e->getMessage());
				}
				
		return $query;
	}
	
	public function tabloMessageIdle() //получить данные о сообщениях табло на время ожидания
	{
		 $sql='select hlm.text from hl_messages hlm
        where hlm.smalname=\'text1\'';
       
	
	$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->get('TEXT');
		
	
	 $mess['top_string'] = $query;
	
	
	$sql='select hlm.text from hl_messages hlm
        where hlm.smalname=\'text2\'';
       
	
	$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->get('TEXT');
		

	
	 $mess['down_string'] = $query;
	
	
				
		return $mess;
	}
	
	/*
	
	$new_tablo_text - текстовые сообщения 
	$dx - смещение по оси X
	$dy - смещение по оси Y
	$messColor - цвет сообщения
	$messScroll - надо ли организовывать прокрутку
	
	параметры $dx $dy $messColor $messScroll  надо упаковать в json {"dx":21,"dy":22,"messColor":4,"messScroll":1}

	*/
	
	public function update_gate_messages($text,$dx,$dy,$messColor,$messScroll) //обновить данные о сообщениях табло.
	{
		
		//echo Debug::vars('278', 'text=',$text,'dx=',$dx,'dy=',$dy,'messColor=',$messColor,'messScroll=',$messScroll, array_keys($text)); exit;
		$helparr=array_keys($text);
		foreach($helparr as $key=>$value)
		{
			
			$param[$value]['text']=Arr::get($text, $value);
			$param[$value]['param']=json_encode(array(
			'dx'=>Arr::get($dx, $value),
			'dy'=>Arr::get($dy, $value),
			'messColor'=>Arr::get($messColor, $value),
			'messScroll'=>Arr::get($messScroll, $value)
			));
			
			
		}
			//echo Debug::vars('278', $param); exit;
			
		foreach ($param as $key=>$value)
		{
			//echo Debug::vars('297', $key, $value);
			$sql='update hl_messages hlp
				set hlp.text=\''.Arr::get($value, 'text').'\',
				hlp.param=\''.Arr::get($value, 'param').'\'
				where hlp.eventcode='.$key;
				
				//echo Debug::vars('303', $sql); exit;
			try
				{
				$query = DB::query(Database::UPDATE, iconv('UTF-8','windows-1251',$sql))
				->execute(Database::instance('fb'));
				} catch (Exception $e) {
					Log::instance()->add(Log::NOTICE, '80 '. $e->getMessage());
				}
		

		}	
				
		
		return $query;
	}
	
	public function check_count_off() //выключить счетчики
	{
			$sql='UPDATE HL_SETTING
					SET VALUE_INT = 0
					WHERE (NAME = \'CHECKPLACEENABLE\');';
		try
				{
				$query = DB::query(Database::UPDATE, iconv('UTF-8','windows-1251',$sql))
				->execute(Database::instance('fb'));
				} catch (Exception $e) {
					Log::instance()->add(Log::NOTICE, '80 '. $e->getMessage());
				}
		return;
	}
	
	public function check_count_on() //включить счетчики
	{
			$sql='UPDATE HL_SETTING
					SET VALUE_INT = 1
					WHERE (NAME = \'CHECKPLACEENABLE\');';
	
		//echo Debug::vars('31', $sql);exit;
		try
				{
				$query = DB::query(Database::UPDATE, iconv('UTF-8','windows-1251',$sql))
				->execute(Database::instance('fb'));
				} catch (Exception $e) {
					Log::instance()->add(Log::NOTICE, '80 '. $e->getMessage());
				}
		return;
	}
	
	
	
	
}
