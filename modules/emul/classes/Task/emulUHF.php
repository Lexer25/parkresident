    <?php defined('SYSPATH') or die('No direct script access.');
	//22.06.2025 Эмуляция работы cvs
	//задача отправляет в cvs данные точно так, как это делает cvs
	// C:\xampp\php\php.exe c:\xampp\htdocs\parkresident\modules\minion\minion --task=emulUHF --grz=A123AA15
 
    class Task_emulUHF extends Minion_Task {
		
		    protected $_options = array(
        // param name => default value
        'name'   => 'World',
        'delay'   => '30',
        'grz'   => '123ABC',
		);
	
        
        protected function _execute(array $params)
        {
			
			//echo Debug::vars('18', $params);exit;
			  $data_0=array (
						'key' => Arr::get($params, 'grz'),
						'test' => 1,
						'ip'=>'172.16.101.101',
						'ch'=>0,
						);
					
		
		Model::factory('Emul')->sendRequestPostJson($data_0, 'dashboard/sendMPT', 'post');
		
        }
    }