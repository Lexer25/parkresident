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
					//.Debug::vars('57', $value)
					.'</td>';
				echo '<td>'. HTML::anchor('grz/history/'.Arr::get($value,'ID_CARD'), iconv('windows-1251','UTF-8',Arr::get($value,'ID_CARD')));
					if( preg_match("/[а-яё]/iu", iconv('windows-1251','UTF-8',Arr::get($value,'ID_CARD', '')))) echo '<br><span class="label label-danger">Русские буквы в ГРЗ</span>';
					//echo Debug::vars('60', $value);
					echo '</td>';
				echo '<td>'.iconv('windows-1251','UTF-8', Arr::get($value,'GRZ_MODEL', '')).'</td>';
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
				if(Arr::is_array(Arr::get($value,'accessNameList')))
				{
					foreach (Arr::get($value,'accessNameList') as $key1=>$value1)
					{
						//echo Debug::vars('82', $value1);exit;
						//получаю список категорий доступа, выданных этому пиплу.
						//Названия беру из списка категорий доступа.
						echo iconv('windows-1251','UTF-8',Arr::get($accessNameList, Arr::get($value1, 'ID_ACCESSNAME'), '')).'<br>';
						
					};
				} else {
						//echo __('Нет_85');
						echo Arr::get($value,'ACCESSCOUNT');
				}
					echo '</td>';
					
				
				
				//вывод списка присвоенных гаражей
				//$_info=Arr::flatten(Arr::get($value,'garageList'));
				//$_garageName=iconv('windows-1251','UTF-8',Arr::get($_info, 'NAME'));
				//$_garageName=Arr::get($_info, 'NAME');
				$_garageName='95';
				echo '<td>';
						echo HTML::anchor('garage/edit_garage/'.Arr::get($value, 'ID_GARAGE'), iconv('windows-1251','UTF-8',Arr::get($value, 'GARAGENAME')));
						//echo '98 ';
						//echo Debug::vars('99',$value);
						//echo Debug::vars('99',$grzInfoListAccessNameList);exit;
						//echo iconv('windows-1251','UTF-8', Arr::get($value,'GARAGENAME'));
				echo '</td>';
					
				
				//на какой парковке находится
				//$_info=Arr::flatten(Arr::get($value,'inParking'));
				echo '<td>';
					//echo ' '.iconv('windows-1251','UTF-8',Arr::get($_info, 'NAME'));
					//echo '106 ';
					echo iconv('windows-1251','UTF-8', Arr::get($value,'PARKIGNAME'));
					
				echo '</td>';
			
			//время въезда на парковку		
				echo '<td>';
				
					//echo Arr::get($_info, 'ENTERTIME','');
					//echo '114';
					echo Arr::get($value,'ENTERTIME');
				echo '</td>';
					
				echo '<td>';	
					
					
					//echo '136';
					echo Arr::get($value,'PARKINGCOUNT');
					
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








