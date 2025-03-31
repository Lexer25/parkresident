<? //http://itchief.ru/lessons/bootstrap-3/30-bootstrap-3-tables;
// страница отображает список ГРЗ, имеющих право въезда на парковку
//echo Debug::vars('3', $grz_list);

?>
<script type="text/javascript">
     
  	$(function() {		
  		$("#tablesorter").tablesorter({sortList:[[0,0]], headers: { 0:{sorter: false}}});
  	});	
	
</script>
			
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title"> Панель эмуляции системы распознавания ГРЗ CVS<br><?php echo __('grz_list_on_parking', array('count_grz'=>count($grz_list)));?></h3>
	</div>
	<div class="panel-body">

<table id="tablesorter" class="table table-striped table-hover table-condensed tablesorter">

	<thead allign="center">
		<tr>
			<th><?echo __('pp');?></th>
			<!--<th><?echo __('select');?></th>-->
			<th><?echo __('parking_name');?></th>
			<th><?echo __('pep_name');?></th>
			<th><?echo __('grz');?></th>
			<th><?echo __('id_pep');?></th>
			<!--<th><?echo __('time_from');?></th>
			<th><?echo __('time_to');?></th>
			<th><?echo __('gate_name');?></th>-->
			<th><?echo __('in_parking');?></th>
			<th><?echo __('come_in_gate');?></th>
			<th><?echo __('come_out_gate');?></th>
			<th><?echo __('test_gate-1');?></th>
			<th><?echo __('test_gate-2');?></th>
			
			
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
				echo '<td>'.$i++.'</td>';
				//if($i==0) echo '<td>'.Form::radio('id_rp', Arr::get($value,'ID'), FALSE, array('checked'=>$checked)).'</td>';
				//if($i>0) echo '<td>'.Form::radio('id_rp', Arr::get($value,'ID'), FALSE).'</td>';
				//echo '<td>'.Arr::get($value,'ID_PARKING').'</td>';
				echo '<td>'.Arr::get($value,'NAME_ACCESS').'</td>';
				echo '<td>'.Arr::get($value,'GRZ_MODEL').'</td>';
				echo '<td> <a name="'.Arr::get($value,'ID_CARD').'"></a>'.Arr::get($value,'ID_CARD').'</td>';
				echo '<td>'.Arr::get($value,'GARAGE_NAME', 'ttt').'</td>';
				echo '<td>'.Arr::get($value,'ENTERTIME').'</td>';
				echo Form::open('grz/car_in_parking');
				echo Form::hidden('id_parking', Arr::get($value,'ID_PARKING'));
				echo '<td>'.
					Form::button('car_in_parking', 'IN', array('value'=>Arr::get($value,'ID_CARD'),'class'=>'btn btn-success btn-xs btn-block', 'type' => 'submit')).
					'</td>';
					echo Form::close();
					echo Form::open('grz/car_out_parking');
					echo Form::hidden('id_parking', Arr::get($value,'ID_PARKING'));
				echo '<td>'.
					Form::button('car_out_parking', 'OUT', array('value'=>Arr::get($value,'ID_CARD'),'class'=>'btn btn-danger btn-xs btn-block', 'type' => 'submit')).
					'</td>';
					echo Form::close();
					
					
					echo Form::open('grz/test_car_parking');
					
				echo '<td>'.
					Form::button('test_door[4]', 'IN 4', array('value'=>Arr::get($value,'ID_CARD'),'class'=>'btn btn-success btn-sm', 'type' => 'submit')).
					Form::button('test_door[1]', 'OUT 1', array('value'=>Arr::get($value,'ID_CARD'),'class'=>'btn btn-success btn-sm', 'type' => 'submit')).
					'</td>';
				echo '<td>'.	
					Form::button('test_door[2]', 'IN 2', array('value'=>Arr::get($value,'ID_CARD'),'class'=>'btn btn-success btn-sm', 'type' => 'submit')).
					Form::button('test_door[3]', 'OUT 3', array('value'=>Arr::get($value,'ID_CARD'),'class'=>'btn btn-success btn-sm', 'type' => 'submit')).
					'</td>';
					echo Form::close();

				
				
			echo '</tr>';	
			
		}
		
		?>
		</tbody>
	</table>		
	
		
</div>
</div>








