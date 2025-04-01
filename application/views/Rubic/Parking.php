<? //http://itchief.ru/lessons/bootstrap-3/30-bootstrap-3-tables;
// страница отображения данных по парковочным площадям
//echo Debug::vars('3', $rubic_list);//exit;
//echo Debug::vars('4', $count_busy);//exit;
//echo Debug::vars('5', $id_resident);//exit;
$residentInfo=Model::factory('ResidentPlace')->getResidentPlaceInfo($id_resident);
//echo Debug::vars('7', $residentInfo);//exit;

echo Form::open('parking/control');
?>
			
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title"><?echo Kohana::message('rubic','rubic_list'). iconv('windows-1251','UTF-8', Arr::get($residentInfo, 'NAME'));?></h3>
	</div>
	<div class="panel-body">
		<?php
			echo Kohana::message('rubic','rubic_list_desc');
			//echo Debug::vars('11', $count_busy);
		?>
<table class="table table-striped table-hover table-condensed">


		<tr>
			<th><?echo Kohana::message('rubic','select');?></th>
			<th><?echo Kohana::message('rubic','id_parking');?></th>
			<th><?echo Kohana::message('rubic','rubic_name');?></th>
			<th><?echo Kohana::message('rubic','rubic_maxcount');?></th>
			<th><?echo Kohana::message('rubic','rubic_opport');?></th>
			<th><?echo Kohana::message('rubic','rubic_vacant');?></th>
			
		</tr>
		<?php 
		$i=0;
		$checked='no';
		$total_place=0;
		$total_occup=0;
		$total_vacant=0;
		foreach($rubic_list as $key=>$value)
		{
			echo '<tr>';
				if($i==0) echo '<td>'.Form::radio('id_parking', Arr::get($value,'ID'), FALSE, array('checked'=>$checked)).'</td>';
				if($i>0) echo '<td>'.Form::radio('id_parking', Arr::get($value,'ID'), FALSE).'</td>';
				echo '<td>'.Arr::get($value,'ID').'</td>';
				echo '<td>'. HTML::anchor('gate?id_parking='.Arr::get($value,'ID', 0) , Arr::get($value,'NAME')).'</td>';
				echo '<td>'.Arr::get($value,'MAXCOUNT').'</td>';
				echo '<td>'.HTML::anchor('parking/load/'.Arr::get($value,'ID'), Arr::get($count_busy,Arr::get($value,'ID'))).'</td>';
				echo '<td>'. (Arr::get($value,'MAXCOUNT') -  Arr::get($count_busy, Arr::get($value,'ID'), 0)).'</td>';
				
			echo '</tr>';	
			$i++;
		$total_place=$total_place+Arr::get($value,'MAXCOUNT');
		$total_occup=$total_occup + Arr::get($count_busy,Arr::get($value,'ID'), 0);
		$total_vacant=$total_place-$total_occup;
		}
		
		?>
		<tr>
			<td></td>
			<td></td>
			<td>Итого:</td>
			<td><?php echo $total_place;?></td>
			<td><?php echo $total_occup;?></td>
			<td><?php echo $total_vacant;?></td>
			<td></td>
		</tr>
	</table>		
			
		
		<?php if(Auth::Instance()->logged_in())
		{
		
			echo Form::button('todo', Kohana::message('rubic','rubic_edit'), array('value'=>'edit_parking','class'=>'btn btn-success', 'type' => 'submit'));	
			echo Form::button('todo', Kohana::message('rubic','rubic_del'), array('value'=>'del_parking','class'=>'btn btn-danger', 'type' => 'submit', 'onclick'=>'return confirm(\''.__('delete').'?\') ? true : false;'));
		} else {
			echo Form::button('todo', Kohana::message('rubic','to_view'), array('value'=>'edit_rubic','class'=>'btn btn-success', 'type' => 'submit'));	
		}?>
		
		
</div>
</div>



<?php if(Auth::Instance()->logged_in())
		{?>
	<div class="panel panel-primary">
	  <div class="panel-heading">
		<h3 class="panel-title"><?echo __('rp_add_parking');?></h3>
	  </div>
	  <div class="panel-body">
	  
		<?
		echo Kohana::message('rubic','rubic_add_new_parking');
		echo Form::input('add_parking_name', 'Новая парковочная площадь');
		echo Form::hidden('id_resident', $id_resident);
		
		//echo Form::button('todo', Kohana::message('rubic','rubic_add','rubic_add'), array('value'=>'add_parking','class'=>'btn btn-success', 'type' => 'submit', 'disabled'=>'disabled'));	
		echo Form::button('todo', Kohana::message('rubic','add_rubic','rubic_add'), array('value'=>'add_parking','class'=>'btn btn-success', 'type' => 'submit'));	
		
		?>	

	  </div>

	</div>


<?php }?>






<?echo Form::close();?>
<script>
    $(function(){
        window.setTimeout(function(){
            $('#my-alert').alert('close');
        },5000);
    });
</script>
  