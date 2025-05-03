<?php
//echo Debug::vars('2', $gate_list, $id_parking);
//echo Debug::vars('3', $gate_list); exit;
/*
"id" => string(2) "24"
        "id_parking" => string(1) "1"
        "name" => string(0) ""
        "is_enter" => string(1) "0"
        "tablo_ip" => string(13) "192.168.8.112"
        "tablo_port" => string(4) "1985"
        "box_ip" => string(12) "192.168.1.58"
        "box_port" => string(4) "1985"
        "id_cam" => string(1) "1"
        "id_dev" => string(3) "100"
        "mode" => string(1) "1"
		*/
echo Form::open('gate/control');
?>
<script type="text/javascript">
     
  	$(function() {		
  		$("#tablesorter").tablesorter({sortList:[[0,0]], headers: { 0:{sorter: false}}});
  	});	
	
</script> 

	<div class="panel panel-primary">
		<div class="panel-heading">
			<h3 class="panel-title"><?echo __('Список ворот');?></h3>
		</div>
		<div class="panel-body">

			<table id="tablesorter" class="table table-striped table-hover table-condensed tablesorter">

			<thead>
				<tr>
					<th><?php echo 'Выбор.';?></th>
					<th><?php echo 'ID';?></th>
					<th><?php echo 'Ворота';?></th>
					<th><?php echo 'Направление';?></th>
					<th><?php echo 'id_parking';?></th>
					<th><?php echo 'IP табло';?></th>
					<th><?php echo 'PORT табло';?></th>
					<th><?php echo 'IP контроллера';?></th>
					<th><?php echo 'PORT контроллера';?></th>
					<th><?php echo 'channel контроллера';?></th>
					<th><?php echo 'ID видеокамеры';?></th>
					<th><?php echo 'ID точки прохода';?></th>
					<!--<th><?php echo 'Режим работы';?></th>-->
					

				</tr>
			</thead>
			<tbody>
				<?php
				$i=0;
				$checked=1;
				
				if($gate_list)
				{
					foreach($gate_list as $key=>$value)
					{
						$gate = new Gate(Arr::get($value,'ID'));
						//echo Debug::vars('63',$key, $value,  $gate);exit;
						echo '<tr>';
							if($i==0) echo '<td>'.Form::radio('id', $gate->id, FALSE, array('checked'=>$checked)).'</td>';
							if($i>0) echo '<td>'.Form::radio('id', $gate->id, FALSE).'</td>';
							echo '<td>'.$gate->id.'</td>';
							echo '<td>'. iconv('windows-1251','UTF-8', $gate->name).'</td>';
							echo '<td>'.Form::select('is_enter',
								array('0'=>'Выезд 0', '1'=>'Въезд 1'),
								$gate->is_enter, 
								array('disabled'=>'disabled'))
								.' '.$gate->is_enter.'</td>';
							$parkingInfo=new Parking($gate->id_parking);
							//echo '<td>'.$gate->id_parking.'</td>';
							echo '<td>'.iconv('windows-1251','UTF-8',$parkingInfo->name).'</td>';
							echo '<td>'.$gate->tablo_ip.'</td>';
							echo '<td>'.$gate->tablo_port.'</td>';
							echo '<td>'.$gate->box_ip.'</td>';
							echo '<td>'.$gate->box_port.'</td>';
							echo '<td>'.$gate->channel.'</td>';
							echo '<td>'.$gate->id_cam.'</td>';
							echo '<td>'.$gate->dev_name.' ('.$gate->id_dev.')</td>';
							/* echo '<td>'.Form::select('mode',
								array('0'=>'Шлюз 0','1'=>'Ворота 1','2'=>'Шлагбаум 2','3'=>'Ворота+шлагбаум 3'),
								$gate->mode,
								array('type'=>'number', 'size'=>'1', 'min'=>'0', 'max'=>'3', 'disabled'=>'disabled')).' '.$gate->mode.'</td>';
							
						echo '</tr>';	 */
						$i++;
					
					}
				}
				?>
				
						
			</tbody>
		</table>		
			
		
		<?php if(Auth::Instance()->logged_in())
		{
			echo Form::button('todo', __('gate_edit'), array('value'=>'edit','class'=>'btn btn-success', 'type' => 'submit'));	
			echo Form::button('todo', __('gate_del'), array('value'=>'del','class'=>'btn btn-danger', 'type' => 'submit', 'onclick'=>'return confirm(\''.__('delete').'?\') ? true : false;'));
			echo Form::close();
			echo Form::open('gate/control');
		
		?>
		
		</div>
	</div>




		<div class="panel panel-primary">
			  <div class="panel-heading">
				<h3 class="panel-title"><?php echo __('Добавление ворот');?></h3>
			  </div>
			  <div class="panel-body">



				<?
				echo __('Регстрация новых ворот');
				echo Form::input('name', 'Новые ворота');
				echo Form::button('todo', __('reg_gate'), array('value'=>'add','class'=>'btn btn-success', 'type' => 'submit'));	
			echo Form::close();
			echo Form::open('gate/control');	
				?>	

			  </div>

		</div>
			

			<div class="panel panel-primary">
				  <div class="panel-heading">
					<h3 class="panel-title"><?php echo __('Управление подсчетом свободных мест');?></h3>
				  </div>
				  <div class="panel-body">
						<?
						//echo Form::hidden('id_parking', Arr::get($value,'id_parking'));
						//if($checkplaceenable>0)
						if(true)
						{
						echo ' <span class="label label-success">'.__('Счетчики свободных мест включены').'</span><br><br>';
						//echo Form::button('todo', __('Контроль свободных включен'), array('value'=>'check_count_on','class'=>'btn btn-success', 'type' => 'submit'));	
						echo Form::button('todo', __('Контроль свободных мест выключить!'), array('value'=>'check_count_off','class'=>'btn btn-danger', 'type' => 'submit'));	
						} else {
							
						echo ' <span class="label label-danger">'.__('Счетчики свободных мест вЫключены').'</span><br><br>';
						echo Form::button('todo', __('Контроль свободных включить!'), array('value'=>'check_count_on','class'=>'btn btn-success', 'type' => 'submit'));	
						//echo Form::button('todo', __('Контроль свободных мест выключить!'), array('value'=>'check_count_off','class'=>'btn btn-danger', 'type' => 'submit'));	
						}
						?>	
				  </div>
			</div>
			
			
			
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h3 class="panel-title"><?echo __('Надписи на табло при выводе ГРЗ');?></h3>
			</div>
			<div class="panel-body">
			
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title"><?echo __('Верхняя строка табло');?></h3>
					</div>
					В верхней строке табло выводится ГРЗ проезжающего автомобиля.
					<?php //echo Debug::vars('164', $tabloMessages); 
					
					?>
					<div class="panel-body">
				
					<table id="tablesorter2" class="table table-striped table-hover table-condensed tablesorter">

						<thead allign="center">
							<tr>
								<th><?php echo 'Смещение X';?></th>
								<th><?php echo 'Смещение Y';?></th>
							</tr>
						</thead>
						<tbody>
							<?php
							echo '<tr>';
								//echo '<td>'. Form::input('line_0_deltaX', Arr::get($value,'line_0_deltaX', 0), array('size'=>2)).'</td>';
								//echo '<td>'. Form::input('line_0_deltaY', Arr::get($value,'line_0_deltaY', 0), array('size'=>2)).'</td>';
							echo '</tr>';	
							?>
						</tbody>
						</table>
					</div>
				</div>

				
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title"><?echo __('Нижняя строка табло');?></h3>
					</div>
					<div class="panel-body">
						

				В нижзней строке табло выводится приглашение к проезду или пояснения к причине отказа.
				<?php
					$tmess=Model::factory('gates')->tabloMessages();
					//echo Debug::vars('206', $tmess);//exit;
				
				?>
				

			<table id="tablesorter3" class="table table-striped table-hover table-condensed tablesorter">

			<thead allign="center">
				<tr>
					<th><?php echo 'Код события.';?></th>
					<th><?php echo 'Название события в СКУД.';?></th>
					<th><?php echo 'Тест на табло';?></th>
					<th><?php echo 'Смещение X';?></th>
					<th><?php echo 'Смещение Y';?></th>
					<th><?php echo 'Цвет текста';?></th>
					<th><?php echo 'Бегущая строка';?></th>
				</tr>
				</thead>
				<tbody>
				<?php
				$styleList=array(
					'000000',
					'0000ff', 
					'00ff00', 
					'00ffff', 
					'ff0000', 
					'ff00ff', 
					'ffff00', 
					'ffffff', 
					);
				
				$i=0;
				$checked=1;
				if($tmess)
				{
					foreach($tmess as $key=>$value)
					{
						//echo Debug::vars('243', Arr::get($value,'PARAM'));exit;
						$param=json_decode(Arr::get($value,'PARAM'), true);
						echo '<tr>';
							echo '<td>'. Arr::get($value,'EVENTCODE');'</td>';
							echo '<td>'. iconv('windows-1251','UTF-8',Arr::get($value,'EVENTNAME')).'</td>';
							echo '<td>'. Form::input('new_tablo_text['.Arr::get($value,'EVENTCODE').']', iconv('windows-1251','UTF-8',Arr::get($value,'EVENTMESSAGE')), array('size'=>50, 'maxlength'=>50)).'</td>';
							echo '<td>'. Form::input('dx['.Arr::get($value,'EVENTCODE').']', Arr::get($param,'dx', 0), array('type'=>'number', 'size'=>'2', 'min'=>'0', 'max'=>'64')).'</td>';
							echo '<td>'. Form::input('dy['.Arr::get($value,'EVENTCODE').']', Arr::get($param,'dy', 0), array('type'=>'number', 'size'=>'2', 'min'=>'0', 'max'=>'16')).'</td>';
							echo '<td><select name=messColor['.Arr::get($value,'EVENTCODE').'] size=1 style="background: #'.Arr::get($styleList, Arr::get($param,'messColor')).'; color: #FFF;" onChange="this.style.backgroundColor=this.options[this.selectedIndex].style.backgroundColor"> ';
								foreach ($styleList as $key2=>$value2)
								{
									if($key2==Arr::get($param,'messColor'))
									{
										echo '<option style="background-color:#'.$value2.'" selected="selected" value="'.$key2.'">'.$key2.'</option> ';
									} 
									else 
									{
										echo '<option style="background-color:#'.$value2.'" value="'.$key2.'">'.$key2.'</option> ';
										
									}
								}
								 
								echo '</select>'.Arr::get($param,'messColor').
							'</td>';
							
							echo '<td>'.Form::select('messScroll['.Arr::get($value,'EVENTCODE').']',array('0'=>'Нет','1'=>'Да',),
								Arr::get($param,'messScroll',0),
								array('type'=>'number', 'size'=>'1', 'min'=>'0', 'max'=>'3')).Arr::get($param,'messScroll').'</td>';
						echo '</tr>';	
						$i++;
					
					}
				}
				?>

					
			</tbody>
		</table>	

<style>
	.c0{
        background-color: #000000;
    }    
   .c1{
        background-color: #0000ff;
    }    
    .c2{
        background-color: #00ff00;
    }    
    .c3{
        background-color: #00ffff;
    }
	.c4{
        background-color: #ff0000;
    }
	.c5{
        background-color: #ff00ff;
    }
	.c6{
        background-color: #ffff00;
    }
	.c7{
        background-color: #ffffff;
    }
	
	
</style>



Легенда нумерации цвета<br>
	<table border="1">
			<tr align="center">
				<th>0</th>
				<th>1</th>
				<th>2</th>
				<th>3</th>
				<th>4</th>
				<th>5</th>
				<th>6</th>
				<th>7</th>
			</tr>
			<tr>
				<td><button type="button" class="btn c0 active">0</button></td>
				<td><button type="button" class="btn c1 active">1</button></td>
				<td><button type="button" class="btn c2 active">2</button></td>
				<td><button type="button" class="btn c3 active">3</button></td>
				<td><button type="button" class="btn c4 active">4</button></td>
				<td><button type="button" class="btn c5 active">5</button></td>
				<td><button type="button" class="btn c6 active">6</button></td>
				<td><button type="button" class="btn c7 active">7</button></td>
			</tr>
			</table>
<hr>			






<hr>	
			
		
<?php echo Form::button('todo', __('Сохранить'), array('value'=>'set_tablo_text','class'=>'btn btn-success', 'type' => 'submit'));?>

					
		</div>
	</div>
			

			
			
			
			
		<?php } else {
			//echo Form::button('todo', Kohana::message('rubic','to_view'), array('value'=>'edit_rubic','class'=>'btn btn-success', 'type' => 'submit'));	
		}?>


<?php echo Form::close();?>
	
  

