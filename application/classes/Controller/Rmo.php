<?php defined('SYSPATH') or die('No direct script access.');
/*
rmo - рабочее место охраны

*/


class Controller_Rmo extends Controller_Template { // класс описывает въезды и вызды (ворота) для парковочных площадок
	
	
	public $template = 'template';
	public function before()
	{
			
			parent::before();
			$session = Session::instance();
			if (!empty($_POST)) {
             	$username = Arr::get($_POST, 'username');
                $password = Arr::get($_POST, 'password');
			
                if (Auth::instance()->login($username, $password)) {
                $user = Auth::instance()->get_user();
				}
			}
			//echo Debug::vars('9', $_POST, $_GET, Auth::instance()->logged_in(), $_SESSION);
			I18n::load('rubic');
			
	}
	
	
	public function action_index()// управление гаражом
	{
		$_SESSION['menu_active']='rmo';
		$id_garage = $this->request->param('id');
		//echo Debug::vars('30', $id_garage); //exit;
		//если номер гаража указан, то вывожу данные по этому гаражу (для организации управления).
		if($id_garage>1)
		{
			$id_parking=1;
			
			$garage_info=Model::Factory('garage')->get_garage_info($id_garage);//информация о гараже 
			$place_income_garage=Model::Factory('garage')->place_income_garage($id_garage);//список машиномест, зарегистрированных в выбранном гараже
			$org_income_garage=Model::Factory('garage')->org_income_garage($id_garage);//статистические данные: список квартир, зарегистрированных в выбранном гараже
			$place_grz_garage_=Model::Factory('garage')->place_grz_garage_($id_garage);//статистические данные: список ГРЗ, зарегистрированных в выбранном гараже
			$get_grz_in_parking=Model::Factory('garage')->get_grz_in_parking($id_garage);// перечень ГРЗ, находящихся на парковке для выбранного гаража
			$get_garage_parking_list=Model::Factory('garage')->get_garage_parking_list($id_garage);// список парковок, входящих в указанный гараж

			//echo Debug::vars('2', $garage_info, $place_income_garage, $org_income_garage, $get_grz_in_parking, $place_grz_garage_); exit;
			
			$content = View::factory('rubic/rmo', array(
				'garage_info'=>$garage_info,
				'place_income_garage'=>$place_income_garage,
				'org_income_garage'=>$org_income_garage,
				'get_grz_in_parking'=>$get_grz_in_parking,
				'place_grz_garage_'=>$place_grz_garage_,
				'get_garage_parking_list'=>$get_garage_parking_list,
				));
			
			$this->template->content = $content;
			
		};
		
		if(is_null($id_garage))//а если номер гаража не указан, то вывожу предложение поиска
		{
			Session::instance()->delete('place_for_search');
			$content = View::factory('rubic/rmo', array(
			));
	        $this->template->content = $content;
		}
	}
	
	/*
	4.06.2023
	открыть ворота парковки для неизвестного ГРЗ
	*/
	
