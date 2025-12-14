<? //http://itchief.ru/lessons/bootstrap-3/30-bootstrap-3-tables;
 //echo Debug::vars('11', $rp_info); 
// страница для редактирования сущности
//echo Debug::vars('4');exit;
//echo Debug::vars('5', $place);//exit;

?>
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo __('Конфигурация машиноместа').' '. iconv('windows-1251','UTF-8',$place->name);
		echo Form::hidden('id', $place->id);

		?></h3>
	</div>
	<div class="panel-body">
		
		<?php 
			
			$parking=new Parking($place->id_parking);
			$parkingPlace=Model::factory('ParkingPlace')->get_list_for_select();//получил список id жилых комплексов
		

		if(Auth::Instance()->logged_in())
		{
			echo Form::open('Place/control');
			echo Form::hidden('placenumber', $place->placenumber).'<br>';
			echo __('(ID').$place->id.')<br>';
		
			
			$selectList=array();
			echo 'Парковочная площадка: '.Form::select('id_parking', $parkingPlace, $place->id_parking).' '.__('(ID').$place->id_parking.')';
			echo '<br>';
			echo __('Название').Form::input('name', iconv('windows-1251','UTF-8', $place->name), array('maxlength'=>50));
			echo '<br>';
			echo __('Описание').Form::input('description', iconv('windows-1251','UTF-8', $place->description), array('maxlength'=>50)).'<br>';
			echo __('Описание2').Form::input('note', iconv('windows-1251','UTF-8', $place->note), array('maxlength'=>50)).'<br>';
			
			?>
				<?php
			echo Form::button('todo', Kohana::message('rubic','rubic_change_config'), array('value'=>'update','class'=>'btn btn-success', 'type' => 'submit'));	
		} else {
		echo 'Парковочная площадка: '. Arr::get($parkingPlace, $place->id_parking);
			echo '<br>';
			echo __('Название :name', array('name'=>$place->name));
			echo '<br>';
			echo __('Описание :name', array('name'=>iconv('windows-1251','UTF-8', $place->description)));
			echo '<br>';
			echo __('Описание2 :name', array('name'=>iconv('windows-1251','UTF-8', $place->note)));
			echo '<br>';
			echo __('Авторизуйтесь для редактирования.');
		}
		?>
		
		
	</div>
</div>







<?echo Form::close();?>	