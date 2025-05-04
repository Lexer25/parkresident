<?php
/**
* @package    ParkResident/Application
 * @category   Base
 * @author     Artonit
 * @copyright  (c) 2025 Artonit Team
 * @license    http://artonit/ru 
 
 */


/* phpCVSAPI 
Класс предназначен для передача и систему CVS команды управления воротами и табло

*/

class phpCVSAPI
{
    public $baseurl = '';
	protected $socket;            /* holds the socket	*/
    public $address;            /* broker address */
    public $port;                /* broker port */
    private $connection;            /* stores connection */
    public $result;            /* результат выполнения команды */
    public $answer;            /* ответ на команду (если он должен быть) */
    public $command;            /* команда для выполнения */
    public $commandParam;            /* параметры команды */
    public $coordinate;            /* координаты вывода строки на табло */
    public $binCommand;            /* команда для выполнения */
    public $codeCommand;            /* код команды контроллера */
    public $data;            /* данные для команды */
   

    

 
    public function __construct()
    {
        //$this->baseurl='http://192.168.5.10:8080/cvs/index.php/tools/forcecomein';
        //$this->baseurl='http://192.168.1.20:8080/cvs/index.php/tools/forcecomein';
        $this->baseurl='http://192.168.1.5:8080/cvs/index.php/tools/forcecomein';
    }


   
   
   public function command($command)
   {
	   $this->$command=$command;
   }
   
   public function commandParam($commandParam)
   {
	   $this->$commandParam=$commandParam;
   }
   
    public function data($data)
   {
	   $this->$data=$data;
   }
   
   
   /*
	12.07.2023 
	Команда на открытие ворот.
	
	*/
	
	public function execute()
	{
		
		$request = Request::factory($this->baseurl)
				->headers("Accept", "application/json")
				-> method(Request::POST)
				->body($this->data);
		try
		{	
			$response=$request->execute();
			Log::instance()->add(Log::DEBUG, '71 '.$response->body());
			$this->result=$response->body();
		} catch (Kohana_Request_Exception $e) {
			$this->statusOnline=False;
			$this->result=$e->getMessage();
		}
	}
   
   
}