	public function action_opengate_unknow()//передача команды на открытие ворот
	{
	$_SESSION['menu_active']='grz';
		//echo Debug::vars('82', $_GET, $_POST); exit;	
		$todo = $this->request->post('opendoor');
		
		$post=Validation::factory($this->request->post());
				$post->rule('todo', 'not_empty')
						->rule('unknow_plate_for_insert', 'not_empty')

						;
			
				if($post->check())
				{	
					//открываю ворота на въезда
						//определяю gate по номеру паркинга. Параметр true указывает на то, что надо выбрать только въезд
						$param=Model::factory('parking')->getListgate(Arr::get($post, 'parking_for_open'), true);
						//
						//ворот может быть много, поэтому выбираю первый
						$param= Arr::get($param, 0);
						//echo Debug::vars('99', $param ); exit;
										
						//открываю gate
						$result=Model::factory('gates')->opengate(Arr::get($param, 'ID'));
					
						//повторная передача команды если ответ не получен или получен 0
						$i=0;
						while($result !='OK' AND $i<10)
						{
							$result=Model::factory('gates')->opengate(Arr::get($param, 'ID'));
							$i++;
							Log::instance()->add(Log::DEBUG, '119 Повторная передача команды Открыть ворота, попытка '.$i);
						}
						
							
					
					if($result == 'OK')
					{
						Session::instance()->set('ok_mess', array('result'=>'OK Ворота для въезда '.Arr::get($post, 'unknow_plate_for_insert').' открыты. {Отправлено команд '.$i.'}'));
						//делаю запись в журнае об открытии ворот для неизвестного ГРЗ
						Model::factory('grz')->addEventsInsertGRZTomInsideManual(Arr::get($post, 'unknow_plate_for_insert'), Arr::get($param, 'ID_DEV'), 'Машиноместо '.Arr::get($post, 'place_for_open'));
						
						//тут надо бы и надпись на табло вывести.
						//$resultTablo=Model::factory('gates')->sendMess(Arr::get($param, 'ID'));
						
						
					} else 
					{
						
						Session::instance()->set('e_mess', array('result'=>'Ошибка при открытии ворот '. $result));
					}
								
				} else 
				{
					Session::instance()->set('e_mess', $post->errors('Valid_mess'));
				
				}
		$this->redirect('rmo/index/'.Arr::get($post, 'id_garage'));
	}
	
	
/*
	18.04.2023
	1.06.2023
	Открытие ворот по команде оператора
	"id_garage" => string(3) "558" - номер гаража.
    "place_for_open" => string(1) "3" - номер машиноместа, по которому был произведен поиск гаража
    "parking_for_open" => string(1) "1" -  номер парковки, где находится машиноместо.
    "opendoor" => string(9) "O526AP977" -  ГРЗ, для которого открывают ворота.


*/
	public function action_mqtt()//передача команды на открытие ворот
	{
	$_SESSION['menu_active']='grz';
		//echo Debug::vars('66', $_GET, $_POST); exit;	
		$todo = $this->request->post('opendoor');
		
		$post=Validation::factory($this->request->post());
				$post->rule('opendoor', 'not_empty')
						->rule('parking_for_open', 'not_empty')
						->rule('parking_for_open', 'digit')
						;
			
				if($post->check())
				{	
						//вставка события по известному ГРЗ
							
						//подготовка массива для передачи в запросе
						$param_connect1=Arr::flatten(Model::Factory('gates')->get_list_gate(Arr::get($post,'parking_for_open'), true));
						$param_connect=Arr::get($param_connect1,'id');
						//echo Debug::vars('103', $param_connect); exit;
						
						$data=array(
						//'id_parking'=>Arr::get($post,'parking_for_open'),
						'gate'=>$param_connect,
						'grz'=>Arr::get($post,'opendoor'),
						);
						
						$mpt=new phpMPT(Arr::get($param_connect1,'box_ip'), Arr::get($param_connect1, 'box_port'));
						//$mpt->openGate(Arr::get($param_connect, 'mode'));// открыть ворота
						//$mpt->execute();
						$mpt->command='opendoor';
						$mpt->commandParam="\x00";
						$mpt->execute();
						
						Log::instance()->add(Log::DEBUG, '119 Ответ на команду открытия ворот  '.Arr::get($param_connect1,'box_ip').':'. Arr::get($param_connect1, 'box_port').' door '.$mpt->commandParam.' ответ '. $mpt->result);
						$mpt->command='opendoor';
						$mpt->commandParam="\x01";
						$mpt->execute();
						Log::instance()->add(Log::DEBUG, '122 Ответ на команду открытия ворот  '.Arr::get($param_connect1,'box_ip').':'. Arr::get($param_connect1, 'box_port').' door '.$mpt->commandParam.' ответ '. $mpt->result);
						
						$tablo=new phpMPT(Arr::get($param_connect1,'tablo_ip'), Arr::get($param_connect1, 'tablo_port'));
						
							$tablo->command='clearTablo';
							$tablo->execute(); 		
							
							$tablo->command='text';// вывод ГРЗ на табло
							$tablo->commandParam=Arr::get($post,'opendoor');
							$tablo->coordinate="\x00\x00\x06";
							$tablo->execute();
							
							$tablo->command='text';// вывод сообщений на табло
							$tablo->commandParam='Operator';
							$tablo->coordinate="\x08\x00\x06";
							$tablo->execute();
				
				
						
						
						if ($mpt->result == 'OK')
									{	
										//Фиксация события о ручном открывании въезда
						
										Model::factory('grz')->addEventsInsertGRZTomInsideManual(Arr::get($post,'opendoor'), Arr::get($param_connect1,'id_dev') );
							
										//фиксация ГРЗ внутри (на парковке)
							
										Model::factory('grz')->addToInside(Arr::get($post,'opendoor'));

										$mess1='Команда на въезд на парковку для ГРЗ '.Arr::get($post,'opendoor').' передана. <br>Ответ от шлюза . '.$mpt->result.'<br>ответ от табло '.$tablo->result.'.';
										Session::instance()->set('ok_mess', array('desc'=>$mess1));
										Log::instance()->add(Log::NOTICE, '156 '. $mess1);	

										
									} else {	
									
										$mess1='Команда на въезд была передана в контроллер ворот, но ответ '.$mpt->result.'. Попробуйте открыть ворота еще раз.';
										Session::instance()->set('e_mess', array('desc'=>$mess1));
										Log::instance()->add(Log::NOTICE, '163 '. $mess1);	
									}
				}
										
				else
				{
					echo Debug::vars('131 not_check', $_GET, $_POST); exit;
					Session::instance()->set('e_mess', $post->errors('Valid_mess'));
					
				}
			$this->redirect('rmo');
	}

/*
12.07.2023 г.

Команда на открытие ворот передается как POST-запрос в систему phpCVS
Открытие ворот по команде оператора
	"id_garage" => string(3) "558" - номер гаража.
    "place_for_open" => string(1) "3" - номер машиноместа, по которому был произведен поиск гаража
    "parking_for_open" => string(1) "1" -  номер парковки, где находится машиноместо.
    "opendoor" => string(9) "O526AP977" -  ГРЗ, для которого открывают ворота.

*/

public function action_opengateCVS()//передача команды на открытие ворот
	{
	$_SESSION['menu_active']='grz';
		//echo Debug::vars('66', $_GET, $_POST); exit;	
		$todo = $this->request->post('opendoor');
		
		$post=Validation::factory($this->request->post());
				$post->rule('opendoor', 'not_empty')
						->rule('parking_for_open', 'not_empty')
						->rule('parking_for_open', 'digit')
						;
		
				if($post->check())
				{	
						//вставка события по известному ГРЗ
							
						//подготовка массива для передачи в запросе
						$param_connect=Arr::flatten(Model::Factory('gates')->get_list_gate(Arr::get($post,'parking_for_open'), true));
						//$param_connect=Arr::get($param_connect1,'id');
												
						$data=array(
							'ip'=>Arr::get($param_connect,'box_ip'),
							'port'=>Arr::get($param_connect,'box_port'),
							'gateMode'=>Arr::get($param_connect,'mode'),
						);
						
						//echo Debug::vars('103', $param_connect, $data); exit;
						
						$mpt=new phpCVSAPI();
						//$mpt->openGate(Arr::get($param_connect, 'mode'));// открыть ворота
						//$mpt->execute();
						$mpt->data=$data;
						$mpt->execute();
						
						Log::instance()->add(Log::DEBUG, '285 Ответ на команду открытия ворот  '.Arr::get($param_connect,'box_ip').':'. Arr::get($param_connect, 'box_port').' door '.$mpt->commandParam.' ответ '. $mpt->result);
						$mpt->command='opendoor';
						$mpt->commandParam="\x01";
						$mpt->execute();
						Log::instance()->add(Log::DEBUG, '#289 '.Debug::vars($mpt));
						Log::instance()->add(Log::DEBUG, '289 Ответ на команду открытия ворот  '.Arr::get($param_connect,'box_ip').':'. Arr::get($param_connect, 'box_port').' door '.$mpt->commandParam.' ответ '. $mpt->result);
						
						$tablo=new phpMPT(Arr::get($param_connect,'tablo_ip'), Arr::get($param_connect, 'tablo_port'));
						
							$tablo->command='clearTablo';
							$tablo->execute(); 		
							
							$tablo->command='text';// вывод ГРЗ на табло
							$tablo->commandParam=Arr::get($post,'opendoor');
							$tablo->coordinate="\x00\x00\x06";
							$tablo->execute();
							
							$tablo->command='text';// вывод сообщений на табло
							$tablo->commandParam='Operator';
							$tablo->coordinate="\x08\x00\x06";
							$tablo->execute();
				
				
						
						
						if ($mpt->result == 'OK')
									{	
										//Фиксация события о ручном открывании въезда
						
										Model::factory('grz')->addEventsInsertGRZTomInsideManual(Arr::get($post,'opendoor'), Arr::get($param_connect,'id_dev') );
							
										//фиксация ГРЗ внутри (на парковке)
							
										Model::factory('grz')->addToInside(Arr::get($post,'opendoor'));

										$mess1='Команда на въезд на парковку для ГРЗ '.Arr::get($post,'opendoor').' передана. <br>Ответ от шлюза . '.$mpt->result.'<br>ответ от табло '.$tablo->result.'.';
										Session::instance()->set('ok_mess', array('desc'=>$mess1));
										Log::instance()->add(Log::NOTICE, '320 '. $mess1);	

										
									} else {	
									
										$mess1='Команда на въезд была передана в контроллер ворот, но ответ '.$mpt->result.'. Попробуйте открыть ворота еще раз.';
										Session::instance()->set('e_mess', array('desc'=>$mess1));
										Log::instance()->add(Log::NOTICE, '328 '. $mess1);	
									}
				}
										
				else
				{
					echo Debug::vars('131 not_check', $_GET, $_POST); exit;
					Session::instance()->set('e_mess', $post->errors('Valid_mess'));
					
				}
			$this->redirect('rmo');
	}



