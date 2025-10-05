<?php //http://itchief.ru/lessons/bootstrap-3/30-bootstrap-3-tables;
// страница отображения данных по машноместам
//echo Debug::vars('3', $id_place);

$t1=microtime(true);

//вспомогательный класс для быстрого преобразования массива в класс.
//ключи массива становятся свойствами класса
  class MyClass {
    public function __construct(Array $properties=array()){
      foreach($properties as $key => $value){
        $this->{strtolower($key)} = $value;
      }
    }
  }
  

		$placeList=Model::factory('Place')->getAll();//список машиномест на этой парковочной площадке
		
		$titleAddPlace=__('Регистрация машиноместа для парковочных площадок. Общее количество мест на площадке :countPlace',
					array(
						
						':regPlace'=> count($placeList),
						':countPlace'=>count($placeList)
						));
						

		$title=__('Список машиномест для всех парковочных площадок. Парковочных площадок :regPlace. Зарегистрировано машиномест на площадках :countPlace',
					array(
						':regPlace'=> Model_ParkingPlace::getCountParking(),
						':countPlace'=>count($placeList)
						));

?>
<script type="text/javascript">
     
  	$(function() {		
  		$("#tablesorter").tablesorter({sortList:[[0,0]], headers: { 0:{sorter: false}}});
  	});	
	
</script> 
<?php if(Auth::Instance()->logged_in())
{
	
	//if(isset($renamePlace)) echo $renamePlace;//блок управления названиями
	
	$parkingPlace=Model::factory('ParkingPlace')->get_list_for_select();//получил список парковочных площадок
	$selectList=array();
			
	?>
	<div class="panel panel-primary">
		  <div class="panel-heading">
			<h3 class="panel-title"><?php echo $titleAddPlace;?></h3>
		  </div>
		  <div class="panel-body">
			<div id="my-alert" class="alert alert-success alert-dismissible" role="alert">
					<?php 
						echo 'Номер машиноместа вводится как длинное десятичное число: 000123456789.<br>Остальные варианты ввода будут игнорироваться.';
					?>
			</div>
			<?
			echo __('Регистрация парковочного места').'<br>';
			echo Form::open('place/control');
			?>
			<table>
				<tr>
					<th>Парковочная площадка</th>
					<th>Номер машиноместа</th>
					<th>Комментарий машиноместа</th>
				</tr>
				<tr>
					<td><?php echo Form::select('id_parking', $parkingPlace);?></td>
					<td><?php echo Form::input('placenumber','', array('placeholder'=>'Номер машиноместа','minlength '=>1,'maxlength  '=>5, 'required'=>'required', 'type'=>'number' ));?></td>
					<td><?php echo Form::input('new_place_name', '', array('placeholder'=>'Комментарий машиноместа','maxlength  '=>205));?></td>
				</tr>
			</table>
		<?php
				
			echo '<br>';
			echo Form::button('todo', 'Зарегистрировать новое машиноместо', array('value'=>'add','class'=>'btn btn-success', 'type' => 'submit'));	
			echo Form::close();
			
			?>	

		  </div>

	</div>
	<div class="panel panel-primary">
		  <div class="panel-heading">
			<h3 class="panel-title"><?php echo __('titleAddPlaceArray');?></h3>
		  </div>
		  <div class="panel-body">
			<div id="my-alert" class="alert alert-success alert-dismissible" role="alert">
					<?php 
						echo 'Будет добавлено указанное количество машиномест.<br>Уже существующие машиноместа изменены НЕ будут!';
					?>
			</div>
			<?
			echo __('Регистрация парковочного места').'<br>';
			echo Form::open('place/control');
			?>
			<table>
				<tr>
					<th>Парковочная площадка</th>
					<th>С какого номера</th>
					<th>По какой номер</th>
				</tr>
				<tr>
					<td>
						<div class="input-container">
							<?php echo Form::select('id_parking', $parkingPlace);?></td>
						</div>
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
				
		echo Form::checkbox('makeGarage', 1, false, array('onchange'=>"document.getElementById('requiredField').required = this.checked;"));
		echo __('Создавать автоматически гараж для каждого машиноместа.');
		echo '<br>';
		echo Form::input('name', '',array('placeholder'=>'Название гаража','minlength '=>1,'maxlength  '=>200, 'id'=>"requiredField")).'Название для гаражей<br>';
		
		
			
			
			echo '<br>';
			echo Form::button('todo', 'Зарегистрировать новые машиноместа', array('value'=>'addarray','class'=>'btn btn-success', 'type' => 'submit'));	
			
			echo Form::close();
			
			?>	

		  </div>

	</div>
	
	
<?php
}


