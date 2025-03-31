<? //http://itchief.ru/lessons/bootstrap-3/30-bootstrap-3-tables;
 //echo Debug::vars('2', $info_gate);

	
// страница отображения данных по парковочной системе
echo Form::open('gate/control');

if(Auth::Instance()->logged_in())
{?>
<script type="text/javascript">
     
  	$(function() {		
  		$("#tablesorter").tablesorter({sortList:[[0,0]], headers: { 0:{sorter: false}}});
  	});	
	
</script>
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo __('gate_config').' '. Arr::get($info_gate, 'name');
		echo Form::hidden('id', Arr::get($info_gate,'id'));
		echo Form::hidden('is_enter', Arr::get($info_gate,'is_enter'));
		?></h3>
				
				

	
	</div>
	
	<div class="panel-body">
		
		<?php 
		//echo Debug::vars('11', $info_gate, Arr::get($info_gate, 'ENABLED'));

		echo __('gate_name').Form::input('name', Arr::get($info_gate, 'name'), array('maxlength'=>50)).'<br>';
		echo __('id_gate'). ' '. Arr::get($info_gate, 'id').'<br>';
		echo __('id_parking'). ' '. Arr::get($info_gate, 'id_parking').'<br>';
		echo Form::hidden('id_parking', Arr::get($info_gate, 'id_parking'));
		
		?>
			<table id="tablesorter" class="table table-striped table-hover table-condensed tablesorter">
		<thead allign="center">
		<tr>
			<th><?php echo 'Направление';?></th>
			<th><?php echo 'IP табло';?></th>
			<th><?php echo 'PORT табло';?></th>
			<th><?php echo 'IP контроллера';?></th>
			<th><?php echo 'PORT контроллера';?></th>
			<th><?php echo 'ID видеокамеры';?></th>
			<th><?php echo 'ID точки прохода';?></th>
			<th><?php echo 'Режим работы';?></th>
		</tr>
			</thead>
		<tbody>	
		<?php
		/*
		0 - режим шлюза
1 - реле 0 ворота
2 - реле 1 шлагбаум
3 - и реле 0 ,и реле 1
*/
			$patter_IP='^(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$';// шаблон IP адреса
			
			echo '<tr>';
				echo '<td>'.Form::select('is_enter',
					array('1'=>'Въезд 1', '0'=>'Выезд 0'),
					Arr::get($info_gate,'is_enter')).' '.Arr::get($info_gate,'is_enter','--').'</td>';
				echo '<td>'.Form::input('tablo_ip', Arr::get($info_gate,'tablo_ip','--'), array('pattern'=>$patter_IP)).'</td>';
				echo '<td>'.Form::input('tablo_port',Arr::get($info_gate,'tablo_port','--'), array('type'=>'number', 'size'=>'5', 'min'=>'1', 'max'=>'65535')).'</td>';
				echo '<td>'.Form::input('box_ip',Arr::get($info_gate,'box_ip','--'), array('pattern'=>$patter_IP)).'</td>';
				echo '<td>'.Form::input('box_port',Arr::get($info_gate,'box_port','--'), array('type'=>'number', 'size'=>'5', 'min'=>'1', 'max'=>'65535')).'</td>';
				echo '<td>'.Form::input('id_cam',Arr::get($info_gate,'id_cam','--')).'</td>';
				echo '<td>'.Form::input('id_dev',Arr::get($info_gate,'id_dev','--')).'</td>';
				//echo '<td>'.Form::input('mode',Arr::get($info_gate,'mode','--'), array('type'=>'number', 'size'=>'1', 'min'=>'0', 'max'=>'3')).'</td>';
				echo '<td>'.Form::select('mode',
					array('0'=>'Шлюз 0','1'=>'Ворота 1','2'=>'Шлагбаум 2','3'=>'Ворота+шлагбаум 3'),
					Arr::get($info_gate,'mode',0),
					array('type'=>'number', 'size'=>'1', 'min'=>'0', 'max'=>'3')).' '.Arr::get($info_gate,'mode','--').'</td>';
				//echo Form::select('id_dev', $door_list);
			echo '</tr>';	
		?>
		</tbody>
	</table>	
			<?php
		echo Form::button('todo', __('update'), array('value'=>'update','class'=>'btn btn-success', 'type' => 'submit'));	
		?>
	</div>
</div>






<?php }?>




<?echo Form::close();?>	