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
		<?php
		echo 'Список зарегистрированных гаражей.';
		include Kohana::find_file('views', 'garage/block/garageListBlock2');//выводит таблицу состояния гаражей на основе переменной $garageLst
	
		
		?>
		

 
  

