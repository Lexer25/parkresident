<? //http://itchief.ru/lessons/bootstrap-3/30-bootstrap-3-tables;
 //echo Debug::vars('11', $rp_info); 
// страница эмуляции парковочной системы.
//тут можно:
//1. эмулировать отправку и прием пакета от CVS
//echo Debug::vars('4');exit;
//echo Debug::vars('5', $place);//exit;



if(Auth::Instance()->logged_in() OR true)
{?>
<script type="text/javascript">
     
  	$(function() {		
  		$("#tablesorter1").tablesorter({sortList:[[0,0]], headers: { 0:{sorter: false}}});
  		$("#tablesorter2").tablesorter({sortList:[[0,0]], headers: { 0:{sorter: false}}});
  	});	
	
</script>
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo __('Эмулятор работы парковочной системы');?></h3>
	</div>
	<div class="panel-body">
	<?php echo Form::open('emul/sendGRZ'); ?>
		<table id="tablesorter1" class="table table-striped table-hover table-condensed tablesorter">
			<tr>
				<th>ГРЗ</th>
				<th>Видеокамера</th>
				<th>Отправить команду</th>
			</tr>
			<tr>
				<td>
					<?php
					//список ГРЗ
					$list=Model::Factory('emul')->getListIdCard(4);//список ГРЗ
					
					foreach(array_slice($list, 0, 20) as $key=>$value)
					{
						echo Form::radio('grz', Arr::get($value, 'ID_CARD')).Arr::get($value, 'ID_CARD').'<br>';
					}
					?>
				</td>
				<td>
					<?php
					//Теперь нужен список видеокамер
					
					$list=Model::Factory('emul')->getListIdCam();//список cam
					foreach(array_slice($list, 0, 20) as $key=>$value)
					{
						echo Form::radio('cam', Arr::get($value, 'ID_CAM')).Arr::get($value, 'ID_CAM').'<br>';
					}
					?>
				</td>
				<td>
					<?php
					//echo 'Прием сообщения от CVS о получении ГРЗ.';
					//echo HTML::anchor('emul/sendGRZ', 'Send from GRZ');
					echo Form::button('sendGRZ', 'Send from GRZ');
					?>
				</td>
				
			</tr>
		</table>
		<?php
		echo Form::close();
		echo Form::open('emul/sendUHF');
		?>
		
		<table id="tablesorter2" class="table table-striped table-hover table-condensed tablesorter">
			<tr>
				<th>UHF</th>
				<th>channel</th>
				<th>Отправить команду</th>
			</tr>
			<tr>
				<td>
					<?php
					//список UHF
					$list=Model::Factory('emul')->getListIdCard(1);//список UHF
					
					foreach(array_slice($list, 0, 20) as $key=>$value)
					{
						echo Form::radio('uhf', Arr::get($value, 'ID_CARD')).Arr::get($value, 'ID_CARD').'<br>';
					}
					?>
				</td>
				<td>
					<?php
					//Теперь нужен список видеокамер
					
					$list=Model::Factory('emul')->getListIdGate();//список gate
					foreach(array_slice($list, 0, 20) as $key=>$value)
					{
						echo Form::radio('gate', Arr::get($value, 'ID')).Arr::get($value, 'ID').'<br>';
					}
					?>
				</td>
				<td>
					<?php
					//echo 'Прием сообщения от CVS о получении ГРЗ.';
					//echo HTML::anchor('emul/sendGRZ', 'Send from GRZ');
					echo Form::button('sendUHF', 'Send from UHF');
					?>
				</td>
				
			</tr>
		</table>
		
		
		<?php
		
		
		
		
		?>
		
	</div>
</div>

<?php }?>





<?echo Form::close();?>	