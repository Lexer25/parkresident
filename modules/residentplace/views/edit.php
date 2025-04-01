<? //http://itchief.ru/lessons/bootstrap-3/30-bootstrap-3-tables;
 //echo Debug::vars('11', $rp_info); 
// страница для редактирования сущности
//echo Debug::vars('4');exit;
//echo Debug::vars('5', $residence);exit;
echo Form::open('ResidentPlace/rp_control');

if(Auth::Instance()->logged_in())
{?>
<script type="text/javascript">
     
  	$(function() {		
  		$("#tablesorter").tablesorter({sortList:[[0,0]], headers: { 0:{sorter: false}}});
  	});	
	
</script>
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo __('Конфигурация жилого комплекса').' '. iconv('windows-1251','UTF-8',$residence->name);
		echo Form::hidden('id_rp', $residence->id);
		
		
		
		?></h3>
	</div>
	<div class="panel-body">
		
		<?php 
		//echo Debug::vars('11', $rp_info, Arr::get($rp_info, 'ENABLED'));
		//echo Form::hidden('id_rubic', Arr::get($rp_info, 'ID'));
		//echo Debug::vars('28', $residence);//exit;
		echo Kohana::message('rubic','rubic_name').Form::input('name', iconv('windows-1251','UTF-8', $residence->name), array('maxlength'=>50)).'<br>';
		echo __('Активен ').Form::checkbox( 'is_active', 1, $residence->is_active == 1).'<br>';
		echo __('ID ЖК'). ' '. $residence->id.'<br>';
		echo __('Дата создания'). ' '. $residence->created.'<br>';
		
		?>
			<?php
		echo Form::button('todo', Kohana::message('rubic','rubic_change_config'), array('value'=>'update_rp','class'=>'btn btn-success', 'type' => 'submit'));	
		?>
	</div>
</div>

<?php }?>





<?echo Form::close();?>	