?>


<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title"><?echo $title;?></h3>
	</div>
	<div class="panel-body">
		<?php
			echo __('Список машиномест');
			echo Form::open('place/control');
			//echo Debug::vars('123', $card_list);
		?>
	<table id="tablesorter" class="table table-striped table-hover table-condensed tablesorter">

	<thead allign="center">
		<tr>
			
			<th><?echo __('Номер п/п');?></th>
			<th><?echo __('Выбор');?></th>
			<th><?echo 'Название парковки';?></th>
			<!--<th><?echo __('Номер машиноместа');?></th>-->
			<th><?echo __('Машиноместо');?></th>
			<th><?echo __('Комментарий машиноместа');?></th>
			<th><?echo 'Прим.';?></th>
			<th><?echo 'Гараж';?></th>
		</tr>

		</thead>
		<tbody>
		<?php 
		$i=0;
		$checked='no';
		//вывод списка машиномест для указанного паркинга
		
		foreach($place_list as $key=>$value)
		{
			$place = new MyClass($value);//см описание класса выше
			
			//echo Debug::vars('68', $key, $value, $place); exit;
			
			echo '<tr>';
				
				echo '<td>';
					echo ($i+1);
					//echo ' '. Debug::vars('82', $place);
				echo '</td>';
				echo '<td>'.Form::radio( 'id', $place->id, Arr::get($value, 'is_active' == 1)).' '.$place->id.'</td>';
								
				echo '<td>'. iconv('windows-1251','UTF-8',$place->parkingname).'</td>';
				/* if(Auth::Instance()->logged_in())
				{				
					echo '<td>'.HTML::anchor('place/edit/'.$place->id,
							$place->placenumber)
							.'</td>';
				} else 
				{
					echo '<td>'.$place->placenumber.'</td>';
					
				} */
				if(Auth::Instance()->logged_in())
				{				
					echo '<td>'.HTML::anchor('place/edit/'.$place->id,
							iconv('windows-1251','UTF-8', $place->name))
							.'</td>';
				} else 
				{
					echo '<td>'.iconv('windows-1251','UTF-8', $place->name).'</td>';
					
				}
				
				//echo '<td>'.iconv('windows-1251','UTF-8', $place->name). '1</td>';
				echo '<td>'.iconv('windows-1251','UTF-8',$place->description).'</td>';
				echo '<td>'.iconv('windows-1251','UTF-8',$place->note).'</td>';
				if($place->id_garage) 
				{
					echo '<td>'.HTML::anchor('garage/edit_garage/'.$place->id_garage,  iconv('windows-1251','UTF-8', $place->garagename)).' </td>';
				} else 
				{
					echo '<td>--</td>';
				}
				
			echo '</tr>';	

			$i++;
			
		}
		
		?>
		</tbody>
	</table>		
	
		
	


		
<?php 
echo 'Time execute='.(microtime(true)-$t1);
?>


<?php
if(Auth::Instance()->logged_in())
{
?>
		
		<nav class="navbar navbar-default navbar-fixed-bottom disable" role="navigation">
		  <div class="container">

				<?php
					echo Form::button('todo', __('place_edit'), array('value'=>'edit','class'=>'btn btn-success', 'type' => 'submit'));	
					echo Form::button('todo', __('place_del'), array('value'=>'del','class'=>'btn btn-danger', 'type' => 'submit', 'onclick'=>'return confirm(\''.__('delete').'?\') ? true : false;'));
				?>
			
			</div>
		</nav>
	<?php 
	}
	?>	






<?php
	echo Form::close();

?>
		</div>
			</div>
