<? //http://itchief.ru/lessons/bootstrap-3/30-bootstrap-3-tables;
// страница отображения данных по парковочной системе
//echo Debug::vars('3', $rubic_list);
echo Form::open('ResidentPlace/rp_control');
?>
			
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title"><?echo Kohana::message('rubic','rp_list')?></h3>
	</div>
	<div class="panel-body">

<table class="table table-striped table-hover table-condensed">


		<tr>
			<th><?echo Kohana::message('rubic','select');?></th>
			<th><?echo Kohana::message('rubic','rp_id');?></th>
			<th><?echo Kohana::message('rubic','rp_name');?></th>
			<th><?echo __('Количество машиномест');?></th>
			
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
				if($i==0) echo '<td>'.Form::radio('id_rp', Arr::get($value,'ID'), FALSE, array('checked'=>$checked)).'</td>';
				if($i>0) echo '<td>'.Form::radio('id_rp', Arr::get($value,'ID'), FALSE).'</td>';
				echo '<td>'.Arr::get($value,'ID').'</td>';
				echo '<td>'. HTML::anchor('parking?id_parking='.Arr::get($value,'ID', 0) , iconv('windows-1251','UTF-8', Arr::get($value,'NAME'))).'</td>';
				echo Form::hidden('del_rp_name', Arr::get($value,'NAME'));
				echo '<td>'.Arr::get($value,'MAXCOUNT').'</td>';
			echo '</tr>';	
			
		}
		
		?>

	</table>		
			
		
		<?php if(Auth::Instance()->logged_in())
		{
			echo Form::button('todo', Kohana::message('rubic','rp_edit'), array('value'=>'edit_rp','class'=>'btn btn-success  btn-xs', 'type' => 'submit'));	
			echo Form::button('todo', Kohana::message('rubic','rp_del'), array('value'=>'del_rp','class'=>'btn btn-danger  btn-xs', 'type' => 'submit', 'onclick'=>'return confirm(\''.__('delete').'?\') ? true : false;'));
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
		echo Form::button('todo', Kohana::message('rubic','rubic_add','rubic_add'), array('value'=>'add_rp','class'=>'btn btn-success', 'type' => 'submit'));	
		
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
  