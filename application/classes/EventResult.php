<?php

/**
* @package    ParkResident/Application
 * @category   Base
 * @author     Artonit
 * @copyright  (c) 2025 Artonit Team
 * @license    http://artonit/ru 
 
 */
//класс результат вставки события

class EventResult
{
  public $result;//результат вставки события
  public $id_events=-1;//номер вставленного события
  public $errCode;//код ошибки
  public $desc;//описание дополнительное. Если результат ошибка, то тут будет указано описание ошибки
	

	const OK=0;//вставка прошла успешно
	const ERR=1;//вставка прошла с ошибкой
	
	
	const ERRCODE=-405;//
	
	
	 public function __construct($id=null)
    {
       $this->result=self::ERR;
       $this->errCode=self::ERRCODE;
	}
	
	
	
   
}
