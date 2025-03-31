<?php
//echo Debug::vars('2', $list); exit;// список id событий, которые надо вывести на экран

?>
<script type="text/javascript">
     
  	$(function() {		
  		$("#tablesorter_ge").tablesorter({sortList:[[0,0]], headers: {}});
  	});	
</script>			

<div class="panel panel-primary">
	
	<div class="panel-body">


<table id="tablesorter_ge" class="table table-striped table-hover table-condensed tablesorter">
		<thead allign="center">


				<tr>
			<th><?echo __('№ п/п');?></th>
			
			<th><?echo __('Код события');?></th>
			<th><?echo __('КПП.');?></th>
			<th><?echo __('Время события');?></th>
			<th><?echo __('Въезд');?></th>
			<th><?echo __('Выезд');?></th>
			
			<th><?echo __('ГРЗ');?></th>
			<th><?echo __('Модель');?></th>
			<th><?echo __('Прим');?></th>
		</tr>
		</thead>

		<tbody>
		<?php 
		$i=0;
		$rz=';';
		$ps="\r\n";
		$eventTable='';
		$checked='no';	if(!isset($list)) 
		{
			$list=array();// если списка событий нет, то переменнся $event_list - пустая, что позволит избежать ошибок
			
			echo __('Событий нет.');
		}
		foreach($list as $key=>$value)
		{
			switch (Arr::get($value,'EVENT_CODE'))
			{
				case 50:
					$ecolor='success';
				break;
				case 65:
					$ecolor='warning';
				break;
				case 46:
					$ecolor='danger';
				break;
				default:
					$ecolor='info';
				
			}
			$eventcode=new Eventcode($key);
			echo '<tr class="'.$ecolor.'">';
				echo '<td>'.($i+1).'</td>';
				echo '<td>'.iconv('windows-1251','UTF-8', Arr::get($value,'EVENT_NAME')).' ('.Arr::get($value,'EVENT_CODE').')</td>';
				echo '<td>'.iconv('windows-1251','UTF-8', Arr::get($value,'GATE_NAME')).' ('.Arr::get($value,'ID_GATE').')</td>';
				echo '<td>'.Date::formatted_time(Arr::get($value,'EVENT_TIME'), 'd.m.Y H:i:s').'</td>';
				echo '<td>'.(Arr::get($value,'IS_ENTER')? 'Въезд' : '').'</td>';
				echo '<td>'.(Arr::get($value,'IS_ENTER')? '' : 'Выезд').'</td>';
				
				echo '<td>'.HTML::anchor('grz/history/'.Arr::get($value,'GRZ'), Arr::get($value,'GRZ')).'</td>';
				echo '<td>'. iconv('windows-1251','UTF-8', Arr::get($value,'SURNAME').' '
							.Arr::get($value,'NAME').' '
							.Arr::get($value,'PATRONYMIC')).' ('
							.Arr::get($value,'ID_PEP').')</td>';
				echo '<td>'.Arr::get($value,'COMMENT').'</td>';
				
			echo '</tr>';	
			$i++;
			$eventTable.=($i+1).$rz
				.Arr::get($value,'EVENT_NAME').' ('.Arr::get($value,'EVENT_CODE').')'.$rz
				.Date::formatted_time(Arr::get($value,'EVENT_TIME'), 'd.m.Y H:i:s').$rz
				.(Arr::get($value,'IS_ENTER')? 'Въезд' : 'Выезд').$rz
				.Arr::get($value,'SURNAME').' '
							.Arr::get($value,'NAME').' '
							.Arr::get($value,'PATRONYMIC').' ('
							.Arr::get($value,'ID_PEP').')'
				.$ps;
			
		}
		
		?>
		</tbody>
	</table>		
		<?php
		
			//echo Form::hidden('eventTable', $eventTable); 
		?>
</div>
</div>



    <script type="text/javascript">
      $(function () {
		  
		  // Установка начальных значений даты
		var dateEnd=new Date();
	  dateEnd.setHours(23, 59, 59, 0);
	  
		//var dateBegin = new Date();
		var dateBegin = new Date();
		dateBegin.setDate(dateBegin.getDate()-1);
		dateBegin.setHours(0, 0, 0, 0);
	  
	     //Инициализация datetimepicker1 и datetimepicker2
        $("#datetimepicker1").datetimepicker(
		{language: 'ru', 
		showToday: true,
		sideBySide: true,
		defaultDate: dateBegin
		}
		);
        $("#datetimepicker2").datetimepicker(
		{language: 'ru', 
		showToday: true,
		sideBySide: true,
		defaultDate: dateEnd
		}
		);
		
        //При изменении даты в 1 datetimepicker, она устанавливается как минимальная для 2 datetimepicker
        $("#datetimepicker1").on("dp.change",function (e) {
          $("#datetimepicker2").data("DateTimePicker").setMinDate(e.date);
        });
        //При изменении даты в 2 datetimepicker, она устанавливается как максимальная для 1 datetimepicker
        $("#datetimepicker2").on("dp.change",function (e) {
          $("#datetimepicker1").data("DateTimePicker").setMaxDate(e.date);
        });
      });
	  
	  	$(function() {		
  		$("#tablesorter").tablesorter({sortList:[[0,0]], headers: { 0:{sorter: false}}});
  	});
    </script>
	
  

