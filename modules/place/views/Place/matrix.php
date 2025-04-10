<? //http://itchief.ru/lessons/bootstrap-3/30-bootstrap-3-tables;
// страница отображения данных по машноместам
echo Debug::vars('3', $id_place);
echo Form::open('place/control');
?>
<script type="text/javascript">
     
  	$(function() {		
  		$("#tablesorter").tablesorter({sortList:[[0,0]], headers: { 0:{sorter: false}}});
  	});	
	
</script> 
<?php if(Auth::Instance()->logged_in())
{
	?>
	<div class="panel panel-primary">
		  <div class="panel-heading">
			<h3 class="panel-title"><?php echo __('Регистрация парковочного места');?></h3>
		  </div>
		  <div class="panel-body">
			<div id="my-alert" class="alert alert-success alert-dismissible" role="alert">
					<?php 
						//echo 'Номер парковочного места вводится как длинное десятичное число: 000123456789.<br>Остальные варианты ввода будут игнорироваться.';
					?>
					
					
			</div>
			<?
			echo __('Регистрация парковочного места').'<br>';
			echo Form::input('placenumber','', array('placeholder'=>'Номер машиноместа','minlength '=>1,'maxlength  '=>5, 'required'=>'required')).'<br>';
			//echo Form::input('new_place_name', 'Название машиноместа').'<br>';
			echo Form::button('todo', 'Зарегистрировать новое машиноместо', array('value'=>'add','class'=>'btn btn-success', 'type' => 'submit'));	
			
			?>	

		  </div>

	</div>
<?php
}
echo Form::close();
echo Form::open('place/control');
?>

<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title"><?echo __('Список машиномест. Зарегистрировано count машиномест.', array('count'=>count($id_place)))?></h3>
	</div>
	<div class="panel-body">
		<?php
			echo __('Список машиномест');
			//echo Debug::vars('123', $card_list);
		?>
<table id="tablesorter" class="table table-striped table-hover table-condensed tablesorter">

	<thead allign="center">
		<tr>
			
			<th><?echo __('Номер п/п');?></th>
			<th><?echo __('Выбор');?></th>
			<th><?echo __('Номер машиноместа');?></th>
			<th><?echo __('Название машиноместа');?></th>
			<th><?echo __('Комментарий машиноместа');?></th>
			<th><?echo 'Прим.';?></th>
			<th><?echo 'Название парковки';?></th>
			<th><?echo 'Гараж';?></th>
		</tr>

		</thead>
		<tbody>
		<?php 
		$i=0;
		$checked='no';
		//вывод списка машиномест для указанных 
		foreach($id_place as $key=>$value)
		{
			$place=new Place(Arr::get($value, 'ID'));
			//echo Debug::vars('68', $key, $value, $place); exit;
			
			echo '<tr>';
				
				echo '<td>';
					echo ($i+1);
					//echo ' '. Debug::vars('82', $place);
				echo '</td>';
				echo '<td>'.Form::radio( 'id', $place->id, Arr::get($value, 'is_active' == 1)).' '.$place->id.' '.$place->created.'</td>';

		if(Auth::Instance()->logged_in())
		{				
						echo '<td>'.HTML::anchor('place/edit/'.$place->id,
									$place->placenumber)
									.'</td>';
		} else 
		{
						echo '<td>'.$place->placenumber.'</td>';
			
}
				echo '<td>'.iconv('windows-1251','UTF-8', $place->name). '</td>';
				echo '<td>'.iconv('windows-1251','UTF-8',$place->description).'</td>';
				echo '<td>'.iconv('windows-1251','UTF-8',$place->note).'</td>';
				$_parking = new Parking($place->id_parking);
				echo '<td>'. iconv('windows-1251','UTF-8', $place->id_parking). iconv('windows-1251','UTF-8',$_parking->name).'</td>';
				echo '<td>'.HTML::anchor('garage/edit_garage/'.Arr::get($value,'ID_GARAGE'),  iconv('windows-1251','UTF-8', Arr::get($value,'GARAGE_NAME'))).' </td>';
				
			echo '</tr>';	

			$i++;
			
		}
		
		?>
		</tbody>
	</table>		
	
		
	

	
		
<?php if(Auth::Instance()->logged_in())
{
?>
		
		<nav class="navbar navbar-default navbar-fixed-bottom disable" role="navigation">
		  <div class="container">

				<?php
					echo Form::button('todo', __('place_edit'), array('value'=>'edit','class'=>'btn btn-success', 'type' => 'submit'));	
					//echo Form::button('todo', __('place_del'), array('disabled'=>'disabled','value'=>'del','class'=>'btn btn-danger', 'type' => 'submit', 'onclick'=>'return confirm(\''.__('delete').'?\') ? true : false;'));
					echo Form::button('todo', __('place_del'), array('value'=>'del','class'=>'btn btn-danger', 'type' => 'submit', 'onclick'=>'return confirm(\''.__('delete').'?\') ? true : false;'));
				?>
			
			</div>
		</nav>
<?php 
}
echo Form::close();
?>	
</div>
</div>
<?php
	$_parking=Arr::get(Arr::flatten($id_place), 'ID');
	$_place=new Parking($_parking);
	echo 'Матричное представление парковочного пространства для парковки "'. iconv('windows-1251','UTF-8', $_place->name).'" ('.$_parking.')';
?>
<table class="table table-hover">
<thead>
</thead>


<tbody>
<?php
$count=225;//общее количество мест

for($y=0; $y<10; $y++)
{
	 
	echo '<tr>';
		for($x=1; $x<11;$x++)
		{
			
				//echo $x.' '.$y;
				$_place = new PlaceNP(($y*10+$x), $_parking);
				//echo Debug::vars('154', $_place);//exit;
				//echo Debug::vars($_place) .' '.$_parking;
				$_mess='--';
				switch($_place->status)
				{
					case 0:
						$_class='active';//серый
						$_mess='Не настроен';
					break;
					case 1:
						$_class='success';//зеленый
						$_mess='OK';
					break;
					case 2:
						$_class='info';//голубой
					break;
					case 3:
						$_class='warning';//желтый
						$_mess='Заблокирован';
					break;
					case 4:
						$_class='danger';//красный
					break;
					default:
						$_class='active';
					break;
				}
				
					
					echo '<td class="'.$_class.'">';					
									
					//echo HTML::anchor('place/edit/'.($y*10+$x).'/'.$_parking, 'Место '.($y*10+$x));
					echo ($y*10+$x).' '.$_mess;
					echo Form::open('place/control');
						echo Form::hidden('place', ($y*10+$x));
						echo Form::hidden('parking', $_parking);
						echo Form::submit('todo', 'editMatrix');
					echo Form::close();
					echo '</td>';
				
			
		}
	
	echo '</tr>';
}

?>
</tbody>
</table>



	
  

