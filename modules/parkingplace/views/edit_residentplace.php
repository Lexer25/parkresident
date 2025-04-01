<? //http://itchief.ru/lessons/bootstrap-3/30-bootstrap-3-tables;
 //echo Debug::vars('11', $rp_info); 
// страница отображения данных по парковочной системе
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
		<h3 class="panel-title"><?php echo __('Конфигурация жилого комплекса').' '. iconv('windows-1251','UTF-8',Arr::get($rp_info, 'NAME'));
		echo Form::hidden('id_rp', Arr::get($rp_info,'ID'));
		
		
		?></h3>
	</div>
	<div class="panel-body">
		
		<?php 
		//echo Debug::vars('11', $rp_info, Arr::get($rp_info, 'ENABLED'));
		//echo Form::hidden('id_rubic', Arr::get($rp_info, 'ID'));
		echo Kohana::message('rubic','rubic_name').Form::input('name', iconv('windows-1251','UTF-8', Arr::get($rp_info, 'NAME')), array('maxlength'=>50)).'<br>';
		echo __('Количество машиномест').Form::input('maxcount', Arr::get($rp_info, 'MAXCOUNT')).'<br>';
		echo Kohana::message('rubic','id_rubic'). ' '. Arr::get($rp_info, 'ID').'<br>';
		
		?>
			<?php
		echo Form::button('todo', Kohana::message('rubic','rubic_change_config'), array('value'=>'update_rp','class'=>'btn btn-success', 'type' => 'submit'));	
		?>
	</div>
</div>

<?php }?>





<?echo Form::close();?>	