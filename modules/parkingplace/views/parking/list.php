<? //http://itchief.ru/lessons/bootstrap-3/30-bootstrap-3-tables;
// страница отображения данных по парковочной системе
//echo Debug::vars('3', $rubic_list);
//echo Debug::vars('3', $id_parkingPlace);

echo Form::open('ParkingPlace/control');
?>
			
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title"><?echo __('Список парковочных площадок')?></h3>
	</div>
	<div class="panel-body">

<table class="table table-striped table-hover table-condensed">


		<tr>
			<th><?echo Kohana::message('rubic','select');?></th>
			<th><?echo __('ID парковочной площадки');?></th>
			<th><?echo __('Название парковочной площадки');?></th>
			<th><?echo __('is_active');?></th>
			<th><?echo __('created');?></th>
			<th><?echo __('modify');?></th>
			<th><?echo __('parent');?></th>
			<th><?echo __('Количество машиномест');?></th>
			
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
							
							
		foreach($id_parkingPlace as $key=>$value)
		{
			
			$parkingPlace=new Parking(Arr::get($value, 'ID'));
			echo '<tr>';
				if($i==0) echo '<td>'.Debug::vars('53', $parkingPlace).' '.Form::radio('id', Arr::get($value,'ID'), FALSE, array('checked'=>$checked)).'</td>';
				if($i>0) echo '<td>'.Form::radio('id', Arr::get($value,'ID'), FALSE).'</td>';
				echo '<td>'.$parkingPlace->id.'</td>';
				//echo '<td>'. HTML::anchor('parking?id_resident='.Arr::get($value,'ID', 0) , iconv('windows-1251','UTF-8', $residence->name)).'</td>';
				echo '<td>'. iconv('windows-1251','UTF-8', $parkingPlace->name).'</td>';
				echo '<td>'.$parkingPlace->is_active.'</td>';
				echo '<td>'.$parkingPlace->created.'</td>';
				echo '<td>'.$parkingPlace->modify.'</td>';
				echo '<td>'.$parkingPlace->parent.'</td>';
				echo '<td>'.$parkingPlace->count.'</td>';
				
				
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
		<h3 class="panel-title"><?echo __('Добавить парковочную площадку')?></h3>
	  </div>
	  <div class="panel-body">
	  
		<?
		echo __('Добавить парковочную площадку');
		echo Form::input('name', 'Новая парковочная площадка');
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
  