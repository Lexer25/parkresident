<?php
//echo Debug::vars('2', $garageLst);//exit;

?>
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo 'Список гаражей (зарегистрировано '.count($garageLst).') v.2';?></h3>
	</div>
	<div class="panel-body">
	
		<table id="tablesorter" class="table table-striped table-hover table-condensed tablesorter">

			<thead allign="center">
				<tr>
					<th><?php echo __('№ п/п');?></th>
					<th><?php echo __('ID гаража');?></th>
					<th><?php echo __('Название гаража')?></th>
					<th><?php echo __('Прим.')?></th>
					<th><?php echo __('Кол-во квартир<br>(название)');?></th>
					<th><?php echo __('Кол-во ГРЗ<br>(ГРЗ, модель)');?></th>
					<th><?php echo __('Кол-во машиномест<br>(номера машиномест)');?></th>
					<th><?php echo __('Количество ГРЗ на территории<br>Перечень<br>Дата въезда');?></th>
					<th><?php echo __('Осталось<br>свободных<br>мест');?></th>
					<th><?php echo __('Удалить');?></th>
					
				</tr>

				
				</thead>
				<tbody>
				<?php 
				//echo Debug::vars('58', $garageLst); 
				$i=0;
				$checked='no';
				if($garageLst) {
					foreach($garageLst as $key=>$value)
					{
						
						echo '<tr>';
							
							echo '<td>'.++$i.'</td>';//номер по порядку
							echo '<td>'.Arr::get($value,'id_garage');echo '</td>';// id гаража
							
							
							echo '<td>'. HTML::anchor('/garage/edit_garage/'.Arr::get($value, 'id_garage'), iconv('windows-1251','UTF-8', Arr::get($value, 'name'))).'</td>';//название гаража
							echo '<td>'; //считать или не считать
								if(Arr::get($value, 'not_count'))
								{
									//echo HTML::image("images/nophoto.png", array('height' => 100, 'alt' => 'photo'));
									echo HTML::image("images/no_count.png", array('height' => 30, 'alt' => 'photo')).'<br>';
									echo __('Нет подсчета свободных мест.');
								
								}
								echo '</td>';
							
							
							echo '<td>';//названия квартир
							
							
								if(Arr::get($value, 'orgList')){
									echo 'Всего квартир: '.count(Arr::get($value, 'orgList')).'<br>';
									foreach(Arr::get($value, 'orgList') as $key1=>$value1)
									{
										
										echo iconv('windows-1251','UTF-8', Arr::get($value1, 'NAME')).'<br>';
									};
								} else {
									echo 'Всего квартир: 0';
								}
								echo '</td>';
								
							//вывод списка ГРЗ
							echo '<td>';
								echo __('Всего ГРЗ <b>count</b>', array('count'=>count(Arr::get($value, 'grzList')))).'<hr>';
								foreach(Arr::get($value, 'grzList') as $key2=>$value2)
								{
									//echo Debug::vars($key,$value); exit;
									echo HTML::anchor('grz/history/'.Arr::get($value2, 'GRZ'), Arr::get($value2, 'GRZ')).' '. iconv('windows-1251','UTF-8', Arr::get($value2, 'NAME')).'<br>';
								};
								echo '</td>';
								
							//вывод списка машиномест в два этажа. Каждый этаж - своя парковка
							
								echo '<td>';
								//echo Debug::vars('84', Arr::get($value, 'placeList'));
								echo __('Всего мест <b>count</b>', array('count'=>count(Arr::get($value, 'placeList')))).'<hr>';
								foreach(Arr::get($value, 'parkingList') as $_key=>$_infoParking)
								{
										foreach(Arr::get($value, 'placeList') as $key3=>$value3)
										{
											if(Arr::get($value3, 'ID_PARKING') == Arr::get($_infoParking,'ID')) echo HTML::anchor('rubic/edit_place/'.Arr::get($value3, 'PLACENUMBER'), '№ '.iconv('windows-1251','UTF-8',Arr::get($value3, 'NAME'))).' ('.Arr::get($_infoParking,'NAME').')<br>';
										};
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
							
							echo '<td>';
							//echo Debug::vars('160', $value);
								if(Auth::Instance()->logged_in()){
										echo Form::open('garage/control', array('onsubmit'=>'return confirmSubmit()'));
										echo Form::hidden('id', Arr::get($value,'id_garage'));	
										echo Form::button('todo', 'Удалить', array('value'=>'delete_garage','class'=>'btn btn-danger', 'type' => 'submit'));	
										echo Form::close();
									} else {			
										echo Form::button('todo', 'Удалить', array('value'=>'delete_garage','class'=>'btn btn-light', 'type' => 'submit', "disabled"=>"disabled"));	
									}
							echo '</td>';
						
							
						echo '</tr>';	
						
					
						
					}
				} else {
					?>
					<tr>
						<td><?php echo __('-');?></td>
						<td><?php echo __('-');?></td>
						<td><?php echo __('-')?></td>
						<td><?php echo __('-')?></td>
						<td><?php echo __('-');?></td>
						<td><?php echo __('-');?></td>
						<td><?php echo __('-');?></td>
						<td><?php echo __('-');?></td>
						<td><?php echo __('-');?></td>
						<td><?php echo __('-');?></td>
					</tr>
				<?php	
				}
				
				?>
				</tbody>
			</table>
			
		
		
		
				
		
	</div>
</div>