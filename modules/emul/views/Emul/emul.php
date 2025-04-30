<? //http://itchief.ru/lessons/bootstrap-3/30-bootstrap-3-tables;
 //echo Debug::vars('11', $rp_info); 
// страница эмуляции парковочной системы.
//тут можно:
//1. эмулировать отправку и прием пакета от CVS
//echo Debug::vars('4');exit;
//echo Debug::vars('5', $place);//exit;

echo Form::open('Place/control');

if(Auth::Instance()->logged_in() OR true)
{?>
<script type="text/javascript">
     
  	$(function() {		
  		$("#tablesorter").tablesorter({sortList:[[0,0]], headers: { 0:{sorter: false}}});
  	});	
	
</script>
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo __('Эмулятор работы парковочной системы');?></h3>
	</div>
	<div class="panel-body">
		<?php
		
		
		echo 'Прием сообщения от CVS о получении ГРЗ.';
		echo HTML::anchor('emul/sendGRZ', 'Send from GRZ');
		echo '<br>';
		echo 'Прием сообщения от МПТ о получении UHF.';
		echo HTML::anchor('emul/sendUHF', 'Send from UHF');
		
		?>
		
	</div>
</div>

<?php }?>





<?echo Form::close();?>	