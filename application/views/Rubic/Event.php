<?php
//8.06.2023 https://itchief.ru/bootstrap/accordion
//8.06.2023 скрипт https://stackoverflow.com/questions/42834154/changing-bootstrap-button-text-on-toggle
echo Form::open('rubic/rubic_control');
//echo Debug::vars('3', $_SESSION);
//echo Debug::vars('3', unserialize(Cookie::get('id_event_filter', 'no_cookies_0')));
?>

<script>

$(function() {
    var btn = $("[href='#target_event']");
    var toggled = false;
    btn.on("click", function() {
        if(!toggled)
        {
          toggled = true;
          btn.text("Свернуть");
        } else {
          toggled = false;
          btn.text("Развернуть");
        }
    });
});

 $(document).ready(function() {
    	    $("#check_events_list_0").click(function () {
    	         if (!$("#check_events_list_0").is(":checked"))
    	            $(".checkbox").prop("checked",false);
    	        else
    	            $(".checkbox").prop("checked",true);
    	    });
    	});

</script>



<!-- Фильтр событий -->
<div class="panel panel-primary ">
	<div class="panel-heading">
		<h3 class="panel-title">
		<?php
		
		//чтение набор фильтров необходимо для того, чтобы в разделе Фильт событий показать ранее выделенные события.
		/* $arr1=array(3=>3, 4=>4, 5=>5, 6=>6, 46=>46, 50=>50, 65=>65);
		$arr2=array(46=>46, 50=>50, 65=>65,3=>3, 4=>4, 5=>5, 6=>6,  81=>81);
		
		echo Debug::vars('46', array_diff($arr2, $arr1)); exit; */
		$event_income_filter=unserialize(Cookie::get('id_event_filter', null));
		
		
		//if((count($event_income_filter) == 8) or (is_null($event_income_filter)))
		if((count($event_income_filter) == 8) or (is_null(Cookie::get('id_event_filter', null))))//если в фильтре всего 8 событий, или если фильта вообще нет, то показываю все события.
		{
			echo 'Фильтр событий. Показаны все события.';
			$event_income_filter=array(3=>3, 4=>4, 5=>5, 6=>6, 46=>46, 50=>50, 65=>65, 81=>81);
			
		} else 
		{
			echo 'Фильтр событий. Используются фильтры, показаны не все события.';
			$event_income_filter=unserialize(Cookie::get('id_event_filter', 'no_cookies_0'));
			
		}
		
		?></h3>
	</div>
	<div class="panel-body">
	
			<a class="btn btn-primary" data-toggle="collapse" href="#target_event">Развернуть</a>
			<div class="collapse" id="target_event">
				<?php echo __('События парковочной системы');
							
				?>
				<div>
				<?php 
				{ ?>
					<label><input type="checkbox" name="id_event_filter" id="check_events_list_0"></label> Выделить все/снять выделение
					<table  class="table table-striped table-hover table-condensed tablesorter">
						<tbody>
						<?php 
						
						$i=0;
						$checked='no';
						$column=2;// количество колонок в таблице
						$row= ceil(count($events_name_list)/$column);//количество строк
						$aaa=array_chunk($events_name_list, $column);//разбиение массива на $column массивов
						//echo Debug::vars('120', $column, $row); //exit;
						for ($i=0; $i<$row; $i++)
						{
							echo '<tr>';
								foreach(Arr::get($aaa, $i) as $key=>$value)
								{
									$value=Arr::flatten($value);
									;// exit;
									echo '<td>'.Form::checkbox('id_event_filter['.Arr::get($value, 'ID').']', Arr::get($value, 'ID'), (array_key_exists(Arr::get($value, 'ID'), $event_income_filter))? true : false, array('class'=>'checkbox')).iconv('windows-1251','UTF-8', Arr::get($value, 'NAME')).' ('.Arr::get($value, 'ID').')</td>';
								}
							echo '</tr>';	
						}
						?>
						</tbody>
					</table>
					<?php 
					
					}
					?>
				</div>
			</div>
		</div> 



      
    <!-- Инициализация виджета "Bootstrap datetimepicker" -->

	<div class="panel-heading">
		<h3 class="panel-title"><?echo 'Диапазон дат'?></h3>
	</div>
	<div class="panel-body">
    <div class="row">
		<div class="col-xs-1">
			<p class="text-primary text-center">c</p>
		</div>
		<div class="col-xs-5">
		<div class="form-group">
		  <div class="input-group date" id="datetimepicker1">
			
			
			<input type="text" class="form-control" name="timeFrom" value="">
			<span class="input-group-addon">
			  <span class="glyphicon glyphicon-calendar"></span>
			</span>
			
  
		  </div>
		</div>
		</div>
		<div class="col-xs-1">
			<p class="text-primary text-center">по</p>
		</div>
		<div class="col-xs-5">
		<div class="form-group">
		  <div class="input-group date" id="datetimepicker2">
			<input type="text" class="form-control" name="timeTo">
			<span class="input-group-addon">
			  <span class="glyphicon glyphicon-calendar"></span>
			</span>
		  </div>
		</div>
		</div>
    </div>
	<?php
	$eventTable='';
	//$rz='	';
	$rz=';';
	$ps="\r\n";
	$eventTable = __('№ п/п') . $rz.__('Код события').$rz.__('Время события').$rz.__('Вход').$rz. __('ИД пользователя').$ps; 
	
	echo Form::button('todo', 'Получить журнал событий', array('value'=>'getEvent','class'=>'btn btn-success', 'type' => 'submit'));	
	echo Form::button('todo', 'Сохранить журнал событий', array('value'=>'eventExport','class'=>'btn btn-primary', 'type' => 'submit', 'onclick'=>'return confirm(\''.__('Сохранить отчет в файл?').'?\') ? true : false;'));
	
 ?>
	  
	 </div>
