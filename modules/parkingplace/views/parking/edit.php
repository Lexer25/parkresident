<? //http://itchief.ru/lessons/bootstrap-3/30-bootstrap-3-tables;
 //echo Debug::vars('11', $rp_info); 
// страница для редактирования сущности
//echo Debug::vars('4');exit;
//echo Debug::vars('5', $parking);//exit;
echo Form::open('ParkingPlace/control');

if(Auth::Instance()->logged_in())
{?>
<script type="text/javascript">
     
  	$(function() {		
  		$("#tablesorter").tablesorter({sortList:[[0,0]], headers: { 0:{sorter: false}}});
  	});	
	
</script>
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo __('Конфигурация парковочной площадки').' '. iconv('windows-1251','UTF-8',$parking->name);
		echo Form::hidden('id', $parking->id);
		
		
		
		?></h3>
	</div>
	<div class="panel-body">
		
		<?php 
	
		 echo __('Название парковочной площадки').' '.Form::input('name', iconv('windows-1251','UTF-8', $parking->name), array('maxlength'=>50)).'<br>';
	/*	echo __('Активен ').Form::checkbox( 'is_active', 1, $parking->is_active == 1, array('disabled'=>'disabled')).'<br>';
		echo __('Контроль направления проезда ').Form::checkbox( 'is_apb', 1, $parking->is_apb).'<br>';
		echo __('Уменьшать счетчик занятых мест при выезде').Form::checkbox( 'is_decrementCount', 1, $parking->is_decrementCount).'<br>';
		echo __('Работа в режиме "Тест" ').Form::checkbox( 'is_test', 1, $parking->is_test).'<br>';
	*/	echo __('ID'). ' '. $parking->id.'<br>'; 
		//echo __('parent'). ' '. $parking->parent.'<br>';
		$residenceList=Model::factory('ResidentPlace')->get_list();//получил список id жилых комплексов
		//список ЖК для выбора
		$selectList=array();
		//вывожу список жилых комплексов для вывода
		foreach ($residenceList as $key=>$value)
		{
			$residence=new Residence(Arr::get($value, 'ID'));
			$selectList[Arr::get($value, 'ID')]=iconv('windows-1251','UTF-8', $residence->name);
			
		}
		echo 'Жилой комплекс: '.Form::select('parent', $selectList, $parking->parent);

				?>
		
		<table class="table table-striped table-hover table-condensed">
			<tr>
				<th>Параметр</th>
				<th>Значение</th>
				<th>Прим.</th>
			</tr>
			<tr>
				<td><?php echo __('Активен ');?></td>
				<td><?php echo Form::checkbox( 'is_active', 1, $parking->is_active == 1, array('disabled'=>'disabled'));?></td>
			</tr>
			<tr>
				<td><?php echo __('Контроль направления проезда');?></td>
				<td><?php echo Form::checkbox( 'is_apb', 1, $parking->is_apb);?></td>
			</tr>
			<tr>
				<td><?php echo __('Уменьшать счетчик занятых мест при выезде');?></td>
				<td><?php echo Form::checkbox( 'is_decrementCount', 1, $parking->is_decrementCount);?></td>
			</tr>
			<tr>
				<td><?php echo __('Работа в режиме "Тест"');?></td>
				<td><?php echo Form::checkbox( 'is_test', 1, $parking->is_test)?></td>
			</tr><tr>
				<td><?php echo __('Количество мест (по проектной документации)');?></td>
				<td><?php echo Form::input('count',  $parking->count);?></td>
			</tr>
			
		</table>
		<?php
		echo '<br>';
		echo __('Дата создания'). ' '. $parking->created.'<br>';
		echo __('Количество мест'). ' '.$parking->count.'<br>';
		
		?>
			<?php
		echo Form::button('todo', Kohana::message('rubic','rubic_change_config'), array('value'=>'update','class'=>'btn btn-success', 'type' => 'submit'));	
		?>
	</div>
</div>

<?php }?>





<?echo Form::close();?>	