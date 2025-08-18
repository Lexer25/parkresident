 <style>
       
       .input-container {
            position: relative;
            margin-bottom: 40px;
        }
        .error {
            border: 1px solid red;
        }
        .tooltip {
            position: absolute;
            background-color: #ff4444;
            color: white;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 12px;
            top: -35px;
            left: 0;
            white-space: nowrap;
            opacity: 0;
            transition: opacity 0.3s;
            pointer-events: none;
        }
        .tooltip.active {
            opacity: 1;
        }
        .tooltip:after {
            content: "";
            position: absolute;
            top: 100%;
            left: 10px;
            border-width: 5px;
            border-style: solid;
            border-color: #ff4444 transparent transparent transparent;
        }
    </style>
<script>
function confirmSubmit() {
  return confirm("Необходимо подтверждение операции удаления гаража.");
}
</script>
<script>
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
		echo Form::open('garage/control');
		echo __('Регстрация нового гаража');
		echo Form::input('name', 'Новый гараж 5');
		echo Form::hidden('not_count', 0);
		echo Form::hidden('div_code', '');
		echo Form::button('todo', 'Зарегистрировать новый гараж', array('value'=>'add_new_garage','class'=>'btn btn-success', 'type' => 'submit'));	
		echo Form::close();
		?>	

	  </div>

</div>
	<div class="panel panel-primary">
		  <div class="panel-heading">
			<h3 class="panel-title"><?php echo __('garage_titleAddPlaceArray');?></h3>
		  </div>
		  <div class="panel-body">
			<div id="my-alert" class="alert alert-success alert-dismissible" role="alert">
					<?php 
						echo 'Будет добавлено указанное количество гаражей.<br>Уже существующие гаражи изменены НЕ будут!';
					?>
					
					
			</div>
			<?
			echo __('Регистрация гаражей').'<br>';
			echo Form::open('garage/control', array('id'=>"myForm"));
			?>
			<table>
				<tr>
					<th>Префикс</th>
					<th>С какого номера</th>
					<th>По какой номер</th>
				</tr>
				<tr>
					<td>
						<?php 
							echo Form::input('prefix', 'Новый гараж', array('maxlength  '=>200));
							echo Form::hidden('not_count', 0);
							echo Form::hidden('div_code', '');
						?></td>
					<td>
						<div class="input-container">
						<?php echo Form::input('number1','', array('placeholder'=>'С какого номера','minlength '=>1,'maxlength  '=>5, 'required'=>'required', 'type'=>'number', 'id'=>'number1', 'name'=>'number1' ));?>
						<div id="tooltip1" class="tooltip"></div>
						</div>
					</td>
					<td>
						<div class="input-container">
						<?php echo Form::input('number2', '', array('placeholder'=>'По какой номер','minlength '=>1,'maxlength  '=>5, 'required'=>'required', 'type'=>'number', 'id'=>'number2', 'name'=>'number2'));?>
						<div id="tooltip2" class="tooltip">Должно быть больше первого числа</div>
						</div>
					</td>
				</tr>
				</table>
		<?php
				
		
			
			
			echo '<br>';
			echo Form::button('todo', 'Зарегистрировать новые гаражи', array('value'=>'addarray','class'=>'btn btn-success', 'type' => 'submit'));	
			
			echo Form::close();
			
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
			<th><?php echo __('Удалить');?></th>
			
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
		
		?>
		</tbody>
	</table>		
		
		<nav class="navbar navbar-default navbar-fixed-bottom disable" role="navigation">
  <div class="container">

	
	</div>
</nav>
		
</div>
</div>
 <script>
        document.addEventListener('DOMContentLoaded', function() {
            const number1 = document.getElementById('number1');
            const number2 = document.getElementById('number2');
            const tooltip1 = document.getElementById('tooltip1');
            const tooltip2 = document.getElementById('tooltip2');
            const form = document.getElementById('myForm');
            
            function showTooltip(tooltip) {
                tooltip.classList.add('active');
            }
            
            function hideTooltip(tooltip) {
                tooltip.classList.remove('active');
            }
            
            function validateNumbers() {
                const value1 = parseFloat(number1.value);
                const value2 = parseFloat(number2.value);
                
                if (isNaN(value1) || isNaN(value2)) {
                    hideTooltip(tooltip1);
                    hideTooltip(tooltip2);
                    number1.classList.remove('error');
                    number2.classList.remove('error');
                    return true;
                }
                
                if (value1 >= value2) {
                    showTooltip(tooltip1);
                    showTooltip(tooltip2);
                    number1.classList.add('error');
                    number2.classList.add('error');
                    return false;
                } else {
                    hideTooltip(tooltip1);
                    hideTooltip(tooltip2);
                    number1.classList.remove('error');
                    number2.classList.remove('error');
                    return true;
                }
            }
            
            // Проверка при изменении значений
            number1.addEventListener('input', validateNumbers);
            number2.addEventListener('input', validateNumbers);
            
            // Показываем tooltip при фокусе, если есть ошибка
            number1.addEventListener('focus', function() {
                if (number1.classList.contains('error')) {
                    showTooltip(tooltip1);
                }
            });
            
            number2.addEventListener('focus', function() {
                if (number2.classList.contains('error')) {
                    showTooltip(tooltip2);
                }
            });
            
            // Проверка при отправке формы
            form.addEventListener('submit', function(e) {
                if (!validateNumbers()) {
                    e.preventDefault();
                }
            });
        });
    </script>
	
  