</div>
	 
	 
	 
	 

	
	
	
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title"><?echo __('Журнал событий');?></h3>
	</div>
	<div class="panel-body">

<table id="tablesorter" class="table table-striped table-hover table-condensed tablesorter">
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
			<th><?echo __('Гараж');?></th>
			
			
			
			
			
		</tr>
		</thead>
		<tbody>
		<?php 
		$i=0;
		$checked='no';
	
		foreach($event_list as $key=>$value)
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
			echo '<tr class="'.$ecolor.'">';
				$garage= new Garage(Arr::get($value,'ID_GARAGE'));
				echo '<td>'.($i+1).'</td>';
				echo '<td>'.Arr::get($value,'EVENT_NAME').' ('.Arr::get($value,'EVENT_CODE').')</td>';
				echo '<td>'.Arr::get($value,'GATE_NAME').' ('.Arr::get($value,'ID_GATE').')</td>';
				echo '<td>'.Date::formatted_time(Arr::get($value,'EVENT_TIME'), 'd.m.Y H:i:s').'</td>';
				echo '<td>'.(Arr::get($value,'IS_ENTER')? 'Въезд' : '').'</td>';
				echo '<td>'.(Arr::get($value,'IS_ENTER')? '' : 'Выезд').'</td>';
				
				echo '<td>'.HTML::anchor('grz/history/'.Arr::get($value,'GRZ'), Arr::get($value,'GRZ')).'</td>';
				echo '<td>'.Arr::get($value,'SURNAME').' '
							.Arr::get($value,'NAME').' '
							.Arr::get($value,'PATRONYMIC').' ('
							.Arr::get($value,'ID_PEP').')</td>';
				echo '<td>'.Arr::get($value,'COMMENT').'</td>';
				echo '<td>'.HTML::anchor('garage/edit_garage/'.Arr::get($value,'ID_GARAGE'), iconv('windows-1251','UTF-8', $garage->name)).' </td>';
				
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
		
			echo Form::hidden('eventTable', $eventTable); 
		?>
</div>
</div>
<?php echo Form::close();?>


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
  		//$("#tablesorter").tablesorter({sortList:[[0,0]], headers: { 0:{sorter: false}}});
  		$("#tablesorter").tablesorter();
  	});
    </script>
	
  

