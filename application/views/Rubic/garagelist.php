<?php
// список гаражей
//echo Debug::vars('2', $get_stat_garage_place);
echo Form::open('garage/control');
?>
<script type="text/javascript">
     
  	$(function() {		
  		$("#tablesorter").tablesorter();
  	});	
	
</script> 
<div class="panel panel-primary">
	  <div class="panel-heading">
		<h3 class="panel-title"><?php echo __('Регистрация нового гаража');?></h3>
	  </div>
	  <div class="panel-body">
		<div id="my-alert" class="alert alert-success alert-dismissible" role="alert">
				
				
		</div>
		<?
		echo __('Регстрация нового гаража');
		echo Form::input('name', 'Новый гараж5');
		echo Form::button('todo', 'Зарегистрировать новый гараж---', array('value'=>'add_new_garage','class'=>'btn btn-success', 'type' => 'submit'));	
		
		?>	

	  </div>

</div>

<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo 'Список гаражей (зарегистрировано '.count($garageLst).')';?></h3>
	</div>
	<div class="panel-body">
		<?php
			echo 'Спиоск зарегистрированных гаражей.';
			//echo Debug::vars('123', $card_list);
		?>
		
		
<table id="tablesorter" class="table table-striped table-hover table-condensed tablesorter">

	<thead allign="center">
		<tr>
			<th><?php echo __('ID гаража');?></th>
			<th><?php echo __('Название гаража')?></th>
			<th><?php echo __('Прим.')?></th>
			<th><?php echo __('Кол-во квартир<br>(название)');?></th>
			<th><?php echo __('Кол-во ГРЗ<br>(ГРЗ, модель)');?></th>
			<th><?php echo __('Кол-во машиномест<br>(номера машиномест)');?></th>
			<th><?php echo __('Количество ГРЗ на территории<br>Перечень<br>Дата въезда');?></th>
			<th><?php echo __('Осталось<br>свободных<br>мест');?></th>
			
		</tr>


	
		
		</thead>
		<tbody>
		<?php 
		//echo Debug::vars('58', $garageLst); 
		
		$checked='no';
		foreach($garageLst as $key=>$value)
		{
			//echo Debug::vars('58',$key, $value); //exit; 
			//echo Debug::vars('71', Arr::get($value, 'parkingList')); //exit; 
			
			
			echo '<tr>';
				//номер по порядку
				echo '<td>'.Arr::get($value,'id_garage');
				//echo Debug::vars('77', $value);
					
					echo '</td>';
				
				
				echo '<td>'. HTML::anchor('/garage/edit_garage/'.Arr::get($value, 'id_garage'), Arr::get($value, 'name')).'</td>';
				echo '<td>';
					if(Arr::get($value, 'not_count'))
					{
						//echo HTML::image("images/nophoto.png", array('height' => 100, 'alt' => 'photo'));
						echo HTML::image("images/no_count.png", array('height' => 30, 'alt' => 'photo')).'<br>';
						echo __('Нет подсчета свободных мест.');
					
					}
					echo '</td>';
				
				
				echo '<td>';
				
				
					
					foreach(Arr::get($value, 'orgList') as $key1=>$value1)
					{
						//echo Debug::vars($key,$value); exit;
						//echo Arr::get($value1, 'ID'). ' '.Arr::get($value1, 'NAME').'<br>';
						echo Arr::get($value1, 'NAME').'<br>';
					};
					echo '</td>';
					
				//вывод списка ГРЗ
				echo '<td>';
					echo __('Всего ГРЗ <b>count</b>', array('count'=>count(Arr::get($value, 'grzList')))).'<hr>';
					foreach(Arr::get($value, 'grzList') as $key2=>$value2)
					{
						//echo Debug::vars($key,$value); exit;
						echo HTML::anchor('grz/history/'.Arr::get($value2, 'GRZ'), Arr::get($value2, 'GRZ')).' '. Arr::get($value2, 'NAME').'<br>';
					};
					echo '</td>';
					
				//вывод списка машиномест в два этажа. Каждый этаж - своя парковка
				
					echo '<td>';
					echo __('Всего мест <b>count</b>', array('count'=>count(Arr::get($value, 'placeList')))).'<hr>';
					foreach(Arr::get($value, 'parkingList') as $_key=>$_infoParking)
					{
							
							//echo '<button type="button" class="btn btn-default btn-sm"><b>'.Arr::get($_infoParking,'NAME').'</b><br>';
							foreach(Arr::get($value, 'placeList') as $key3=>$value3)
							{
								if(Arr::get($value3, 'ID_PARKING') == Arr::get($_infoParking,'ID')) 	echo HTML::anchor('rubic/edit_place/'.Arr::get($value3, 'PLACENUMBER'), '№ '.Arr::get($value3, 'PLACENUMBER')).' ('. Arr::get($value3, 'NAME').' '.Arr::get($_infoParking,'NAME').')<br>';
							};
						//echo '</button><br>';
						
					}	
					echo '</td>';
				
				//вывод списка ГРЗ, уже стоящих на парковке, в две колонки. Каждая колонка - своя парковка
				echo '<td>';
				echo __('Всего на территории <b>count</b>', array('count'=>count(Arr::get($value, 'grzInGarageList')))).'<hr>';
					foreach(Arr::get($value, 'parkingList') as $_key=>$_infoParking)
					{
						
					
						foreach(Arr::get($value, 'grzInGarageList') as $key4=>$value4)
						{
							//echo Debug::vars($key4,Arr::get($value4, 0), '##',$value4);
							
								if(Arr::get($value4, 'ID_PARKING') == Arr::get($_infoParking,'ID')) echo '<acronym title="'.Arr::get($value4, 'ENTER_TIME').'">'.$key4.'</acronym> ('.Arr::get($_infoParking,'NAME').')<br>';
								
							
							
						};	//echo '</button><br>';
					}

				echo '</td>';
			//количество свободных мест		
				echo '<td>';
					echo count(Arr::get($value, 'placeList')) - count(Arr::get($value, 'grzInGarageList'));
				echo '</td>';
			
				
			echo '</tr>';	
			
		
			
		}
		
		?>
		</tbody>
	</table>		
		
		<nav class="navbar navbar-default navbar-fixed-bottom disable" role="navigation">
  <div class="container">

		<?php
			//echo Form::button('todo', Kohana::message('rubic','rubic_edit'), array('value'=>'edit_garage','class'=>'btn btn-success', 'type' => 'submit'));	
			//echo Form::button('todo', Kohana::message('rubic','rubic_del'), array('value'=>'del_garage','class'=>'btn btn-danger', 'type' => 'submit', 'onclick'=>'return confirm(\''.__('delete').'?\') ? true : false;'));
		?>
	
	</div>
</nav>
		
</div>
</div>



<?echo Form::close();?>
	
  

