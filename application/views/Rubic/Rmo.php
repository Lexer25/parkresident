<?php
/*
echo (isset($garage_info))?  Debug::vars('2', $garage_info) : 'no garage_info';
echo(isset($place_income_garage))?  Debug::vars('2', $place_income_garage) : 'no place_income_garage';
echo(isset($org_income_garage))?  Debug::vars('2', $org_income_garage) : 'no org_income_garage';
echo(isset($get_grz_in_parking))?  Debug::vars('2', $get_grz_in_parking) : 'no get_grz_in_parking';
echo(isset($place_grz_garage_))?  Debug::vars('2', $place_grz_garage_) : 'no place_grz_garage_';
echo Debug::vars(Session::instance()->as_array()); 
*/

?>
<div class="panel panel-primary"> 

  <div class="panel-heading">
    <h3 class="panel-title"><?php echo __('Найдена информация по машиноместу № ').' '.Session::instance()->get('place_for_search');?></h3>
    <h3 class="panel-title"><?php //echo __('Разрешить проезд выбранного ГРЗ на машиноместо').' '.Session::instance()->get('place_for_search');?></h3>
  </div>
  <div class="panel-body">
	
<?// Раздел ввода данных
	//echo Debug::vars('12', $garage_info);
	if(!isset($garage_info))
	{
		echo __('comment_for_search');
		echo Form::open('rmo/control');
			echo Form::button('todo', 'Поиск машиноместа', array('value'=>'find_place','class'=>'btn btn-primary', 'type' => 'submit'));
			echo ' ';
			echo Form::input('num_for_search', '1', array( 'type'=>'number', 'max'=>999));
			echo ' ';
			//echo Form::button('todo', 'Поиск ГРЗ', array('value'=>'find_grz','class'=>'btn btn-success', 'type' => 'submit'));	
		echo Form::close();	
	}
