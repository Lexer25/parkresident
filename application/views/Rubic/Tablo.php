<?php
//echo Debug::vars('2', $configTablo);
echo Form::open('rubic/rubic_control');
?>
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title"><?echo __('rubic_tablo_IP')?></h3>
	</div>
	<div class="panel-body">
		<?php
			
		echo __('rubic_tablo_IP');
		echo Form::input('tablo', Arr::get(Arr::get($configTablo,0), 'STRVALUE')).' '.__('rubic_tablo_IP_format').'<br>';
	
			echo Form::button('todo', Kohana::message('rubic','rubic_edit'), array('value'=>'edit_tablo','class'=>'btn btn-success', 'type' => 'submit'));	
				?>
</div>
</div>
	
  
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title"><?echo __('rubic_tablo_mess')?></h3>
	</div>
	<div class="panel-body">
		<?php
			echo Kohana::message('rubic','rubic_list_desc');
		//echo Debug::vars('12', Arr::get($configTablo,1));
		$data = Arr::get(Arr::get($configTablo,1), 'STRVALUE');
		
		
		$data2=explode("\r\n",$data); //строка разобрана на массивы
		
		
		echo 'Заголовок табло '.Form::input('header_text', iconv('windows-1251','UTF-8',substr(Arr::get($data2, 1), stripos (Arr::get($data2, 1), '=')+1)), array('maxlength'=>20)).'<br>';
		echo 'Заголовок смещение по горизонтали '.Form::input('header_x', Model::factory('stat')->getParam(Arr::get($data2, 2))).Arr::get($data2, 2).'<br>';
		echo 'Заголовок смещение по вертикали '.Form::input('header_y', Model::factory('stat')->getParam(Arr::get($data2, 3))).Arr::get($data2, 3).'<br>';
		echo 'Заголовок цвет'.Form::input('header_color', Model::factory('stat')->getParam(Arr::get($data2, 4))).Arr::get($data2, 4).'<br>';
		echo 'Заголовок фонт'.Form::input('header_font', Model::factory('stat')->getParam(Arr::get($data2, 5))).Arr::get($data2, 5).'<br><br>';
		
		echo 'Нижняя строка табло '.Form::input('footer_text', iconv('windows-1251','UTF-8',Model::factory('stat')->getParam(Arr::get($data2, 7))),array('maxlength'=>20)).'<br>';
		echo 'Нижняя строка смещение по горизонтали '.Form::input('footer_x', Model::factory('stat')->getParam(Arr::get($data2, 8))).Arr::get($data2, 8).'<br>';
		echo 'Нижняя строка смещение по вертикали '.Form::input('footer_y', Model::factory('stat')->getParam(Arr::get($data2, 9))).Arr::get($data2, 9).'<br>';
		echo 'Нижняя строка цвет'.Form::input('footer_color', Model::factory('stat')->getParam(Arr::get($data2, 10))).Arr::get($data2, 10).'<br>';
		echo 'Нижняя строка фонт'.Form::input('footer_font', Model::factory('stat')->getParam(Arr::get($data2, 11))).Arr::get($data2, 11).'<br><br>';
		
		echo 'Счетчики смещение названия парковки по горизонтали '.Form::input('count_shift_1', Model::factory('stat')->getParam(Arr::get($data2, 13))).Arr::get($data2, 13).'<br>';
		echo 'Счетчики смещение количетсва свободных мест по горизонтали '.Form::input('count_shift_2', Model::factory('stat')->getParam(Arr::get($data2, 14))).Arr::get($data2, 14).'<br>';
		echo 'Счетчики высота верхнего счетчика '.Form::input('count_shift_top', Model::factory('stat')->getParam(Arr::get($data2, 15))).Arr::get($data2, 15).'<br>';
		echo 'Счетчики высота букв '.Form::input('count_font_heigh', Model::factory('stat')->getParam(Arr::get($data2, 16))).Arr::get($data2, 16).'<br>';
		echo 'Счетчики строка цвет '.Form::input('count_color', Model::factory('stat')->getParam(Arr::get($data2, 17))).Arr::get($data2, 17).'<br>';
		echo 'Счетчики строка фонт '.Form::input('count_font', Model::factory('stat')->getParam(Arr::get($data2, 18))).Arr::get($data2, 18).'<br><br>';

		echo Form::button('todo', Kohana::message('rubic','rubic_change_config'), array('value'=>'tconf_save','class'=>'btn btn-success', 'type' => 'submit'));	
			?>
</div>
</div>

	
	
  <?php
	
  echo Form::close();
  ?>	

