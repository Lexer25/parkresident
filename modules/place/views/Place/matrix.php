<? //http://itchief.ru/lessons/bootstrap-3/30-bootstrap-3-tables;
// страница отображения данных по машноместам
//echo Debug::vars('3', $id_place);
$_parking=new Parking(Arr::get(Arr::flatten($id_place), 'ID'));//информация о парковочной площадке
$placeList=Model::factory('Place')->getChild($_parking->id);//список машиномест на этой парковочной площадке
//echo Debug::vars('5', $_parking);//exit;

$titleAddPlace=__('Регистрация машиноместа для парковочной площадки ":name". Зарегистрировано :regPlace. Количество мест на площадке :countPlace',
			array(
				':name'=>iconv('windows-1251','UTF-8',$_parking->name),
				':regPlace'=> count($placeList),
				':countPlace'=>$_parking->count
				));
				

$title=__('Список машиномест для парковочной площадки ":name". Зарегистрировано :regPlace. Количество мест на площадке :countPlace',
			array(
				':name'=>iconv('windows-1251','UTF-8',$_parking->name),
				':regPlace'=> count($placeList),
				':countPlace'=>$_parking->count
				));
				
				
?>
<script type="text/javascript">
     
  	$(function() {		
  		$("#tablesorter").tablesorter({sortList:[[0,0]], headers: { 0:{sorter: false}}});
  	});	
	
</script> 
<?php 
echo '<!--';
echo Form::open('place/control');
if(Auth::Instance()->logged_in())
{
	?>
	<div class="panel panel-primary">
		  <div class="panel-heading">
			<h3 class="panel-title"><?php echo $titleAddPlace;?></h3>
		  </div>
		  <div class="panel-body">
			<div id="my-alert" class="alert alert-success alert-dismissible" role="alert">
					<?php 
						//echo 'Номер парковочного места вводится как длинное десятичное число: 000123456789.<br>Остальные варианты ввода будут игнорироваться.';
					?>
					
					
			</div>
			<?
			echo __('Регистрация парковочного места').'<br>';
			echo Form::input('place','', array('placeholder'=>'Номер машиноместа','minlength '=>1,'maxlength  '=>5, 'required'=>'required')).'<br>';
			echo Form::hidden('parking', $_parking->id);
			echo Form::button('todo', 'Зарегистрировать новое машиноместо', array('value'=>'editMatrix','class'=>'btn btn-success', 'type' => 'submit'));	
			
			?>	

		  </div>

	</div>
<?php
}


echo Form::close();
echo '-->';
echo Form::open('place/control');
?>

<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title"><?echo $title;?></h3>
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
		//вывод списка машиномест для указанных парковочных площадок
		foreach($placeList as $key=>$value)
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
						echo '<td>'.HTML::anchor('place/edit/'.$place->id.'/'.$_parking->id,
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
				echo '<td>'. iconv('windows-1251','UTF-8',$_parking->name).'</td>';
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
					echo Form::hidden('parking', $_parking->id);
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
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title"><?echo $title;?></h3>
	</div>
	<div class="panel-body">
		<?php
			echo __('Список машиномест');
			//echo Debug::vars('123', $card_list);

	//$_parking=Arr::get(Arr::flatten($id_place), 'ID');
	
	//echo Debug::vars('117', $_parking);exit;
	echo 'Матричное представление парковочного пространства для парковки "'. iconv('windows-1251','UTF-8', $_parking->name).'" ('.$_parking->id.')';
?>
<table class="table table-hover">
<thead>
</thead>


<tbody>
<?php
$countTotal=$_parking->count;//общее количество мест
$rowMax=5;//количество строк для показа всех машиномест
$currentPlace=1;//номер машиноместа, по которому выводится информация.
$rowCount=10;//количество машиномест в строке
$y=0;
While($currentPlace<$countTotal)
{
	
	echo '<tr>';
		for($x=1; $x<$rowCount+1;$x++)
		{
			if($currentPlace<$countTotal+1)
			{
				$_place = new PlaceNP($currentPlace, $_parking->id);
				$_mess='';
					if($_place->id>0 )
					{
						$_class='success';//желтый
						
					} else {
						$_class='active';//серый
					}
						echo '<td class="'.$_class.'">';					
										
							echo $currentPlace.' '.$_mess;
						echo Form::open('place/control');
							echo Form::hidden('place', $currentPlace);
							echo Form::hidden('parking', $_parking->id);
							echo Form::submit('todo', 'editMatrix');
						echo Form::close();
						echo '</td>';
			}
			$currentPlace++;
			
		}
	
	echo '</tr>';
}

?>
</tbody>
</table>
</div>
</div>



	
  

