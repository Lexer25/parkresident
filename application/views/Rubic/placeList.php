<?php
//echo Debug::vars('2', $place_list); //exit;
//echo Debug::vars('3',$_SESSION); 
echo Form::open('rubic/rubic_control');
?>
<script type="text/javascript">
     
  	$(function() {		
  		$("#tablesorter").tablesorter({sortList:[[0,0]], headers: { 0:{sorter: false}}});
  	});	
	
</script> 
<?php if(Auth::Instance()->logged_in())
{
	?>
	<div class="panel panel-primary">
		  <div class="panel-heading">
			<h3 class="panel-title"><?php echo __('Регистрация парковочного места');?></h3>
		  </div>
		  <div class="panel-body">
			<div id="my-alert" class="alert alert-success alert-dismissible" role="alert">
					<?php 
						//echo 'Номер парковочного места вводится как длинное десятичное число: 000123456789.<br>Остальные варианты ввода будут игнорироваться.';
					?>
					
					
			</div>
			<?
			echo __('Регистрация парковочного места').'<br>';
			echo Form::input('new_place_number','', array('placeholder'=>'Номер машиноместа')).'<br>';
			//echo Form::input('new_place_name', 'Название машиноместа').'<br>';
			echo Form::button('todo', 'Зарегистрировать новое машиноместо', array('value'=>'add_new_place','class'=>'btn btn-success', 'type' => 'submit'));	
			
			?>	

		  </div>

	</div>
<?php
}
?>

<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title"><?echo __('Список машиномест. Зарегистрировано count машиномест.', array('count'=>count($place_list)))?></h3>
	</div>
	<div class="panel-body">
		<?php
			echo __('Список машиномест');
			//echo Debug::vars('123', $card_list);
		?>
<table id="tablesorter" class="table table-striped table-hover table-condensed tablesorter">

	<thead allign="center">
		<tr>
			
			<th><?echo __('Номер п/п');?></th>
			<th><?echo __('Номер машиноместа');?></th>
			<th><?echo __('Название машиноместа');?></th>
			<th><?echo __('Комментарий машиноместа');?></th>
			<th><?echo 'Прим.';?></th>
			<th><?echo 'Название парковки';?></th>
			<th><?echo 'Гараж';?></th>
		</tr>

		</thead>
		<tbody>
		<?php 
		$i=0;
		$checked='no';
		foreach($place_list as $key=>$value)
		{
			echo '<tr>';
				//echo '<td>'.($i+1).Debug::vars('68', $key, $value).'</td>';
				echo '<td>'.($i+1).'</td>';
				/* if($i==0) echo '<td>'.Form::radio('id_place', Arr::get($value,'ID'), FALSE, array('checked'=>$checked)).'</td>';
				if($i>0) echo '<td>'.Form::radio('id_place', Arr::get($value,'ID'), FALSE).'</td>'; */
		if(Auth::Instance()->logged_in())
		{				
						echo '<td>'.HTML::anchor('rubic/edit_place/'.Arr::get($value,'PLACENUMBER'),
									Arr::get($value,'PLACENUMBER'))
									.'</td>';
		} else 
		{
						echo '<td>'.Arr::get($value,'PLACENUMBER').'</td>';
			
}
				echo '<td>'.iconv('windows-1251','UTF-8', Arr::get($value,'PLACE_NAME')). '</td>';
				echo '<td>'.iconv('windows-1251','UTF-8',Arr::get($value,'DESCRIPTION')).'</td>';
				echo '<td>'.iconv('windows-1251','UTF-8',Arr::get($value,'NOTE')).'</td>';
				echo '<td>'.iconv('windows-1251','UTF-8', Arr::get($value,'PARKING_NAME')).'</td>';
				echo '<td>'.HTML::anchor('garage/edit_garage/'.Arr::get($value,'ID_GARAGE'),  iconv('windows-1251','UTF-8', Arr::get($value,'GARAGE_NAME'))).' </td>';
				
			echo '</tr>';	

			$i++;
			
		}
		
		?>
		</tbody>
	</table>		
			
		
<?php if(Auth::Instance()->logged_in())
{
?>
		
		<nav class="navbar navbar-default navbar-fixed-bottom disable" role="navigation">
		  <div class="container">

				<?php
					echo Form::button('todo', __('place_edit'), array('value'=>'edit_place_rubic','class'=>'btn btn-success', 'type' => 'submit'));	
					echo Form::button('todo', __('place_del'), array('disabled'=>'disabled','value'=>'del_place_rubic','class'=>'btn btn-danger', 'type' => 'submit', 'onclick'=>'return confirm(\''.__('delete').'?\') ? true : false;'));
				?>
			
			</div>
		</nav>
<?php 
}
?>	
</div>
</div>

<?echo Form::close();?>
	
  