//вывод информации по гаражу
if(isset($garage_info))
{
	//echo Debug::vars('12', $garage_info);

	$level_count=array();
	foreach($place_income_garage as $key=>$value)
	{
		//echo Debug::vars('41', $key, $value);
		if(!array_key_exists(Arr::get($value,'ID_PARKING'), $level_count)) $level_count[]=Arr::get($value,'ID_PARKING');
	}
	//echo Debug::vars('44', count($level_count), $level_count);
?>
<table id="tablesorter" class="table table-striped table-hover table-condensed tablesorter">

	<thead allign="center">
		<tr>
			<th><?echo __('Название гаража')?></th>
			<th><?echo __('Кол-во квартир (название)');?></th>
			<th><?echo __('Кол-во ГРЗ<br>(ГРЗ, модель)');?></th>
			<th><?echo __('Кол-во машиномест<br>(номера машиномест)');?></th>
			<th><?echo __('Количество ГРЗ на территории<br>Перечень<br>Дата въезда');?></th>
			<th><?echo __('Осталось<br>свободных<br>мест');?></th>
			
		</tr>

		<tr>
			<th>1</th>
			<th>2</th>
			<th>3</th>
			<th>4</th>
			<th>5</th>
			<th>6</th>
			
		</tr>

		
		</thead>
		<tbody>
		<?php 
			echo '<tr>';
				//название гаража
				echo '<td>'.Arr::get($garage_info, 'NAME').'<br>(id_garage='.Arr::get($garage_info, 'ID').')</td>'; 
				
				//перечень квартир
				echo '<td>';
					foreach($org_income_garage as $key=>$value)
					{
					 echo Arr::get($value, 'NAME').'<br>(id_garage='.Arr::get($value, 'ID').')';
					}
				echo '</td>';
				
				//перечень ГРЗ
				echo '<td>';
					//разрешить въезд известным ГРЗ
					//echo Form::open('rmo/mqtt');
					echo Form::open('rmo/opengateCVS');
					echo Form::hidden('id_garage', Arr::get($garage_info, 'ID'));
					echo Form::hidden('place_for_open', Session::instance()->get('place_for_search'));
					echo Form::hidden('parking_for_open', Arr::get(Arr::get($place_income_garage, Session::instance()->get('place_for_search')), 'ID_PARKING'));
	
						$total_grz=count($place_grz_garage_);
						//echo Debug::vars('57', $place_grz_garage_);
						echo __('Всего ГРЗ:').' '. $total_grz.'<br>';
						foreach($place_grz_garage_ as $key=>$value)
						{
						 if (Arr::get($value, 'ACTIVE') >0)
						 {
							echo Form::button('opendoor', Arr::get($value, 'GRZ').' ('.Arr::get($value, 'NAME').')', array('value'=>Arr::get($value, 'GRZ'), 'class'=>'btn btn-success btn-xs', 'type' => 'submit', 'onclick'=>'return confirm(\''.__('Будет открыт въезд для grz на парковку PARKING_NAME. Открыть', array('grz'=>Arr::get($value, 'GRZ'), 'PARKING_NAME'=>Arr::get(Arr::get($place_income_garage, Session::instance()->get('place_for_search')), 'PARKING_NAME'))).'?\') ? true : false;')).'<br><br>';
						 } else
						 {
							echo Form::button('opendoor', Arr::get($value, 'GRZ').' ('.Arr::get($value, 'NAME').')', array('value'=>Arr::get($value, 'GRZ'), 'disabled'=>'disabled', 'class'=>'btn btn-danger btn-xs', 'type' => 'submit'));
							echo ' '.__('Не активен').'<br>';
							 
						 }
						
				
						}
						
					echo Form::close();	
					
					echo Form::open('rmo/opengate_unknow');
					
					echo Form::hidden('id_garage', Arr::get($garage_info, 'ID'));
					echo Form::hidden('place_for_open', Session::instance()->get('place_for_search'));
					echo Form::hidden('parking_for_open', Arr::get(Arr::get($place_income_garage, Session::instance()->get('place_for_search')), 'ID_PARKING'));
					//разрешить въезд неизвестным ГРЗ
					
					
					echo Form::input('unknow_plate_for_insert', '1', array( 'type'=>'text', 'maxlength'=>'10'));
					echo ' ';
					echo Form::button('todo', 'Вставка неизвестного ГРЗ для проезда', array('value'=>'insert_unknow_plate','class'=>'btn btn-primary', 'type' => 'submit'));
					echo '<br>(не более 10 символов)';
					echo Form::close();	
					
				echo '</td>';
							
								
				echo '<td>'; //echo Debug::vars('50', $place_income_garage); список машиномест
				$total_place=0;
				$total_place=count($place_income_garage);
					echo __('Всего машиномест ').' '.$total_place.'<hr>';
					//echo Debug::vars('94', $place_income_garage);
					foreach($place_income_garage as $key=>$value)
					{
					 if(Arr::get($value, 'ID_PARKING') == 1) $parking_name='-1 этаж';
					 if(Arr::get($value, 'ID_PARKING') == 4) $parking_name='-2 этаж';
					 //echo Arr::get($value, 'ID').' ('.Arr::get($value, 'ID_PARKING').')<br>';
					 echo Arr::get($value, 'ID').' ('.$parking_name.')<br>';
					}
				echo '</td>';
				
				echo '<td>';
				
					//echo Debug::vars('52', count($get_grz_in_parking), $get_grz_in_parking);
					$occuped_place=0;
					$occuped_place=count($get_grz_in_parking);
					 echo __('Занято мест ').$occuped_place.'<hr>';
					
					foreach($get_grz_in_parking as $key=>$value)
					{
						echo $key.' ('.Arr::get($value,'ENTER_TIME').')<br>';
					}
					
				echo '</td>';
				
				echo '<td>'.($total_place - $occuped_place).'</td>';
			echo '</tr>';
		?>
		</tbody>
	</table>
	
<?php
	//echo Debug::vars('132', $garage_info, $place_grz_garage_, $get_grz_in_parking, $place_income_garage, $get_garage_parking_list );
	}


?>
<!--
<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="100" height="100" viewBox="0 0 100 100" >

  <symbol id="sym01" viewBox="0 0 150 110">
    <circle cx="50" cy="50" r="40" stroke-width="8" stroke="red" fill="red"/>
    <circle cx="90" cy="60" r="40" stroke-width="8" stroke="green" fill="white"/>
  </symbol>
  <use xlink:href="#sym01" x="0" y="0" width="100" height="50"/>
  <use xlink:href="#sym01" x="0" y="50" width="75" height="38"/>
  <use xlink:href="#sym01" x="0" y="100" width="50" height="25"/>
</svg>
-->
	
	

</div>	
</div>
	
  

