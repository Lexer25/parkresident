<?php
// 5.10.2025
//блок управления названиями машиномест. Пока что он нужен только для вывода кнопки Преобразовать название.
//


?>
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo 'Управления названиями машиномест';?></h3>
	</div>
	<div class="panel-body">
	
	<?php
	
	echo Debug::vars('16');//exit;
		echo Form::open('place/control');

				echo Form::input('placenumber','', array('placeholder'=>'Номер машиноместа','minlength '=>1,'maxlength  '=>5, 'required'=>'required', 'type'=>'number' )).'<br>';
				echo Form::input('new_place_name', '', array('placeholder'=>'Комментарий машиноместа','maxlength  '=>205)).'<br>';

			echo Form::button('todo', 'Преобразовать', array('value'=>'renamePlace','class'=>'btn btn-success', 'type' => 'submit'));	
			echo Form::close();
	?>
		
		
		
				
		
	</div>
</div>