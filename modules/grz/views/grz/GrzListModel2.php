<? //http://itchief.ru/lessons/bootstrap-3/30-bootstrap-3-tables;
// страница отображает список ГРЗ, имеющих право въезда на парковку
//echo Debug::vars('3', $grz_list); exit;

?>
<script type="text/javascript">
     
  	$(function() {		
  		$("#tablesorter").tablesorter({sortList:[[0,0]], headers: {}});
  	});	
</script>			
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo __('grz_list_on_parking', array('count_grz'=>count($grz_list)));?></h3>
	</div>
	<div class="panel-body">

<table id="tablesorter" class="table table-striped table-hover table-condensed tablesorter">

	<thead allign="center">
		<tr>
			<th><?php echo __('pp');?></th>
			<th><?php echo __('grz');?></th>
			<th><?php echo __('pep_name');?></th>
			<th><?php echo __('Активность');?></th>
			<th><?php echo __('Категории доступа СКУД');?></th>
			<th><?php echo __('Гараж');?></th>
			<th><?php echo __('На парковке');?></th>
			<th><?php echo __('Дата и время заезда');?></th>
			<th><?php echo __('come_in_gate');?></th>
			<th><?php echo __('come_out_gate');?></th>
			<?php
			if(Kohana::$config->load('artonitparking_config')->emulated_cvs)
			{?>
			<th><?php echo __('test_gate-1');?></th>
			<th><?php echo __('test_gate-2');?></th>
			<?php
			}
			?>
			
			
		</tr>
		
		</thead>
		<tbody>
		<?php 
		$i=1;
		$checked='no';
		$total_place=0;
		$total_occup=0;
		$total_vacant=0;
			
			
		foreach($grz_list as $key=>$value)
		{
			echo '<tr>';
				echo '<td>'
					.$i++
				//	.Debug::vars('57', $value)
			
					.'</td>';
				echo '<td>'. HTML::anchor('grz/history/'.Arr::get($value,'ID_CARD'), iconv('windows-1251','UTF-8',Arr::get($value,'ID_CARD')));
					if( preg_match("/[а-яё]/iu", iconv('windows-1251','UTF-8',Arr::get($value,'ID_CARD', '')))) echo '<br><span class="label label-danger">Русские буквы в ГРЗ</span>';
					//echo Debug::vars('60', $value);
					echo '</td>';
				echo '<td>';

					echo __('<abbr title="id_pep=:title">:GRZ_MODEL</abbr>', array(
						':title'=>Arr::get($value,'ID_PEP', ''),
						':GRZ_MODEL'=>iconv('windows-1251','UTF-8', Arr::get($value,'GRZ_MODEL', '')),
					));
					
				echo '</td>';
				echo '<td>';
				if(Arr::get($value,'ACTIVE', '0') == 1)
					{				
						echo '<span class="label label-success">Активен</span>';
					} else 
					{
						echo '<span class="label label-danger">Не активен</span>';
					}
					echo '</td>';
				//вывод списка категорий доступа
				echo '<td>';
				//echo Debug::vars('77', $accessNameList);exit;
				//echo '78<br>';
				if(Arr::is_array(Arr::get($value,'accessNameList')))
				{
					foreach (Arr::get($value,'accessNameList') as $key1=>$value1)
					{
						echo iconv('windows-1251','UTF-8',Arr::get($accessNameList, Arr::get($value1, 'ID'), '')).'<br>';
					};
				} else {
						//echo __('Нет_85');
						echo Arr::get($value,'ACCESSCOUNT');
				}
					echo '</td>';
					
				
				
				//вывод списка присвоенных гаражей
				
				$_garageName='95';
				echo '<td>';
				//echo '98<br>';
				if(Arr::is_array(Arr::get($value,'garageList'))){
					foreach (Arr::get($value,'garageList') as $key1=>$value1)
					{
						
						echo HTML::anchor('garage/edit_garage/'.Arr::get($value1, 'ID'), iconv('windows-1251','UTF-8',Arr::get($value1, 'NAME')));
					}
				} else {
					
					//echo '106 no';
				}
						
				echo '</td>';
					
				
				//на какой парковке находится
				echo '<td>';
										
				if(Arr::is_array(Arr::get($value,'onParkingList'))){
					foreach (Arr::get($value,'onParkingList') as $key1=>$value1)
					{
						
					//	echo HTML::anchor('garage/edit_garage/'.Arr::get($value1, 'ID'), iconv('windows-1251','UTF-8',Arr::get($value1, 'NAME')));
						echo  iconv('windows-1251','UTF-8',Arr::get($value1, 'NAME'));
					}
				} else {
					
					//echo '127 no';
				}
				
				
					
				echo '</td>';
			
			//время въезда на парковку		
				echo '<td>';
				
					
					if(Arr::is_array(Arr::get($value,'onParkingList'))){
					foreach (Arr::get($value,'onParkingList') as $key1=>$value1)
					{
						echo Arr::get($value1,'ENTERTIME');
						
					}
				} else {
					
					//echo '144 no';
				}
				echo '</td>';
					
				echo '<td>';	
					
					
					//echo '136<br>';
					
					if(Arr::is_array(Arr::get($value,'enabledParkingList'))){
						foreach (Arr::get($value,'enabledParkingList') as $key1=>$value1)
						{
							//echo iconv('windows-1251','UTF-8',Arr::get($value1, 'NAME'));
							echo Form::open('grz/car_in_parking');
							echo Form::hidden('id_parking', Arr::get($value1,'ID'));
							echo Form::hidden('id_pep', Arr::get($value,'ID_PEP'));
							echo Form::button('car_in_parking', 'IN '.iconv('windows-1251','UTF-8', Arr::get($value1, 'NAME')), array('value'=>Arr::get($value,'ID_CARD'),'class'=>'btn btn-success btn-xs', 'type' => 'submit'));
							echo Form::close();

						}
					} else {
						
						//echo '161 no';
					}
					
					
				echo '</td>';
					
					echo Form::open('grz/car_out_parking');
					
				echo '<td>'.
					Form::button('car_out_parking', 'OUT', array('value'=>Arr::get($value,'ID_CARD'),'class'=>'btn btn-danger btn-xs', 'type' => 'submit')).
					'</td>';
					echo Form::close();
					
					
					echo Form::open('grz/test_car_parking');
				if(Kohana::$config->load('artonitparking_config')->emulated_cvs)
				{					
				echo '<td>'.
					Form::button('test_door[4]', 'IN 4', array('value'=>Arr::get($value,'ID_CARD'),'class'=>'btn btn-success btn-sm', 'type' => 'submit')).
					Form::button('test_door[1]', 'OUT 1', array('value'=>Arr::get($value,'ID_CARD'),'class'=>'btn btn-success btn-sm', 'type' => 'submit')).
					'</td>';
				echo '<td>'.	
					Form::button('test_door[2]', 'IN 2', array('value'=>Arr::get($value,'ID_CARD'),'class'=>'btn btn-success btn-sm', 'type' => 'submit')).
					Form::button('test_door[3]', 'OUT 3', array('value'=>Arr::get($value,'ID_CARD'),'class'=>'btn btn-success btn-sm', 'type' => 'submit')).
					'</td>';
				}
					echo Form::close();

				
				
			echo '</tr>';	
			
		}
		
		?>
		</tbody>
	</table>		
	
		
</div>
</div>
<?php
	echo __('Время выполнения :t', array(':t'=>(microtime(true) - $t1)));
?>








