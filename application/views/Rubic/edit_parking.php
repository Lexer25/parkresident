<? //http://itchief.ru/lessons/bootstrap-3/30-bootstrap-3-tables;
/* echo Debug::vars('11', 
	$rubic_getinfo,
			$list_key_into_parking,
			$apb_device_list,
//			$door_list,
			$rubic_getinfo); */
// страница отображения данных по парковочной системе
echo Form::open('parking/control');

if(Auth::Instance()->logged_in())
{?>
<script type="text/javascript">
     
  	$(function() {		
  		$("#tablesorter").tablesorter({sortList:[[0,0]], headers: { 0:{sorter: false}}});
  	});	
	
</script>
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title"><?echo Kohana::message('rubic','rubic_config').' '. Arr::get($rubic_getinfo, 'name');
		echo Form::hidden('id_parking', Arr::get($rubic_getinfo,'id_parking'));
		echo Form::hidden('id_org', Arr::get($rubic_getinfo,'id_org'));
		
		?></h3>
	</div>
	<div class="panel-body">
		
		<?php 
		//echo Debug::vars('11', $rubic_getinfo, Arr::get($rubic_getinfo, 'ENABLED'));
		//echo Form::hidden('id_rubic', Arr::get($rubic_getinfo, 'ID'));
		echo Kohana::message('rubic','rubic_name').Form::input('name', Arr::get($rubic_getinfo, 'name'), array('maxlength'=>50)).'<br>';
		echo Kohana::message('rubic','id_rubic'). ' '. Arr::get($rubic_getinfo, 'id_parking').'<br>';
		echo Kohana::message('rubic','rubic_maxcount').Form::input('maxcount', Arr::get($rubic_getinfo, 'maxcount')).'<br>';
		//echo Kohana::message('rubic','rubic_tablo_row').Form::input('position', Arr::get($rubic_getinfo, 'POSITION')).'<br>';
		//echo Kohana::message('rubic','rubic_org_name').': '.Arr::get($rubic_getinfo, 'org_name').'<br>';
		//echo 'точки въезда и выезда<br>';
		?>
			<?php
		echo Form::button('todo', Kohana::message('rubic','rubic_change_config'), array('value'=>'change_config','class'=>'btn btn-success', 'type' => 'submit'));	
		?>
	</div>
</div>

<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title"><?echo Kohana::message('apb','apb_select_door')?></h3>
	</div>
	<div class="panel-body">
		<?php echo Kohana::message('apb','Kp_panel_inside');
		//echo Debug::vars('79', $door_list); exit;
		//echo Form::select('id_dev', $door_list, array('multiple'=>'"multiple"'));
		//echo Form::select('id_dev', $door_list);
		echo Form::button('todo', Kohana::message('apb','apb_door_add_as_enter','Добавить как вход'), array('value'=>'add_gate_enter','class'=>'btn btn-success', 'type' => 'submit'));
		echo Form::button('todo', Kohana::message('apb','apb_add_door_as_exit','Добавить как выход'), array('value'=>'add_gate_exit','class'=>'btn btn-success', 'type' => 'submit'));
		?>
	</div> 
</div>

<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title"><?echo Kohana::message('apb','apb_door')?></h3>
	</div>
			<div class="panel-body">
		<?php
			echo Kohana::message('apb','apb');
			
			
		?>
		
			
	
	</div> 
</div> 


<?php }?>



<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title"><?echo Kohana::message('rubic','rubic_view_inside')?></h3>
	</div>
	<div class="panel-body">
		<?php echo Kohana::message('rubic','Kp_panel_inside2');?>


		
		<?php
		if(Auth::Instance()->logged_in())
		{
			echo Form::button('todo', Kohana::message('rubic','rubic_del_parking_inside','Очистить таблицу'), array('value'=>'clear_parking_inside','class'=>'btn btn-success', 'type' => 'submit'));
		}
		?>
	</div> 
</div>

<?echo Form::close();?>	