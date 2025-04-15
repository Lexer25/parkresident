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
		//echo __('Номер машиноместа').Form::input('placenumber', $place->placenumber).'<br>';
		echo __('Номер машиноместа: ').$place->placenumber.'<br>';
		//echo __('Название машиноместа').Form::input('name', iconv('windows-1251','UTF-8', $place->name), array('maxlength'=>50)).'<br>';
		//echo __('Статус').Form::input('status', iconv('windows-1251','UTF-8', $place->status), array('maxlength'=>50)).'<br>';
		$parking=new Parking($place->id_parking);
		//получить список паркингов.
		
		//вывести список паркингов
		
		// сохранить изменения. Однако может быть коллизия, если номер машиноместа уже используется.
		//вывод: переносить машиноместа из паркинга в паркинг нельзя!!!
		echo Debug::vars('34');
		//echo __('ID паркинга ').$place->id_parking.'<br>';
		echo __('Паркинг ').iconv('windows-1251','UTF-8', $parking->name).'<br>';
		//echo Form::hidden('id_parking', $place->id_parking);
		//echo Form::hidden('placenumber', $place->placenumber);
		echo Form::hidden('placenumber', $place->id);
		echo __('Описание').Form::input('description', iconv('windows-1251','UTF-8', $place->description), array('maxlength'=>50)).'<br>';
		//echo __('Описание2').Form::input('note', iconv('windows-1251','UTF-8', $place->note), array('maxlength'=>50)).'<br>';
			
		echo __('Дата создания'). ' '. $place->created.'<br>';
		
		?>
			<?php
		echo Form::button('todo', Kohana::message('rubic','rubic_change_config'), array('value'=>'update','class'=>'btn btn-success', 'type' => 'submit'));	
		?>
	</div>
</div>

<?php }?>





<?echo Form::close();?>	