	public function action_mpt()
	{
	$_SESSION['menu_active']='grz';
		echo Debug::vars('136', $_GET, $_POST); exit;	
		$todo = $this->request->post('opendoor');
		
		$post=Validation::factory($this->request->post());
				$post->rule('opendoor', 'not_empty')
						->rule('id_garage', 'not_empty')
						->rule('id_garage', 'digit')
						;
				if($post->check())
				{	
					//echo Debug::vars('73', $_GET, $_POST); exit;	
					
					//дать команду на открытие двери
					
					//если команда выполнена успешно, то зафиксировать событие в журнале HL_EVENTS и изменить таблицу HL_INSIDE
		
			
			//echo Debug::vars('100',$a, $b ); exit;
			Session::instance()->set('ok_mess', $result);
			// открытие ворот на въезд. Чтобы открыть ворота надо заполнить топик Parking/MonitorDoor/Door/Open в формате {"cameraNumber": 3}
			} else {
					Session::instance()->set('e_mess', $post->errors('Valid_mess'));
					
				}
			$this->redirect('rmo');
	}

	
	public function action_control()// просмотр списка ГРЗ и их свойств
	{
		$_SESSION['menu_active']='grz';
		//echo Debug::vars('30', $_GET, $_POST); exit;	
		$todo = $this->request->post('todo');
		
		switch ($todo){
			
			case 'find_grz':
			$post=Validation::factory($this->request->post());
				$post->rule('num_for_search', 'digit')
						->rule('num_for_search', 'not_empty')
						->rule('num_for_search', 'min_length', array(':value', '3'))
						->rule('num_for_search', 'max_length', array(':value', '3'))
						;
				if($post->check())
				{	
					
				$list_grz=Model::factory('grz')->find_grz(Arr::get($post,  'num_for_search'));
				
				if(count($list_grz) == 0)// ничего не найдено. Переход на страницу rmo с выводом сообщения о неудачном поиске.
				{
					Session::instance()->set('e_mess', array('desc'=>'63 Информация по ГРЗ '.Arr::get($post,  'num_for_search').'не найдена.'));
					//echo Debug::vars('64', $id_garage); exit;
					$this->redirect('rmo');
				};
				
				if(count($list_grz) == 1)// найден строго один ГРЗ. Далее надо получать номер гаража и выводить сводную информацию.
				{
					$id_garage=Model::factory('grz')->get_id_garage_from_grz(Arr::get(Arr::get($list_grz, 0), 'ID_CARD'));
					if(is_null($id_garage))
					{
						Session::instance()->set('e_mess', array('desc'=>'Найден единственный ГРЗ '.Arr::get(Arr::get($list_grz, 0), 'ID_CARD').', который не имеет машиномест. Въезд запрещен.'));
						$this->redirect('rmo');	
					}
					//echo Debug::vars('77', $id_garage); exit;
					$this->redirect('rmo/'.$id_garage);
				}
				
				if(count($list_grz) > 1)// найдено более одного ГРЗ. Далее надо вывести список всех ГРЗ для выбора нужного ГРЗ.
				{
					Session::instance()->set('ok_mess', array('desc'=>'85 По ГРЗ '.Arr::get($post,  'num_for_search').' найдено '.count($list_grz).' совпадений.'));
					//echo Debug::vars('84', $list_grz); exit;
					$this->redirect('rmo/select_grz/'.Arr::get($post,  'num_for_search'));
				}
				
				echo Debug::vars('88', $id_garage); exit;
				
				echo Debug::vars('73', $list_grz, count($list_grz)); exit;
				
					Session::instance()->set('ok_mess', array('desc'=>'85 Информация по ГРЗ найдена успешно.'));
				} else {
					Session::instance()->set('e_mess', $post->errors('Valid_mess'));
					
				}
				//$this->redirect('rmo');
			break;
			
			case 'find_place':// поиск по номеру машиноместа
			$post=Validation::factory($this->request->post());
				$post->rule('num_for_search', 'digit')
						->rule('num_for_search', 'not_empty')
						;
				if($post->check())
				{	
				
				//получаю id_garage по номеру машиноместа
				Session::instance()->set('place_for_search', Arr::get($post, 'num_for_search'));
					$id_garage=Model::factory('garage')->get_id_garage_from_place(Arr::get($post, 'num_for_search'));
					
					if(!is_null($id_garage))
					{
					Session::instance()->set('ok_mess', array('desc'=>'Информация по номеру машиноместа '.Arr::get($post, 'num_for_search').' найдена успешно.'));
					$this->redirect('rmo/index/'.$id_garage);
					} else {
					Session::instance()->set('e_mess', array('desc'=>'Информация по номеру машиноместа '.Arr::get($post, 'num_for_search').' не найдена. Возможно, что машиноместо не входит ни в один из гаражей'));
					$this->redirect('rmo');	
						
					}
				} else {
					Session::instance()->set('e_mess', $post->errors('Valid_mess'));
					$this->redirect('rmo/');
				}
				
			break;
			case 'open_gate_1':
			$t1=microtime(1);
			echo Debug::vars('251',  $_GET, $_POST, $this->request->post('action')); //exit;
			
			$mpt=new phpMPT('192.168.5.56', 1985);
			$mpt->command='opendoor';
			$mpt->commandParam="\x00";
			$mpt->execute();
			$mpt->commandParam="\x01";
			$mpt->execute();
			Log::instance()->add(Log::DEBUG, '259 Ответ на открытие реле result, desc, время выполнения exectime, переход на action', array('result'=>$mpt->result, 'desc'=>$mpt->edesc,'exectime'=> (microtime(1)-$t1), 'action'=>$this->request->post('action')));
			if($mpt->result == 'OK')
			{
				Session::instance()->set('ok_mess', array('desc'=>'Команда на открытие ворот -1 этажа передана успешно. Ответ контроллера '.$mpt->result));
			} else 
			{
				Session::instance()->set('e_mess', array('desc'=>'Команда на открытие ворот -1 этажа передана с ошибкой. Ответ контроллера '.$mpt->result));
			}
					
			$this->redirect($this->request->post('action'));
			
			
			break;
			
			
			case 'open_gate_2':
			$t1=microtime(1);
			echo Debug::vars('251',  $_GET, $_POST, $this->request->post('action')); //exit;
			
			$mpt=new phpMPT('192.168.5.57', 1985);
			$mpt->command='opendoor';
			$mpt->commandParam="\x00";
			$mpt->execute();
			$mpt->commandParam="\x01";
			$mpt->execute();
			Log::instance()->add(Log::DEBUG, '259 Ответ на открытие реле result, desc, время выполнения exectime, переход на action', array('result'=>$mpt->result, 'desc'=>$mpt->edesc,'exectime'=> (microtime(1)-$t1), 'action'=>$this->request->post('action')));
			if($mpt->result == 'OK')
			{
				Session::instance()->set('ok_mess', array('desc'=>'Команда на открытие ворот -1 этажа передана успешно. Ответ контроллера '.$mpt->result));
			} else 
			{
				Session::instance()->set('e_mess', array('desc'=>'Команда на открытие ворот -1 этажа передана с ошибкой. Ответ контроллера '.$mpt->result));
			}		
			$this->redirect($this->request->post('action'));
			
			
			break;
			
			
			
			
			
				default:
				
				Session::instance()->set('e_mess', array('0'=>'Неизвестная команда '.Debug::vars($_POST)));
				$this->redirect('rmo');
			break;
		}
			$this->redirect('rmo');
			//echo Debug::vars('88', $id_garage); exit;
			
			
	}
	
	public function action_select_grz()
	{
		$grz = $this->request->param('id');
		
		//выбрать по указанным цифрам список подходящих ГРЗ с номером гаража
		
		$list_grz=Model::factory('grz')->find_grz($grz);
		
		//echo Debug::vars('155', $grz, $list_grz); exit;
		//вывод страницы для выбора ГРЗ
		$content = View::factory('rubic/rmo_list', array(
				'list_grz'=>$list_grz,
				
				));
			
			$this->template->content = $content;
		
	}

	
	
	
} 
