<?php
//это блок, выводящий матрицу машиномест для гаража
//заранее должны быть подготовлены переменные
//$parking,
//$place_list,
//echo Debug::vars('2', $place_list);//exit;

?>
<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title"><?php echo __('Список машиномест парковочной площадки :name. v2', array(':name'=>iconv('windows-1251','UTF-8', $parking->name)));?></h3>
				</div>
				<div class="panel-body">
			
			
		<?php
		if(count($place_list))
			{ ?>
			
			<table class="table table-striped table-hover table-condensed">
				<tbody>
				<?php 
								
				$i=0;
				$checked='no';
				$column=10;// количество колонок в таблице
				$row= ceil(count($place_list)/$column);
				$aaa=array_chunk($place_list, $column);
				//echo Debug::vars('120', $aaa); //exit;
				
				for ($i=0; $i<$row; $i++)
				{
					echo '<tr>';
						foreach(Arr::get($aaa, $i) as $key=>$value)
						{
							
							if(array_key_exists(Arr::get($value, 'ID'), $place_busy) and !array_key_exists(Arr::get($value, 'ID'), $place_income_garage)) // если это место уже занято, то запретить редактирование
							{
							//echo '<td>'.Form::checkbox('id_place['.Arr::get($value, 'ID').']', Arr::get($value, 'ID'), TRUE, array("disabled"=>"disabled")).'мм №'.Arr::get($value, 'PLACENUMBER').'<br>'.iconv('windows-1251','UTF-8', Arr::get($value, 'NAME')).'</td>';
							echo '<td>'.Form::checkbox('id_place['.Arr::get($value, 'ID').']', Arr::get($value, 'ID'), TRUE, array("disabled"=>"disabled"))
							.HTML::anchor('place/edit/'.Arr::get($value, 'ID'), iconv('windows-1251','UTF-8', Arr::get($value, 'NAME'))).'</td>';
							} else {
							//echo '<td>'.Form::checkbox('id_place['.Arr::get($value, 'ID').']', Arr::get($value, 'ID'), (array_key_exists(Arr::get($value, 'ID'), $place_income_garage))? true : false, array()).'мм №'.Arr::get($value, 'PLACENUMBER').'<br>'.iconv('windows-1251','UTF-8', Arr::get($value, 'NAME')).'</td>';
							echo '<td>'.Form::checkbox('id_place['.Arr::get($value, 'ID').']', Arr::get($value, 'ID'), (array_key_exists(Arr::get($value, 'ID'), $place_income_garage))? true : false, array())
							.HTML::anchor('place/edit/'.Arr::get($value, 'ID'),iconv('windows-1251','UTF-8', Arr::get($value, 'NAME'))).'</td>';
							}
						}
					echo '</tr>';	
					
				}
				
				?>
				</tbody>
			</table>
			<?php 
			
			} else {
				echo __('no_date_for_view');
		
			};
			?>
			
			</div>
			</div>
			<?php				