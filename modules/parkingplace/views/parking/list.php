<? //http://itchief.ru/lessons/bootstrap-3/30-bootstrap-3-tables;
// страница отображения данных по парковочной системе
//echo Debug::vars('3', $rubic_list);
//echo Debug::vars('3', $id_parkingPlace);
//echo Debug::vars('4', $id_parent);

if($id_parent > 0)
{
	$_parent=new Residence($id_parent);
	$_title='Список парковочных площадок жилого комлпекса "'. iconv('windows-1251','UTF-8', $_parent->name).'"';
} else {
	
	$_title='Список всех парковочных площадок';
}

echo Form::open('ParkingPlace/control');
?>
			
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo $_title;?></h3>
	</div>
	<div class="panel-body">

<table class="table table-striped table-hover table-condensed">


		<tr>
			<th><?echo Kohana::message('rubic','select');?></th>
			<th><?echo __('ID парковочной площадки');?></th>
			<th><?echo __('Название парковочной площадки');?></th>
			<th><?echo __('Жилокй комплекс');?></th>
			<th><?echo __('Количество машиномест');?></th>
			<th><?echo __('Зарегистрировано машиномест');?></th>
			
		</tr>
		<?php 
		$i=0;
		$checked='no';
		$total_place=0;
		$total_occup=0;
		$total_vacant=0;
		
		
							
							
		foreach($id_parkingPlace as $key=>$value)
		{
			
			$parkingPlace=new Parking(Arr::get($value, 'ID'));
			echo '<tr>';
				//if($i==0) echo '<td>'.Debug::vars('53', $parkingPlace).' '.Form::radio('id', Arr::get($value,'ID'), FALSE, array('checked'=>$checked)).'</td>';
				if($i==0) echo '<td>'.Form::radio('id', Arr::get($value,'ID'), FALSE, array('checked'=>$checked)).'</td>';
				if($i>0) echo '<td>'.Form::radio('id', Arr::get($value,'ID'), FALSE).'</td>';
				echo '<td>'.$parkingPlace->id.'</td>';
				//echo '<td>'. HTML::anchor('parking?id_resident='.Arr::get($value,'ID', 0) , iconv('windows-1251','UTF-8', $residence->name)).'</td>';
				echo '<td>';
					echo iconv('windows-1251','UTF-8', $parkingPlace->name);
					echo ' ';
					echo HTML::anchor('place/list/'.Arr::get($value,'ID'), 'List');
					echo ' ';
					echo HTML::anchor('place/matrix/'.Arr::get($value,'ID'), 'Matrix');
					
				echo '</td>';
				//echo '<td>'.$parkingPlace->is_active.'</td>';
				//echo '<td>'.$parkingPlace->created.'</td>';
				//echo '<td>'.$parkingPlace->modify.'</td>';
				$_residence=new Residence($parkingPlace->parent);
				echo '<td>'.iconv('windows-1251','UTF-8', $_residence->name).'</td>';
				echo '<td>'.$parkingPlace->count.'</td>';
				//Подсчет количества зарегистрированных машиномест
				$placeList=Model::factory('Place')->getChild($parkingPlace->id);
				echo '<td>'.count($placeList).'</td>';
				
				
			echo '</tr>';	
			
		}
		
		?>

	</table>		
			
		
		<?php if(Auth::Instance()->logged_in())
		{
			echo Form::button('todo', Kohana::message('rubic','rp_edit'), array('value'=>'edit','class'=>'btn btn-success  btn-xs', 'type' => 'submit'));	
			echo Form::button('todo', Kohana::message('rubic','rp_del'), array('value'=>'del','class'=>'btn btn-danger  btn-xs', 'type' => 'submit', 'onclick'=>'return confirm(\''.__('delete').'?\') ? true : false;'));
		} else {
			echo Form::button('todo', Kohana::message('rubic','to_view'), array('value'=>'edit_rubic','class'=>'btn btn-success', 'type' => 'submit'));	
		}?>
		
		
</div>
</div>



<?php 

if($id_parent > 0)
{

	
	

if(Auth::Instance()->logged_in())
			{?>
		<div class="panel panel-primary">
		  <div class="panel-heading">
			<h3 class="panel-title"><?echo __('Добавить парковочную площадку')?></h3>
		  </div>
		  <div class="panel-body">
		  
			<?
			
			
			
			$residenceList=Model::factory('ResidentPlace')->get_list();//получил список id жилых комплексов
			
			$selectList=array();
			
			foreach ($residenceList as $key=>$value)
			{
				$residence=new Residence(Arr::get($value, 'ID'));
				$selectList[Arr::get($value, 'ID')]=iconv('windows-1251','UTF-8', $residence->name);
				
			}
			echo 'Жилой комплекс: '.Form::select('parent', $selectList, $id_parent);
			
			echo '<br>';
			echo __('Название парковочной площадки');
			//echo Form::hidden('id_parent',$id_parent);
			echo Form::input('name',null , array('placeholder'=>'Новая парковочная площадка'));
			echo '<br>';
			echo Form::button('todo', Kohana::message('rubic','rubic_add','rubic_add'), array('value'=>'add','class'=>'btn btn-success', 'type' => 'submit'));	
			
			
			
			?>	

		  </div>

		</div>


	<?php }

} else {
//ничего не показывать, т.к. площадок много, и добавлять ничего не надо.	
	
} ?>






<?echo Form::close();?>
<script>
    $(function(){
        window.setTimeout(function(){
            $('#my-alert').alert('close');
        },5000);
    });
</script>
  