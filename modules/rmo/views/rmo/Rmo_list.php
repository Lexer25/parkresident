<?php
//echo Debug::vars('2', $list_grz); //exit;
//echo Debug::vars('3', $_SESSION);
?>
<div class="panel panel-primary"> 

  <div class="panel-heading">
    <h3 class="panel-title"><?echo __('Выбор ГРЗ для управления')?></h3>
  </div>
  <div class="panel-body">
	
<?// Раздел ввода данных
	//echo Debug::vars('12', isset($parking_list));

if(isset($list_grz))
{
?>
<table id="tablesorter" class="table table-striped table-hover table-condensed tablesorter">

	<thead allign="center">
		<tr>
			<th><?echo __('Название гаража')?></th>
			<th><?echo __('Кол-во квартир (название)');?></th>
			
			
		</tr>

		<tr>
			<th>1</th>
			<th>2</th>
			
			
		</tr>

		
		</thead>
		<tbody>
		<?php 
	foreach($list_grz as $key=>$value)
	{		
		echo '<tr>';
				//ГРЗ
				echo '<td>'.HTML::anchor('/rmo/index/'.Arr::get($value, 'ID_GARAGE'),Arr::get($value,'ID_CARD')).'</td>'; 
				echo '<td>'.Arr::get($value, 'ID_CARD').'</td>'; 
				echo '<td>'.Arr::get($value, 'ID_GARAGE').'</td>'; 
				
				
			echo '</tr>';
	}?>
		</tbody>
	</table>



<?php
	}
?>


	
	

</div>	
</div>
	
  

