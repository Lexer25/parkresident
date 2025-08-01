    <?php defined('SYSPATH') or die('No direct script access.');
 
    class Task_CheckDubleIdGRZ extends Minion_Task {
		/**
		
		Тест для имитации обработки быстро пришедших идентификаторов.
		C:\xampp\php\php.exe c:\xampp\htdocs\parkresident\modules\minion\minion --task=CheckDubleIdGRZ
		
		*/
		
		    protected $_options = array(
        // param name => default value
        'cam'   => 3,
        'grz'   => 'A005BB177',
        'key'   => '50500505',
        'ip'   => '172.16.101.105',
        'ch'   => 1,
  
		);
	
        
        protected function _execute(array $params)
        {
        
		$data=array (
			'camera' => Arr::get($params, 'cam'),
			'channel' => 3,
			'count' => 16,
			'dateTime' => '20250429T141918Z',
			'description' =>'---',
			'direction' => 0,
			'groupId' => -1,
			'id' => 624101,
			//'image' =>  '/9j/4AAQSkZJRgABAQAAAQABAAD//gALQ1ZTIMDi8u4r/9sAQwAGBAUGBQQGBgUGBwcGCAoQCgoJCQoUDg8MEBcUGBgXFBYWGh0lHxobIxwWFiAsICMmJykqKRkfLTAtKDAlKCko/9sAQwEHBwcKCAoTCgoTKBoWGigoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgo/8QBogAAAQUBAQEBAQEAAAAAAAAAAAECAwQFBgcICQoLEAACAQMDAgQDBQUEBAAAAX0BAgMABBEFEiExQQYTUWEHInEUMoGRoQgjQrHBFVLR8CQzYnKCCQoWFxgZGiUmJygpKjQ1Njc4OTpDREVGR0hJSlNUVVZXWFlaY2RlZmdoaWpzdHV2d3h5eoOEhYaHiImKkpOUlZaXmJmaoqOkpaanqKmqsrO0tba3uLm6wsPExcbHyMnK0tPU1dbX2Nna4eLj5OXm5+jp6vHy8/T19vf4+foBAAMBAQEBAQEBAQEAAAAAAAABAgMEBQYHCAkKCxEAAgECBAQDBAcFBAQAAQJ3AAECAxEEBSExBhJBUQdhcRMiMoEIFEKRobHBCSMzUvAVYnLRChYkNOEl8RcYGRomJygpKjU2Nzg5OkNERUZHSElKU1RVVldYWVpjZGVmZ2hpanN0dXZ3eHl6goOEhYaHiImKkpOUlZaXmJmaoqOkpaanqKmqsrO0tba3uLm6wsPExcbHyMnK0tPU1dbX2Nna4uPk5ebn6Onq8vP09fb3+Pn6/8AAEQgC0AUAAwEhAAIRAQMRAf/aAAwDAQACEQMRAD8A8zUs7okcckkkjrGiRoXZmY4CgDkkkgYFd5p/hfwv4X0CHUvic1xc6nfqj2mg2d0wlEciqcumI2DqRIGLNsABHLYoAx4/FwaJGk8HeAVcqMr/AGGDg46ZEnP1ro/BcHhDx3eyaVrmm22heIZnC2MuhQPawzRKpkI27nj3jbJnzBypGM9gDK+HOh2H/CY6v4V8&nbsp;&hellip;',
			'inList' => 0,
			'passed' => 1,
			//'plate' => 'C023CA797',
			'plate' => Arr::get($params, 'grz'),
			'quality' =>  '555555555000',
			'stayTimeMinutes' => 0,
			'type' => 0,
			'weight' => 0,
			'test' => 1
			);
			//echo Debug::vars('98', $data);exit;
			
		$data_0=array (
				'key' => dechex(Arr::get($params, 'key')),
				'test' => 1,
				'ip'=>Arr::get($params, 'ip'),
				'ch'=>Arr::get($params, 'ch'),
				);

		Model::factory('Emul')->sendRequestPostJson(Array('plate'=>$data), 'dashboard/exec', 'body');
		//Model::factory('Emul')->sendRequestPostJson($data_0, 'dashboard/sendMPT', 'post');
		
		
        }
    }