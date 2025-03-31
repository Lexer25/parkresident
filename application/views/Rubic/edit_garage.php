<style>
.tree{
  --spacing : 1.5rem;
  --radius  : 5px;
}

.tree li{
  display      : block;
  position     : relative;
  padding-left : calc(2 * var(--spacing) - var(--radius) - 2px);
}

.tree ul{
  margin-left  : calc(var(--radius) - var(--spacing));
  padding-left : 0;
}

.tree ul li{
  border-left : 2px solid #ddd;
}

.tree ul li:last-child{
  border-color : transparent;
}

.tree ul li::before{
  content      : '';
  display      : block;
  position     : absolute;
  top          : calc(var(--spacing) / -2);
  left         : -2px;
  width        : calc(var(--spacing) + 2px);
  height       : calc(var(--spacing) + 1px);
  border       : solid #ddd;
  border-width : 0 0 2px 2px;
}

.tree summary{
  display : block;
  cursor  : pointer;
}

.tree summary::marker,
.tree summary::-webkit-details-marker{
  display : none;
}

.tree summary:focus{
  outline : none;
}

.tree summary:focus-visible{
  outline : 1px dotted #000;
}

.tree li::after,
.tree summary::before{
  content       : '';
  display       : block;
  position      : absolute;
  top           : calc(var(--spacing) / 2 - var(--radius));
  left          : calc(var(--spacing) - var(--radius) - 1px);
  width         : calc(2 * var(--radius));
  height        : calc(2 * var(--radius));
  border-radius : 50%;
  background    : #ddd;
}

.tree summary::before{
  content     : '+';
  z-index     : 1;
  background  : #696;
  color       : #fff;
  line-height : calc(2 * var(--radius) - 2px);
  text-align  : center;
}

.tree details[open] > summary::before{
  content : '−';
}
</style>

<script>

$(function() {
    var btn = $("[href='#target_org']");
    var toggled = false;
    btn.on("click", function() {
        if(!toggled)
        {
          toggled = true;
          btn.text("Свернуть список квартир");
        } else {
          toggled = false;
          btn.text("Развернуть список квартир");
        }
    });
});
$(function() {
    var btn = $("[href='#target_place']");
    var toggled = false;
    btn.on("click", function() {
        if(!toggled)
        {
          toggled = true;
          btn.text("Свернуть список машиномест");
        } else {
          toggled = false;
          btn.text("Развернуть список машиномест");
        }
    });
});

</script>


<? //http://itchief.ru/lessons/bootstrap-3/30-bootstrap-3-tables;
/* echo Debug::vars('11', 
	$garage_info);  */
// страница отображения данных по гаражу
//echo Debug::vars('5', $_SESSION);
echo Form::open('garage/control');

