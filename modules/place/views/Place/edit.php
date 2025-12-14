<? //http://itchief.ru/lessons/bootstrap-3/30-bootstrap-3-tables;
 //echo Debug::vars('11', $rp_info); 
// страница для редактирования сущности
//echo Debug::vars('4');exit;
//echo Debug::vars('5', $place);//exit;

?>
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo __('Конфигурация машиноместа').' '. iconv('windows-1251','UTF-8',$place->name);
		echo Form::hidden('id', $place->id);

		?></h3>
	</div>
	<div class="panel-body">
		
		<?php 
			
			$parking=new Parking($place->id_parking);
			$parkingPlace=Model::factory('ParkingPlace')->get_list_for_select();//получил список id жилых комплексов

			echo Form::open('Place/control');
			echo Form::hidden('placenumber', $place->placenumber).'<br>';
			echo __('(ID').$place->id.')<br>';
			$selectList=array();
			/* echo 'Парковочная площадка: '.Form::select('id_parking', $parkingPlace, $place->id_parking).' '.__('(ID').$place->id_parking.')';
			echo '<br>';
			echo __('Название').Form::input('name', iconv('windows-1251','UTF-8', $place->name), array('maxlength'=>50));
			echo '<br>';
			echo __('Описание').Form::input('description', iconv('windows-1251','UTF-8', $place->description), array('maxlength'=>50)).'<br>';
			echo __('Описание2').Form::input('note', iconv('windows-1251','UTF-8', $place->note), array('maxlength'=>50)).'<br>';
			 */
			echo __('
			<table class="table table-striped table-hover table-condensed">
				<tr>
					<th>:header1</th>
					<th>:header2</th>
				</tr>
				<tr>
					<td>:name1</td>
					<td>:value1</td>
				</tr>
				<tr>
					<td>:name2</td>
					<td>:value2</td>
				</tr>
				<tr>
					<td>:name3</td>
					<td>:value3</td>
				</tr>
				<tr>
					<td>:name4</td>
					<td>:value4</td>
				</tr>
				
				
			</table>', array(
				':header1'=>'Параметр',
				':header2'=>'Значение',
				':name1'=>'Парковочная площадка',
				':value1'=>Form::select('id_parking', $parkingPlace, $place->id_parking).' '.__('(ID').$place->id_parking.')',
				':name2'=>'Название',
				':value2'=>Form::input('name', iconv('windows-1251','UTF-8', $place->name), array('maxlength'=>50)),
				':name3'=>'Описание',
				':value3'=>Form::input('description', iconv('windows-1251','UTF-8', $place->description), array('maxlength'=>50)),
				':name4'=>'Описание2',
				':value4'=>Form::input('note', iconv('windows-1251','UTF-8', $place->note), array('maxlength'=>50)),
				
				
				
				));
			
			if(Auth::Instance()->logged_in()) echo Form::button('todo', Kohana::message('rubic','rubic_change_config'), array('value'=>'update','class'=>'btn btn-success', 'type' => 'submit'));	
		echo Form::close();	
		?>
		
		
	</div>
</div>



