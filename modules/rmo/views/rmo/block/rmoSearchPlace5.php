<div class="panel panel-primary container-fluid"> 

  <div class="panel-heading">
  <?php
if(!isset($searchPlaceTitle)) $searchPlaceTitle=__('Поиск машиноместа');
	
  ?>
    <h3 class="panel-title"><?php echo $searchPlaceTitle;?></h3>
   </div>
  <div class="panel-body">
	
<?
if(isset($garageListView)) //вывожу список информации по найденным машиноместам
{
	echo $garageListView;
} 
// Раздел ввода данных для поиска машиноместа
	if(!isset($garage_info))
	{
		echo __('comment_for_search');
		echo Form::open('rmo/control');
			echo Form::button('todo', 'Найти', array('value'=>'find_place','class'=>'btn btn-primary', 'type' => 'submit'));
			echo ' ';
			//echo Form::input('num_for_search', '1', array( 'type'=>'number', 'max'=>99999));
			echo Form::input('num_for_search', '1', array('max'=>50));
			echo ' ';
			//echo Form::button('todo', 'Поиск ГРЗ', array('value'=>'find_grz','class'=>'btn btn-success', 'type' => 'submit'));	
		echo Form::close();	
	}
//вывод информации по гаражу


?>
</div>	
</div>