//if(Auth::Instance()->logged_in())
{?>
<script type="text/javascript">
     
  	$(function() {		
  		$("#tab0").tablesorter({
			sortList:[[0,0]],
			headers: { 0:{sorter: false},
			widgets: ['zebra'],
			debug:true
			}});
  	});	
		
		
	$(function() {		
  		$("#tab1").tablesorter({sortList:[[0,0]], headers: { 0:{sorter: false}}});
  	});	
	
</script>

<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title"><?echo 'Редактировать гараж '. Arr::get($garage_info, 'name');
		echo Form::hidden('id_garage', Arr::get($garage_info,'ID'));
		echo Form::hidden('id_org', Arr::get($garage_info,'id_org'));
		?></h3>
	</div>
	<div class="panel-body">
		
		<?php 
		//echo Debug::vars('11', $garage_info, Arr::get($garage_info, 'ENABLED'));
		//echo Form::hidden('id_rubic', Arr::get($garage_info, 'ID'));
		echo 'Название гаража'.Form::input('name', Arr::get($garage_info, 'NAME'), array('maxlength'=>250)).'<br>';
		echo 'ID гаража'. ' '. Arr::get($garage_info, 'ID').'<br>';
		
		echo Form::checkbox('not_count',1, Arr::get($garage_info, 'NOT_COUNT') ==1).__('Не считать количество свободных мест.');
		
		?>
			<?php
		if(Auth::Instance()->logged_in()){
			echo Form::button('todo', 'Изменить', array('value'=>'change_garage','class'=>'btn btn-success', 'type' => 'submit'));	
		} else {			
			echo Form::button('todo', 'Изменить', array('value'=>'change_garage','class'=>'btn btn-light', 'type' => 'submit', "disabled"=>"disabled"));	
		}
			
		?>
	</div>
	<?php
		//echo debug::vars('128', $place_grz_garage_);
		if(count($place_grz_garage_))
		{
			foreach($place_grz_garage_ as $key=>$value)
				
				echo Arr::get($value, 'GRZ').' '.Arr::get($value, 'NAME').' ';
		} else 
		{
			echo __('Нет ГРЗ для этого гаража.');
		}
	echo Form::close();
	?>
</div>

<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title"><?echo __('Список машиномест, входящих в гараж.');?></h3>
	</div>
	<div class="panel-body">

		<table id="tab0" class="table table-striped table-hover table-condensed tablesorter">


	<thead allign="center">
		<tr>
			<th><?echo Kohana::message('rubic','pp');?></th>
			<th><?echo 'Номер машиноместа';?></th>
			<th><?echo 'Название машиноместа';?></th>
			<th><?echo 'Парковка';?></th>
			<th><?echo 'Прим.';?></th>
			
		</tr>

		</thead>
		<tbody>
		<?php 
		$i=1;
		$checked='no';
		foreach($place_income_garage as $key=>$value)
		{
			echo Form::open('garage/control');
			echo Form::hidden('id_place_for_del', Arr::get($value,'ID'));
			echo Form::hidden('id_garage', Arr::get($garage_info,'ID'));
			echo '<tr>';
				echo '<td>'.$i.'</td>';
				echo '<td>'.Arr::get($value,'PLACENUMBER').'</td>';
				echo '<td>'.Arr::get($value,'NAME').'</td>';
				echo '<td>'.Arr::get($value,'PARKING_NAME').'</td>';
				//	echo '<td>'.Arr::get($value,'NOTE').'</td>';
				echo '<td>';
					
					if(Auth::Instance()->logged_in()){
						//echo Form::button('todo', 'Удалить', array('value'=>'del_place_from_garage','class'=>'btn btn-danger', 'type' => 'submit'));
					} else {
						//echo Form::button('todo', 'Удалить', array('value'=>'del_place_from_garage','class'=>'btn btn-danger',  "disabled"=>"disabled", 'type' => 'submit'));
					} 
				echo '</td>';
				
			echo '</tr>';	
			$i++;
			echo Form::close();
			
		}
		
		?>
		</tbody>
	</table>	


<?php 
	echo Form::open('garage/control');
	echo Form::hidden('id_garage', Arr::get($garage_info,'ID'));
	if(Auth::Instance()->logged_in())
	{
	?>

			
		<a class="btn btn-primary" data-toggle="collapse" href="#target_place">Развернуть список машиномест</a>
<div class="panel panel-default">
		<!-- #target -->
	<div class="collapse" id="target_place">

		<div>
		<?php 
		echo Form::button('todo', 'Добавить/изменить машиноместо', array('value'=>'add_place_to_garage','class'=>'btn btn-success', 'type' => 'submit'));
		//echo Debug::vars('197', $place_list ); //exit;
		if(isset($place_list))
			{ ?>
			
			<table class="table table-striped table-hover table-condensed">
				<tbody>
				<?php 
				//echo Debug::vars('105', $place_busy);
				//echo Debug::vars('106', array_key_exists(5, $place_busy));
				
				$i=0;
				$checked='no';
				$column=10;// количество колонок в таблице
				$row= ceil(count($place_list)/$column);
				$aaa=array_chunk($place_list, $column);
				//echo Debug::vars('120', $aaa); //exit;
				
				for ($i=0; $i<$row; $i++)
				{
					echo '<tr>';
						foreach(Arr::get($aaa, $i) as $key=>$value)
						{
							if(array_key_exists(Arr::get($value, 'ID'), $place_busy) and !array_key_exists(Arr::get($value, 'ID'), $place_income_garage)) // если это место уже занято, то запретить редактирование
							{
							echo '<td>'.Form::checkbox('id_place['.Arr::get($value, 'ID').']', Arr::get($value, 'ID'), TRUE, array("disabled"=>"disabled")).'мм №'.Arr::get($value, 'PLACENUMBER').'</td>';
							} else {
							echo '<td>'.Form::checkbox('id_place['.Arr::get($value, 'ID').']', Arr::get($value, 'ID'), (array_key_exists(Arr::get($value, 'ID'), $place_income_garage))? true : false, array()).'мм №'.Arr::get($value, 'PLACENUMBER').'</td>';
							}
						}
					echo '</tr>';	
					
				}
				
				?>
				</tbody>
			</table>
			<?php 
			echo Form::button('todo', 'Добавить/изменить машиноместо', array('value'=>'add_place_to_garage','class'=>'btn btn-success', 'type' => 'submit'));
			} else {
				echo __('no_date_for_view');
		
			};?>
		</div>
	</div>
	</div>
<?php } ;?>

</div>
</div>

<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title"><?echo 'Список квартир, паркующихся в гараже'?></h3>
	</div>
	<div class="panel-body">

		<table id="tab1" class="table table-striped table-hover table-condensed tablesorter">

	<thead allign="center">
		<tr>
			<th><?echo Kohana::message('rubic','pp');?></th>
			<th><?echo 'ID квартиры(группы)';?></th>
			<th><?echo 'Название квартиры (группы)';?></th>
			<th><?echo 'Прим.';?></th>
		</tr>

		</thead>
		<tbody>
		<?php 
		$i=1;
		$checked='no';
		foreach($org_income_garage as $key=>$value)
		{
			echo '<tr>';
				echo '<td>'.$i.'</td>';
				echo '<td>'.Arr::get($value,'ID').'</td>';
				echo Form::hidden('id_org_for_del_from_garage', Arr::get($value,'ID'));
				echo '<td>'.Arr::get($value,'NAME').'</td>';
				echo '<td>'.Arr::get($value,'NOTE').'</td>';
				
			echo '</tr>';	
			$i++;
			
		}
		
		?>
		</tbody>
	</table>	




<?php 
	if(Auth::Instance()->logged_in())
	{
	?>

	<a class="btn btn-primary" data-toggle="collapse" href="#target_org">Развернуть список квартир</a>
	<div class="panel panel-default">
		<!-- #target -->
			<div class="collapse" id="target_org">
				<?php 
				
				echo Form::button('todo', 'Добавить/изменить квартиры', array('value'=>'add_org_to_garage','class'=>'btn btn-success', 'type' => 'submit'));
				echo $org_can_view;
				
					echo Form::button('todo', 'Добавить/изменить квартиры', array('value'=>'add_org_to_garage','class'=>'btn btn-success', 'type' => 'submit'));
			
				?>
			</div> 
			
			
			<div>
			<?php if(isset($place_list))
				{ ?>
				

				<?php } else {
					echo __('no_date_for_view');
			
				};?>
			</div>
		</div>
<?php } ;?>		
	</div>
</div>






<?php echo Form::close();
}


?>

<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title"><?echo 'Журнал событий'?></h3>
	</div>
	<div class="panel-body">
	<?php
		$garage = new Garage(Arr::get($garage_info, 'ID'));
		$events=new Events();
		$eventsListForGarage=$events->getListEventsForGarage($garage->getEvents());//я передаю список id событий, которые надо вывести на экран

		 echo View::factory('garage/event')// вывод таблицы журнала событий для гаража
				->set('list', $eventsListForGarage)

			; 
			
	?>

	</div>
</div>
