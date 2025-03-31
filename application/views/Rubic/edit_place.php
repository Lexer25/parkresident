<? //http://itchief.ru/lessons/bootstrap-3/30-bootstrap-3-tables;
// страница отображения данных о парковочном месте
//echo Debug::vars('3',$id_place, $info_place, $list_parking, $list_garage); 
//echo Debug::vars('3',$id_place, $info_place); 
//echo Debug::vars('3',$_SESSION); 
echo Form::open('rubic/rubic_control');
?>



<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo __('Реадктирование машиноместа placenumber', array('placenumber'=>$id_place))?></h3>
	</div>
	<div class="panel-body">
		<?php echo Kohana::message('rubic','Kp_panel_inside2');?>

			<table class="table table-striped table-hover table-condensed">


		<tr>
			<!--<th><?php echo __('ID');?></th>
			<th><?php echo __('select');?></th>-->
			<th><?php echo __('Название машиноместа');?></th>
			<th><?php echo __('Описание машиноместа');?></th>
			<th><?php echo __('Комментарий');?></th>
			<th><?php echo __('Парковка');?></th>
			<th><?php echo __('garage');?></th>
			
		</tr>
		<?php 
		$i=0;
		$checked='no';
		//echo Debug::vars('33', $info_card, $list_parking); 
		foreach($info_place as $key=>$value)
		{
			echo '<tr>';
				//echo '<td>'.Arr::get($value,'ID').'</td>';
				/* if($i==0) echo '<td>'.Form::radio('id_place', Arr::get($value,'ID'), FALSE, array('checked'=>$checked)).'</td>';
				if($i>0) echo '<td>'.Form::radio('id_place', Arr::get($value,'ID'), FALSE).'</td>'; */
				echo Form::hidden('id_place', Arr::get($value,'ID'));
				echo '<td>'.Form::input('name',iconv('windows-1251','UTF-8',Arr::get($value,'NAME'))).'</td>';
				echo '<td>'.Form::input('description', iconv('windows-1251','UTF-8',Arr::get($value,'DESCRIPTION'))).' </td>';
				
				echo '<td>'.Form::input('note', iconv('windows-1251','UTF-8',Arr::get($value, 'NOTE')), array('maxlength'=>50)).'</td>';
				//echo '<td>'.iconv('windows-1251','UTF-8', Arr::get($value,'PARKINGNAME')).Form::select('id_parking', $list_parking, Arr::get($value,'ID_PARKING')).					
				echo '<td>'.Form::select('id_parking', $list_parking, Arr::get($value,'ID_PARKING')).'</td>';
				//echo '<td>'.Form::select('id_garagename', $list_garage, Arr::get($value,'ID_GARAGE', 0)).'</td>';
				if(!Arr::get($value,'ID_GARAGENAME', false))
				{
					echo '<td>---</td>';
					
				} else {
					
					
					echo '<td>'. HTML::anchor('/garage/edit_garage/'.Arr::get($value,'ID_GARAGENAME', 0), Arr::get($list_garage, Arr::get($value,'ID_GARAGENAME'))).'</td>';
				}		
			echo '</tr>';	
			$i++;
			
		}
		
		?>
		
	
	</table>
		
		<?php
		echo Form::hidden('status', Arr::get($value,'STATUS', 0));
		echo Form::button('todo', Kohana::message('rubic','rubic_change_config','Сохранить изменения'), array('value'=>'save_place','class'=>'btn btn-success', 'type' => 'submit'));
		
		?>
	</div> 
</div>

<?php echo Form::close();?>	