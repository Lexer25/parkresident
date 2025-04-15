<? //http://itchief.ru/lessons/bootstrap-3/30-bootstrap-3-tables;
// страница отображения данных по парковочной системе
//echo Debug::vars('3', $rubic_list);
//echo Debug::vars('3', $id_resident);
echo Form::open('ResidentPlace/control');
?>
			
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title"><?echo Kohana::message('rubic','rp_list')?></h3>
	</div>
	<div class="panel-body">
123
<table class="table table-striped table-hover table-condensed">


		<tr>
			<th><?echo Kohana::message('rubic','select');?></th>
			<th><?echo Kohana::message('rubic','rp_id');?></th>
			<th><?echo Kohana::message('rubic','rp_name');?></th>
			<!--<th><?echo __('is_active');?></th>
			<th><?echo __('created');?></th>
			<th><?echo __('modify');?></th>-->
			<th><?echo __('Количество парковочных<br>площадок');?></th>
			<th><?echo __('Общее количество машиномест<br>в жилом комплексе');?></th>
			
		</tr>
		<?php 
		$i=0;
		$checked='no';
		$total_place=0;
		$total_occup=0;
		$total_vacant=0;
		
			$dis1='';
			$dis1_arr='';
			$dis2='';
			$dis2_lighten='';
			$dis3='';
							
							
		foreach($id_resident as $key=>$value)
		{
			
			$residence=new Residence(Arr::get($value, 'ID'));
			echo '<tr>';
				if($i==0) echo '<td>'.Form::radio('id_rp', Arr::get($value,'ID'), FALSE, array('checked'=>$checked)).'</td>';
				if($i>0) echo '<td>'.Form::radio('id_rp', Arr::get($value,'ID'), FALSE).'</td>';
				echo '<td>'.$residence->id.'</td>';
				echo '<td>'. HTML::anchor('parkingPlace?id_resident='.Arr::get($value,'ID', 0) , iconv('windows-1251','UTF-8', $residence->name)).'</td>';
				
				$parkinPlace=Model::factory('ParkingPlace')->get_list_for_parent($residence->id);//список парковок в этом жилом комплексе
				//echo Debug::vars('56', $parkinPlace);//exit;
				$placeCount=0;
				foreach($parkinPlace as $key2=>$value2)
				{
					//echo Debug::vars('59', $value);//exit;
					$parloin=new Parking(Arr::get($value2, 'ID'));
					$placeCount=$placeCount + $parloin->count;
					
				}
				//echo Debug::vars('64', $placeCount);//exit;
				echo '<td>'. HTML::anchor('parkingPlace?id_resident='.Arr::get($value,'ID', 0) , count($parkinPlace)).'</td>';
				//подсчет количества мест в каждой парковке
				
				echo '<td>'. $placeCount.'</td>';
				
			echo '</tr>';	
			
		}
		
		?>

	</table>		
			
		
		<?php if(Auth::Instance()->logged_in())
		{
			echo Form::button('todo', Kohana::message('rubic','rp_edit'), array('value'=>'edit','class'=>'btn btn-success  btn-xs', 'type' => 'submit'));	
			echo Form::button('todo', Kohana::message('rubic','rp_del'), array('value'=>'del','class'=>'btn btn-danger  btn-xs', 'type' => 'submit', 'onclick'=>'return confirm(\''.__('delete').'?\') ? true : false;'));
		} else {
			echo Form::button('todo', Kohana::message('rubic','to_view'), array('value'=>'edit_rubic','class'=>'btn btn-success', 'type' => 'submit'));	
		}?>
		
		
</div>
</div>



<?php if(Auth::Instance()->logged_in())
		{?>
	<div class="panel panel-primary">
	  <div class="panel-heading">
		<h3 class="panel-title"><?echo Kohana::message('rubic','rp_add_rubic','rp_add_rubic')?></h3>
	  </div>
	  <div class="panel-body">
	  
		<?
		echo Kohana::message('rubic','rp_add_rubic');
		echo Form::input('add_rp_name', 'Новый жилой комплекс');
		echo Form::button('todo', Kohana::message('rubic','rubic_add','rubic_add'), array('value'=>'add','class'=>'btn btn-success', 'type' => 'submit'));	
		
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
  