<? //http://itchief.ru/lessons/bootstrap-3/30-bootstrap-3-tables;
// страница отображения список ГРЗ парковочным площадям
//echo Debug::vars('3', $load_list_parking, $info_parking); //exit;
/*
  0 => array(3) (
        "ENTERTIME" => string(19) "2023-01-10 14:29:57"
        "ID_CARD" => string(9) "Y268EH197"
        "COUNTERID" => string(1) "1"
    )
	*/


?>
<script>
    $(function(){
        window.setTimeout(function(){
            $('#my-alert').alert('close');
        },5000);
    });
</script>
<script type="text/javascript">
     
  	$(function() {		
  		$("#tablesorter").tablesorter({sortList:[[0,0]], headers: { 0:{sorter: false}}});
  	});	
	
</script>			
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title"><?echo __('parking_load_list').' '. Arr::get($info_parking, 'name');?></h3>
	</div>
	<div class="panel-body">
		<?php
			echo Kohana::message('rubic','rubic_list_desc');
			//echo Debug::vars('11', $count_busy);
		?>
<table id="tablesorter" class="table table-striped table-hover table-condensed">


		<tr>
			<th><?php echo __('pp');?></th>
			<th><?php echo __('grz');?></th>
			<th><?php echo __('date_come_in');?></th>
			<th><?php echo __('todo');?></th>
			
		</tr>
		<?php 
		$i=1;
		$checked='no';
		$total_place=0;
		$total_occup=0;
		$total_vacant=0;
		foreach($load_list_parking as $key=>$value)
		{
			echo Form::open('parking/control');
			echo '<tr>';
				echo '<td>'.$i.'</td>';
				echo '<td>'. HTML::anchor('gate?id_parking='.Arr::get($value,'ID', 0) , Arr::get($value,'ID_CARD')).'</td>';
				echo Form::hidden('grz_for_del', Arr::get($value,'ID_CARD'));
				echo Form::hidden('id_parking', Arr::get($value,'COUNTERID'));
				echo '<td>'.Arr::get($value,'ENTERTIME').'</td>';
				$mess=__('delete_grz_from_parking',array('grz'=>Arr::get($value,'ID_CARD'), 'parking_name'=>Arr::get($info_parking, 'name')));
				echo '<td>'.Form::button('todo', 
					__('del_grz_from_parking'),
					array('value'=>'del_grz_from_parking',
						'class'=>'btn btn-danger  btn-xs', 
						'type' => 'submit', 
						'onclick'=>'return confirm(\''. $mess.'\') ? true : false;')
						).'</td>';
			
			echo '</tr>';	
			echo Form::close();
			$i++;
		
		}
		
		?>
		<tr>
			<td>Итого:</td>
			<td><?php echo count($load_list_parking);?></td>
			<td></td>
			
			
		</tr>
	</table>		
		
</div>
</div>





<?php 
	echo Form::open('parking/control');
	if(Auth::Instance()->logged_in())
		{?>
	<div class="panel panel-primary">
	  <div class="panel-heading">
		<h3 class="panel-title"><?echo __('add_grz_in_parking');?></h3>
	  </div>
	  <div class="panel-body">
	  
		<?
		echo 'Добавить ГРЗ  ';
		echo Form::input('add_grz_in_parking', 'ГРЗ');
		
		echo Form::button('todo', __('add_grz_in_parking'), array('value'=>'add_parking','class'=>'btn btn-success', 'type' => 'submit', 'disabled'=>'disabled'));	
		
		?>	

	  </div>

	</div>


<?php }?>

<?echo Form::close();?>






  