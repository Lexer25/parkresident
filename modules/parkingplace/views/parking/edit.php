<? //http://itchief.ru/lessons/bootstrap-3/30-bootstrap-3-tables;
 //echo Debug::vars('11', $rp_info); 
// страница для редактирования сущности
//echo Debug::vars('4');exit;
//echo Debug::vars('5', $parking);exit;
echo Form::open('ParkingPlace/control');

if(Auth::Instance()->logged_in())
{?>
<script type="text/javascript">
     
  	$(function() {		
  		$("#tablesorter").tablesorter({sortList:[[0,0]], headers: { 0:{sorter: false}}});
  	});	
	
</script>
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo __('Конфигурация парковочной площадки').' '. iconv('windows-1251','UTF-8',$parking->name);
		echo Form::hidden('id', $parking->id);
		
		
		
		?></h3>
	</div>
	<div class="panel-body">
		
		<?php 
	
		echo __('Название').' '.Form::input('name', iconv('windows-1251','UTF-8', $parking->name), array('maxlength'=>50)).'<br>';
		echo __('Активен ').Form::checkbox( 'is_active', 1, $parking->is_active == 1).'<br>';
		echo __('ID'). ' '. $parking->id.'<br>';
		echo __('parent'). ' '. $parking->parent.'<br>';
		echo __('parent').' '.Form::input('parent', $parking->parent).'<br>';
		echo __('Дата создания'). ' '. $parking->created.'<br>';
		echo __('Количество мест'). ' '.Form::input('count',  $parking->count).'<br>';
		
		?>
			<?php
		echo Form::button('todo', Kohana::message('rubic','rubic_change_config'), array('value'=>'update','class'=>'btn btn-success', 'type' => 'submit'));	
		?>
	</div>
</div>

<?php }?>





<?echo Form::close();?>	