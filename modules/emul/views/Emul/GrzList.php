<? //http://itchief.ru/lessons/bootstrap-3/30-bootstrap-3-tables;
// страница отображает список ГРЗ, имеющих право въезда на парковку
//echo Debug::vars('3', $grz_list);

?>
<script type="text/javascript">
     
  	$(function() {		
  		$("#tablesorter").tablesorter({sortList:[[0,0]], headers: {}});
  	});	
</script>			
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo __('Эмуляция въездов и выездов', array('count_grz'=>count($grz_list)));?></h3>
	</div>
	<div class="panel-body">

<table id="tablesorter2" class="table table-striped table-hover table-condensed tablesorter">
	<thead>
		<tr>
			<th> Гараж </th>
			<th> Идентификаторы </th>
			<?php
			
				$list=Model::factory('Gates')->get_list_gate();//список гаражей
				//echo Debug::vars('35', $list);exit;
				foreach(Arr::get($list, 'res') as $key=>$value)
				{
					echo '<th>';
						//echo Debug::vars('39', $value);//exit;
						//echo $key;
						//echo iconv('CP1251','UTF-8', Arr::get($value, 'name'));
						echo Arr::get($value, 'name');
					echo '</th>';
				}
			
			?>
		</tr>
	</thead>
		<?php 
			foreach($garageList as $key=>$value)
			{
				echo '<tr>';
					//название гаража
					echo '<td>';
						//echo Debug::vars('30', $value);
						echo Arr::get($value, 'NAME').'('.Arr::get($value, 'ID').')';
					echo '</td>';
					
					
					//готовлю данные по текущему гаражу
					$garage=Model::Factory('Garage');
					$data=$garage->getGarageInfo(Arr::get($value, 'ID'));
					$cardInGarage=Arr::get($garage->getGarageInfo(Arr::get($value, 'ID')), 'grzInGarageList');
					//echo Debug::vars('54', $cardInGarage );exit;
					echo '<td>';
						foreach(array_slice(Arr::get($data, 'grzList'), 0, 3) as $key2=>$value2)
						{
								echo Arr::get($value2, 'GRZ');
								
								echo  array_key_exists(Arr::get($value2, 'GRZ'), $cardInGarage)? 'true' : 'false';
								if(array_key_exists(Arr::get($value2, 'GRZ'), $cardInGarage))
								{
									echo Form::button('todo', 'IN', array('value'=>'in','class'=>'btn btn-success btn-xs', 'type' => 'submit'));
								} else {
									echo Form::button('todo', 'IN', array('value'=>'in','class'=>'btn btn-warning btn-xs', 'type' => 'submit'));
								}
									
								echo '<br>';
								
						}
						foreach(array_slice(Arr::get($data, 'cardList'), 0, 3) as $key2=>$value2)
						{
								echo Arr::get($value2, 'GRZ');
								echo  array_key_exists(Arr::get($value2, 'GRZ'), $cardInGarage)? 'true' : 'false';
								if(array_key_exists(Arr::get($value2, 'GRZ'), $cardInGarage))
								{
									echo Form::button('todo', 'IN', array('value'=>'in','class'=>'btn btn-success btn-xs', 'type' => 'submit'));
								} else {
									echo Form::button('todo', 'IN', array('value'=>'in','class'=>'btn btn-warning btn-xs', 'type' => 'submit'));
								}
								
								echo '<br>';
						}
						
						
						//вывожу список CARD
						
					echo '</td>';	
								
				foreach(Arr::get($list, 'res') as $key4=>$value4)
				{
					
					echo '<td>';
						foreach(array_slice(Arr::get($data, 'grzList'), 0, 3) as $key2=>$value2)
						{
							//echo Debug::vars('41', $value4);
							echo Form::open('emul/test');
								echo Arr::get($value2, 'GRZ');
								echo Form::hidden('card', Arr::get($value2, 'GRZ'));
								echo Form::hidden('gate', Arr::get($value4, 'id'));
								
								echo Form::button('todo', 'IN', array('value'=>'in','class'=>'btn btn-success btn-xs', 'type' => 'submit')).
								Form::button('todo', 'OUT', array('value'=>'out','class'=>'btn btn-success btn-xs', 'type' => 'submit'));
								
							echo Form::close();
							 //echo HTML::anchor('emul/test?card='.Arr::get($value2, 'GRZ').'&gate='.Arr::get($value4, 'id'), Arr::get($value2, 'GRZ'));
							 echo '<br>';
						}
						//вывожу список CARD
						foreach(array_slice(Arr::get($data, 'cardList'), 0, 3) as $key2=>$value2)
						{
							//echo Debug::vars('41', $value3);
							echo Form::open('emul/test');
								echo Arr::get($value2, 'GRZ');
								echo Form::hidden('card', Arr::get($value2, 'GRZ'));
								echo Form::hidden('gate', Arr::get($value4, 'id'));
								
								echo Form::button('todo', 'IN', array('value'=>'in','class'=>'btn btn-success btn-xs', 'type' => 'submit')).
								 Form::button('todo', 'OUT', array('value'=>'out','class'=>'btn btn-success btn-xs', 'type' => 'submit'));
							echo Form::close();
							
							 //echo HTML::anchor('emul/test?card='.Arr::get($value2, 'GRZ').'&gate='.Arr::get($value4, 'id'), Arr::get($value2, 'GRZ'));
							 echo '<br>';
						}
						
					echo '</td>';
				}
					
				echo '</tr>'; 
				
				
			}
		?>
	<tbody>
	</tbody>
		
</table>


</div>
</div>
<?php
	echo __('Время выполнения :t', array(':t'=>(microtime(true) - $t1)));
?>








