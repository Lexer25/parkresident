<?php
//echo Debug::vars('2', $tableList); //exit;
//echo Debug::vars('3', $tableListCheck); //exit;
//echo Debug::vars('4', $procedureList); //exit;
//echo Debug::vars('5', $procedureListCheck); //exit;
echo Form::open('Checkdb/worker');
?>
<script type="text/javascript">
     
  	$(function() {		
  		$("#tablesorter_ge").tablesorter({sortList:[[0,0]], headers: {}});
  	});	
</script>			

<div class="panel panel-primary">
	
	<div class="panel-body">

	Таблицы
		<table id="tablesorter_ge" class="table table-striped table-hover table-condensed tablesorter">
		<thead allign="center">
			<tr>
				<th><?echo __('№ п/п');?></th>
				<th><?echo __('Код события');?></th>
				<th><?echo __('КПП.');?></th>
				<th><?echo __('Время события');?></th>
			</tr>
		</thead>
		<tbody>
		<?php 
		$i=0;
		foreach($tableList as $key=>$value)
		{
		echo '<tr>';
				echo '<td>'.++$i.'</td>';
				
				echo '<td>'.$value.'</td>';
				echo '<td>';
				echo Arr::get($tableListCheck, $value)? HTML::image('static/images/green-check.png', array('alt' => 'true')) : 'false';
				
				//echo Debug::vars(Arr::get($tableListCheck, $value));
				echo '</td>';
				echo '<td>'.Form::button('addTable', 'Добавить таблицу', array('value'=>$value)).'</td>';
				echo '<td>'.Form::button('delTable', 'Удалить таблицу', array('value'=>$value)).'</td>';
			echo '</tr>';	
		}	
		?>
		</tbody>
	</table>	

<?php
	echo '<td>'.Form::button('addAllTable', 'Добавить все таблицы', array('value'=>$value)).'</td>';
	echo '<td>'.Form::button('delAllTable', 'Удалить все таблицы', array('value'=>$value)).'</td>';

?>	
		Процедуры
		<table id="tablesorter_ge" class="table table-striped table-hover table-condensed tablesorter">
		<thead allign="center">
			<tr>
				<th><?echo __('№ п/п');?></th>
				<th><?echo __('Код события');?></th>
				<th><?echo __('КПП.');?></th>
				<th><?echo __('Время события');?></th>
			</tr>
		</thead>
		<tbody>
		<?php 
		$i=0;
		foreach($procedureList as $key=>$value)
		{
		echo '<tr>';
				echo '<td>'.++$i.'</td>';
				
				echo '<td>'.$value.'</td>';

				echo '<td>';
				echo Arr::get($procedureListCheck, $value)? HTML::image('static/images/green-check.png', array('alt' => 'true')) : 'false';
				echo '</td>';
				echo '<td>'.Form::button('addProcedure', 'Добавить процедуру', array('value'=>$value)).'</td>';
				echo '<td>'.Form::button('delProcedure', 'Удалить процедуру', array('value'=>$value)).'</td>';
				
			echo '</tr>';	
		}	
		?>
		</tbody>
	</table>		
		
		
	<?php

echo Form::close();
?>	
		
</div>
</div>
