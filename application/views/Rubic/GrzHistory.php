<? //http://itchief.ru/lessons/bootstrap-3/30-bootstrap-3-tables;
// 4.05.2023 страница отображает историю ГРЗ
//echo Debug::vars('3', $getGrzInfo);
//echo Debug::vars('4', $grzHistory);
//exit;
echo Form::open('grz/grz_control1');
?>
<script type="text/javascript">
     
  	$(function() {		
  		$("#tablesorter").tablesorter({sortList:[[0,0]], headers: { 0:{sorter: false}}});
  	});	
	
</script>
			
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo __('Информация по ГРЗ <b>grz</b>', array('grz'=>$grz));?></h3>
	</div>
	<div class="panel-body">
	<table width="100%">
		<tr>
			<td>
	<?php 
	if($getGrzInfo!==NULL)
	{

			echo __('ГРЗ: grz', array('grz'=> Arr::get($getGrzInfo, 'ID_CARD', 'Нет'))).'<br>';
			echo __('Дата регистрация ГРЗ: TIMESTART', array('TIMESTART'=> Arr::get($getGrzInfo, 'TIMESTART', 'Нет'))).'<br>';
			echo __('Модель транспортного средства:  GRZ_MODEL', array('GRZ_MODEL'=> iconv('windows-1251','UTF-8',Arr::get($getGrzInfo, 'GRZ_MODEL', 'No')))).'<br>';

			echo '<h4>'.__('Категории доступа:').'</h4>';
			
				echo '<ul>';
				foreach (Arr::get($getGrzInfo, 'accessList') as $key=>$value)
				{
					echo '<li>'.iconv('windows-1251','UTF-8',Arr::get($value, 'NAME')).'</li>';
				}
				echo '</ul>';
			
			
			
			echo '<h4>'.__('Гаражи:').'</h4>';
			if(Arr::get($getGrzInfo, 'garageList') !==NULL)
			{
				if(count(Arr::get($getGrzInfo, 'garageList')))
				{
					echo '<ul>';
					foreach (Arr::get($getGrzInfo, 'garageList') as $key=>$value)
					{
						echo '<li>'.HTML::anchor('rmo/index/'. Arr::get($value, 'ID_GARAGE'), iconv('windows-1251','UTF-8',Arr::get($value, 'NAME'))).'</li>';
					}
					echo '</ul>';
				} else 
				{
					echo __('Нет гаража.');
					
				}
			} 
				

			echo '<h4>'.__('Состояние:').'</h4>';
			if(Arr::get($getGrzInfo, 'inParking') !==NULL)
			{
				if(count(Arr::get($getGrzInfo, 'inParking')))
				{
					foreach (Arr::get($getGrzInfo, 'inParking') as $key=>$value)
					{
						echo '<li>'.iconv('windows-1251','UTF-8',Arr::get($value, 'NAME')).' '. Arr::get($value, 'ENTERTIME').'</li>';
					}
				} else 
				{
					echo __('Не на парковке.');
				}
				
			} 
			
			
	} else {
		
		echo __('ГРЗ <b>grz</b> не зарегистрирован в парковочной системе', array('grz'=>$grz));
	}		
		?>
		</td>
		<td allign="center">
		<?php 
			echo '<br>'.HTML::image('grzPhoto\\'.$grz.'.jpg', array('height' => 200, 'alt' => 'photo'));
		?>
		</td>
		</tr>
		</table>
			
	
	</div>
</div>

<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo __('История проездов ГРЗ <b>grz</b>', array('grz'=>$grz));?></h3>
	</div>
	<div class="panel-body">

<table id="tablesorter" class="table table-striped table-hover table-condensed tablesorter">

	<thead allign="center">
		<tr>
			<th><?echo __('pp');?></th>
			<th><?echo __('grz');?></th>
			<th><?echo __('Событие');?></th>
			<th><?echo __('Парковка');?></th>
			<th><?echo __('Въезд');?></th>
			<th><?echo __('Выезд');?></th>
			
			
		</tr>
		
		</thead>
		<tbody>
		<?php 
		$i=1;
		$checked='no';
		$total_place=0;
		$total_occup=0;
		$total_vacant=0;
			
			
		foreach($grzHistory as $key=>$value)
		{
			switch (Arr::get($value,'EVENT_CODE'))
			{
				case 50:
					$ecolor='success';
				break;
				case 81:
					$ecolor='warning';
				break;
				case 46:
				case 65:
					$ecolor='danger';
				break;
				default:
					$ecolor='info';
				
			}
			echo '<tr class="'.$ecolor.'">';
				echo '<td>'.$i++.'</td>';
				
				echo '<td>'.Arr::get($value,'GRZ').'</td>';
				echo '<td>'.iconv('windows-1251','UTF-8',Arr::get($value,'EVENTNAME')).' ('.Arr::get($value,'EVENT_CODE').')</td>';
				echo '<td>'.iconv('windows-1251','UTF-8', Arr::get($value,'GATENAME')).'</td>';
				echo '<td>'.(Arr::get($value,'IS_ENTER')? Arr::get($value,'EVENT_TIME'):'').'</td>';
				echo '<td>'.(Arr::get($value,'IS_ENTER')? '':Arr::get($value,'EVENT_TIME')).'</td>';
				//echo '<td>'.HTML::image("images/no_count.png", array('height' => 30, 'alt' => 'photo')).'</td>';
				

				
				
			echo '</tr>';	
			
		}
		
		?>
		</tbody>
	</table>		
	
		
</div>
</div>







<?echo Form::close();?>
