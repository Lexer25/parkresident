<?php
// 4.10.2025
//блок выводит информацию о машиноместе при поиске этого машиноместа
//


?>
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo __('Результат поиска :count гаражей', array(':count'=>count($garageLst)));?></h3>
	</div>
	<div class="panel-body">
	
		<table id="tablesorter" class="table table-striped table-hover table-condensed tablesorter">

			<thead allign="center">
				<tr>
					<th><?php echo __('№ п/п');?></th>
					<th><?php echo __('Название гаража')?></th>
					<th><?php echo __('Подсчет')?></th>
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
				$i=0;
				$checked='no';
				if($garageLst) {
					foreach($garageLst as $key=>$value)
					{
						
						echo '<tr>';
							
							echo '<td>'.++$i.'</td>';//номер по порядку
							echo '<td>'
								. HTML::anchor('/garage/edit_garage/'.Arr::get($value, 'id_garage'), iconv('windows-1251','UTF-8', Arr::get($value, 'name')))
								.'<br>(id='.Arr::get($value, 'id_garage').')'
								.'</td>';//название гаража
							echo '<td>'; //считать или не считать
								if(Arr::get($value, 'not_count')!=0)
								{
									//echo HTML::image("images/nophoto.png", array('height' => 100, 'alt' => 'photo'));
									echo HTML::image("images/no_count.png", array('height' => 30, 'alt' => 'photo')).'<br>';
									echo __('Нет подсчета свободных мест.');
								
								} else {
									echo HTML::image("images/yes_count.png", array('height' => 30, 'alt' => 'photo')).'<br>';
									echo __('Есть подсчета свободных мест.');
									
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
								/* foreach(Arr::get($value, 'grzList') as $key2=>$value2)
								{
									//echo Debug::vars($key,$value); exit;
									echo HTML::anchor('grz/history/'.Arr::get($value2, 'GRZ'), Arr::get($value2, 'GRZ')).' '. iconv('windows-1251','UTF-8', Arr::get($value2, 'NAME')).'<br>';
								}; */
								//echo Form::open('Place/sendOpen');
								foreach(Arr::get($value, 'grzList') as $_key=>$_value)
								{
									 if (Arr::get($_value, 'ACTIVE') >0)
									 {
										echo Form::button('opendoor', Arr::get($_value, 'GRZ').' ('.iconv('windows-1251','UTF-8', Arr::get($_value, 'NAME')).')', 
										array(
											'value'=>Arr::get($_value, 'GRZ'), 
											'class'=>'btn btn-success btn-xs', 
											'type' => 'submit', 
											))
										.'<br><br>';
									 } else
									 {
										echo Form::button('opendoor', Arr::get($_value, 'GRZ').' ('. iconv('windows-1251','UTF-8', Arr::get($_value, 'NAME')).')', 
											array(
												'value'=>Arr::get($_value, 'GRZ'),
												'disabled'=>'disabled',
												'class'=>'btn btn-danger btn-xs', 'type' => 'submit'));
										echo ' '.__('Не активен').'<br>';
										 
									 }
								}
								//echo Form::close();
								
								/* echo Form::open('rmo/opengate_unknow');
								//echo Form::hidden('id_garage', Arr::get($garage_info, 'ID'));
								//разрешить въезд неизвестным ГРЗ
								
								
								echo Form::input('unknow_plate_for_insert', '1', array( 'type'=>'text', 'maxlength'=>'10'));
								echo ' ';
								echo Form::button('todo', 'Вставка неизвестного ГРЗ для проезда', array('value'=>'insert_unknow_plate','class'=>'btn btn-primary', 'type' => 'submit'));
								echo '<br>(не более 10 символов)';
								echo Form::close();	 */
					
						 
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