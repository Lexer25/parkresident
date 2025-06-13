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
		$sql = 'SELECT GEN_ID( gen_event_id, 0 ) FROM RDB$DATABASE';
		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->current();
		return $query['GEN_ID'];
	}
	private function selectevent($id)
	{
		$sql = 'select e.id_event, e.id_eventtype, e.datetime,  et.color, et.name as eventtype_name, d.name as device_name, p.surname, p.surname||\' \'|| p.name||\' \'|| p.patronymic as people_name, p.photo, p.post, o.name as organization_name from events e
 join eventtype et on et.id_eventtype=e.id_eventtype
 join device d on e.id_dev=d.id_dev
 left join people p on p.id_pep=e.ess1
 left join organization o on o.id_org=e.ess2
 where  e.id_event > '.$id;
		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();
		return $query;
	}
	private function get_table()
	{
		try{
			$id=Cookie::get('id', null);
			if($id==null)
			{		
				$id=$this->getid();
				Cookie::set('id', $id);
			}
			$tab=$this->selectevent($id); // взять данные
			if(count($tab)==0) return null;//выйти при 0 вкладках
			Log::instance()->add(Log::DEBUG, 'Line 60. Select event from: '.$id.', received events: '. count($tab));
			return array_reverse($tab);
		}
		catch (Exception $e) {
			return null;
		}
	}
	private function format_body($tab)
	{
		$body='';
		foreach ($tab as $key=>$row)
		{
			$style='color: black;background-color: #'.dechex($row['COLOR']).';';
			$body.='<tr>
			<td id="photo" style="'.$style.'display:none;">'.base64_encode(pack("H*", str_replace("\0", "",$row['PHOTO']))).'</td>
			<td id="people_post" style="'.$style.'display:none;">'.iconv('CP1251','UTF-8',$row['POST']).'</td>
			<td id="event_type" style="'.$style.'">'.$row['ID_EVENTTYPE'].'</td>
			<td style="'.$style.'">'.$row['ID_EVENT'].'</td>
			<td style="'.$style.'">'.$row['DATETIME'].'</td>
			<td id="even_name" style="'.$style.'">'.iconv('CP1251','UTF-8',$row['EVENTTYPE_NAME']).'</td>
			<td id="device_name" style="'.$style.'">'.iconv('CP1251','UTF-8',$row['DEVICE_NAME']).'</td>
			<td id="people_name" style="'.$style.'">'.iconv('CP1251','UTF-8',$row['PEOPLE_NAME']).'</td>
			<td id="org_name" style="'.$style.'">'.iconv('CP1251','UTF-8',$row['ORGANIZATION_NAME']).'</td>
			</tr>';	
		}	
		return $body;
	}
	public function action_getEvent()
	{
		$this->response->body('');
		try{
			$id=Cookie::get('id', null);
			if($id==null)
			{		
				$id=$this->getid();
				Cookie::set('id', $id);
			}
			$tab=$this->selectevent($id); // взять данные
			if(count($tab)==0) return null;//выйти при 0 вкладках
			Log::instance()->add(Log::DEBUG, 'Line 60. Select event from: '.$id.', received events: '. count($tab));
			return array_reverse($tab);
		}
		catch (Exception $e) {
			return null;
		}
		if($tab==null) return;	
		Cookie::set('id',$tab[0]['ID_EVENT']);
		Log::instance()->add(Log::DEBUG, 'Line 93. Receive count: '. count($tab).' Save to ccokie: '. $tab[0]['ID_EVENT']);	
		$this->response->body($this->format_body($tab));
	}
}
