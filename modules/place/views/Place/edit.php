<? //http://itchief.ru/lessons/bootstrap-3/30-bootstrap-3-tables;
 //echo Debug::vars('11', $rp_info); 
// страница для редактирования сущности
//echo Debug::vars('4');exit;
echo Debug::vars('5', $place);//exit;
echo Form::open('Place/control');

if(Auth::Instance()->logged_in())
{?>
<script type="text/javascript">
     
  	$(function() {		
  		$("#tablesorter").tablesorter({sortList:[[0,0]], headers: { 0:{sorter: false}}});
  	});	
	
</script>
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo __('Конфигурация машиноместа').' '. iconv('windows-1251','UTF-8',$place->name);
		echo Form::hidden('id', $place->id);
		
		
		
		?></h3>
	</div>
	<div class="panel-body">
		
		<?php 
		echo __('Номер машиноместа').Form::input('placenumber', $place->placenumber).' '.__('(ID').$place->id.')<br>';
		//echo __('Номер машиноместа: ').$place->placenumber.'<br>';
		//echo __('Название машиноместа').Form::input('name', iconv('windows-1251','UTF-8', $place->name), array('maxlength'=>50)).'<br>';
		//echo __('Статус').Form::input('status', iconv('windows-1251','UTF-8', $place->status), array('maxlength'=>50)).'<br>';
		$parking=new Parking($place->id_parking);
		//получить список паркингов.
		
		//вывести список паркингов
		
		// сохранить изменения. Однако может быть коллизия, если номер машиноместа уже используется.
		//вывод: переносить машиноместа из паркинга в паркинг нельзя!!!
		//список парковочных площадок для выбора
		$parkingPlace=Model::factory('ParkingPlace')->get_list_for_select();//получил список id жилых комплексов
		//echo Debug::vars('42', $parkingPlace);exit;
		$selectList=array();
		echo 'Парковочная площадка: '.Form::select('id_parking', $parkingPlace, $place->id_parking).' '.__('(ID').$place->id_parking.')';
		echo '<br>';
		echo __('Название').Form::input('name', iconv('windows-1251','UTF-8', $place->name), array('maxlength'=>50));
		echo '<br>';
		echo __('Описание').Form::input('description', iconv('windows-1251','UTF-8', $place->description), array('maxlength'=>50)).'<br>';
		echo __('Описание').Form::input('note', iconv('windows-1251','UTF-8', $place->note), array('maxlength'=>50)).'<br>';
		
		?>
			<?php
		echo Form::button('todo', Kohana::message('rubic','rubic_change_config'), array('value'=>'update','class'=>'btn btn-success', 'type' => 'submit'));	
		?>
	</div>
</div>

<?php }?>





<?echo Form::close();?>	