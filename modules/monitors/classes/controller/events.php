<?php defined('SYSPATH') or die('No direct script access.');
/**


*/
class Controller_events extends Controller { 
		
	public $view = 'result';//view для показа результата
	public $template = 'template';
	public function before()
	{
		parent::before();
	}


	public function action_index($filter = null)
	{
		//echo Debug::vars('19');exit;
		
			$fl = $this->session->get('alert');
		$this->session->delete('alert');
		$this->template->content = View::factory('list')
			//->bind('cards', $list)
			//->bind('cardsList', $list)
			//->bind('catdTypelist', $catdTypelist)
			->bind('alert', $fl)
			->bind('arrAlert', $arrAlert)
			//->bind('filter', $filter)
			;
	}
	
	private function getid()
	{
		//$sql = 'SELECT GEN_ID(gen_event_id, 0 ) FROM RDB$DATABASE';//эта строка нужна для работы с общим журналом событий events
		$sql = 'SELECT GEN_ID(GEN_HL_EVENTS_ID, 0 ) FROM RDB$DATABASE';//тут выбирается генератор таблицы hl_events
		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->current();
		return $query['GEN_ID'];
	}
	
	private function selectevent($id,$photo)
	{
		
		$sqlphoto='';
		if($photo) $sqlphoto='p.photo,';
		
		
		$sql='select  e.id as id_event, e.event_code as id_eventtype, e.event_time as datetime, e.grz as id_card, e.comment, e.id_gate  , hlec.name as eventtype_name ,
        coalesce(hlp.name, hlp2.name) as device_name,
        p.surname, p.surname||\' \'|| p.name||\' \'|| p.patronymic as people_name,
         p.photo, p.post, o.name as organization_name, e.is_enter, e.id_garage , hlg.name as garage_name
        from hl_events e
        left join HL_GARAGENAME hlg on hlg.id=e.id_garage
        left join hl_eventcode hlec on hlec.id=e.event_code
        left join hl_param hlp on hlp.id=e.id_gate
		left join hl_param hlp2 on hlp2.id_dev=e.id_gate
        left join card c on c.id_card=e.grz
        left join people p on p.id_pep=c.id_pep
        left join organization o on o.id_org=p.id_org
		where  e.id >'.($id-30).'
		order by e.event_time';
		
				
		
  
 //Log::instance()->add(Log::DEBUG, 'Line 66.evenrs sql: '. $sql);
 //Log::instance()->add(Log::DEBUG, 'Line 49.select events from : '. $id);
		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();
		return $query;
	}
	
	/**5.02.2025 Получить последние события.
	* http://localhost/crm2/events/getEvent?photo=
	*номер последнего событий хранится в куках.
	*/
	public function action_getEvent()
	{
		
		$t1=microtime(true);
		$photo=filter_var($this->request->query('photo'), FILTER_VALIDATE_BOOLEAN);
		$getPhoto = ($photo)? 'true' : 'false';
		$this->response->body('');
		try{
			$id=Cookie::get('id');
			if($id==null)
			{
				$id=$this->getid();
				Cookie::set('id',$id);
			}
			$tab=$this->selectevent($id,$photo); // получил журнал событий
//echo Debug::vars('93', $tab);exit;
			if(count($tab)==0) 
			{
								
				return;//выйти при 0 вкладках (если нет событий)
			}
			

			$body='';
			//Log::instance()->add(Log::DEBUG, Debug::vars($tab));
			$tab2=$tab;
			$tab=array_reverse($tab);
			//Log::instance()->add(Log::DEBUG,'118 '. Debug::vars($tab));
			
					
			foreach ($tab as $key=>$row)
			{
				//цвет фона зависит от типа событий
				//Код Событие Цвет
				//50 Действительная карта зеленый
				//46 Неизвестная карта Красный
				//65 Недейсвительная карта Синий
				
				switch($row['ID_EVENTTYPE']){
					case 14:
					case 15:
					case 17:
					case 18:
					case 50:
						$row['COLOR']=13037766;
					break;
					case 10:
					case 46:
						$row['COLOR']=13027056;
					break;
					
					case 65:
						$row['COLOR']=15779526;
						
					break;
					default:
						$row['COLOR']=15462640;
					break;
				}
				$style='color: black;background-color: #'.dechex($row['COLOR']).';';
				$bodyphoto='';
				if($photo) $bodyphoto='<td id="photo" style="'.$style.'display:none;">'.base64_encode(pack("H*", str_replace("\0", "",$row['PHOTO']))).'</td>';
				if(is_null($row['IS_ENTER'])) $row['IS_ENTER'] = -1;
				
					switch($row['IS_ENTER']){
					case 1:				
						{
								$directionEnter='Въезд';
								$directionExit='';
						}		
					break;
					case 0:				
						{
								$directionEnter='';
								$directionExit='Выезд';
						}		
					break;
					case -1:				
						{
								$directionEnter='-';
								$directionExit='-';
						}		
					break;
					
					
					}
				
				if(is_null($row['ID_GARAGE']))
				{
					$garageName='---';
				} else {
					
					$garageName=iconv('CP1251','UTF-8',$row['GARAGE_NAME']).' ('.$row['ID_GARAGE'].')';
				}
				
				$body.='<tr>
				'.$bodyphoto.'
				<td id="people_post" style="'.$style.'display:none;">'.iconv('CP1251','UTF-8',$row['POST']).'</td>
				<td style="'.$style.'">'.$row['DATETIME'].'</td>
				<td id="even_name" style="'.$style.'">'.iconv('CP1251','UTF-8',$row['EVENTTYPE_NAME']).'('.$row['ID_EVENTTYPE'].')</td>
				<td style="'.$style.'">'.$row['ID_CARD'].'</td>
				<td style="'.$style.'">'.$directionEnter.'</td>
				<td style="'.$style.'">'.$directionExit.'</td>
				<td style="'.$style.'">'.$garageName.'</td>
				<td id="device_name" style="'.$style.'">'.iconv('CP1251','UTF-8',$row['DEVICE_NAME']).'</td>
				<td id="people_name" style="'.$style.'">'.iconv('CP1251','UTF-8',$row['PEOPLE_NAME']).'</td>
				<td id="org_name" style="'.$style.'">'.iconv('CP1251','UTF-8',$row['ORGANIZATION_NAME']).'</td>
				<td id="comment" style="'.$style.'">'.iconv('CP1251','UTF-8',$row['COMMENT']).'</td>
				</tr>';	
			Cookie::set('id',$tab[0]['ID_EVENT']);	
			}	
		//	Cookie::set('id',$tab[0]['ID_EVENT']);
			$this->response->body($body);
		}
		catch (Exception $e) {
			Log::instance()->add(Log::DEBUG, $e->getMessage());
			return;
		}
	}
}
