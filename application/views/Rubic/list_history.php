<?php
//echo Debug::vars('2', $list_history);
//echo Debug::vars('2', $card_info);
?>
<script type="text/javascript">
     
  	$(function() {		
  		$("#tablesorter").tablesorter({sortList:[[0,0]], headers: { 0:{sorter: false}}});
  	});	
	
</script> 
<div class="panel panel-primary"> 

  <div class="panel-heading">
    <h3 class="panel-title"><?echo Kohana::message('rubic','parking_list_history').' '. Arr::get(Arr::get($card_info, 0), 'CARDCODE').' '.Arr::get(Arr::get($card_info, 0), 'COMMENT')?></h3>
  </div>
  <div class="panel-body">
	
	<table id="tablesorter" class="table table-striped table-hover table-condensed tablesorter">
		<thead allign="center">
		<tr>
			<th><?echo Kohana::message('rubic','pp');?></th>
			<th><?echo Kohana::message('rubic','date');?></th>
			<th><?echo Kohana::message('rubic','parking_card');?></th>
			<th><?echo Kohana::message('rubic','event');?></th>
			<th><?echo Kohana::message('rubic','rubic_people_name');?></th>
			
		</tr>
		</thead>
		<tbody>
		<?php 
		//echo Debug::vars('126', $list_key_into_parking);
		$i=1;
		foreach($list_history as $key=>$value)
		{
			{
			echo '<tr>';
				echo '<td>'.$i++.'</td>';
				echo '<td>'.Arr::get($value,'EVENT_TIME', '--').'</td>';
				echo '<td>'.Arr::get($value,'PARK_CARD', '--').'</td>';
				echo '<td>'.(Arr::get($value,'IS_ENTER', '--')? Kohana::message('rubic','enter'):Kohana::message('rubic','exit')).'</td>';
				echo '<td>'.Arr::get($value,'ID_PEP', '--').' '
							.Arr::get($value,'SURNAME', '--').' '
							.Arr::get($value,'NAME', '--').' '
							.Arr::get($value,'PATRONYMIC', '--').'</td>';
				//echo '<td>'.Arr::get($value,'SURNAME').' '.Arr::get($value,'NAME').' '.Arr::get($value,'PATRONYMIC').' ('.Arr::get($value, 'DUOCARD') .')</td>';
					
			echo '</tr>';	
			}
		}
		?>
		</tbody>
	</table>

	
	

</div>	
</div>
	
  

