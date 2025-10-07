<?php
// 5.10.2025
//блок управления названиями машиномест. Пока что он нужен только для вывода кнопки Преобразовать название.
//


?>
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo 'Управления названиями машиномест';?></h3>
	</div>
	<div class="panel-body">
	

				       <table id="tablesorter_ge3" class="table table-striped table-hover table-condensed tablesorter">
                    <thead>
                    <tr>
                        <th><?echo __('№ п/п');?></th>
                        <th><?echo __('Параметр');?></th>
                        <th><?echo __('Значение.');?></th>
                        <th><?echo __('Время события');?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
					$setting=new Setting();
                    $i=0;
					
    
						echo '<tr>';
							echo '<td>'.++$i.'</td>';
							echo '<td>Минимальная длина названия машиноместа. Если введенный номер короче указанной длины, то слева будут добавлены символы "0".</td>';
							echo Form::open('wizard/cvs');
								echo Form::hidden('type', 'int');
								echo Form::hidden('name', 'placeNameLenght');
								echo '<td>'.Form::input('value',$setting->get('placeNameLenght', 3), array('type'=>'number', 'min'=>'2', 'max'=>'4')).'</td>';
								echo '<td>'.Form::button('cvs', 'Сохранить', array('disabled'=>'disabled')).'</td>';
							 echo Form::close();	
                        echo '</tr>';
						echo '<tr>';
							echo '<td>'.++$i.'</td>';
							echo '<td>Применять форматирование длины названия машиноместа</td>';
							echo Form::open('wizard/cvs');
								echo Form::hidden('type', 'int');
								echo Form::hidden('name', 'placeNameLenghtApply');
								echo '<td>'.Form::checkbox('value', 1, $setting->get('placeNameLenghtApply', 0) == 1).'</td>';
								echo '<td>'.Form::button('cvs', 'Сохранить', array('disabled'=>'disabled')).'</td>';
							 echo Form::close();	
                        echo '</tr>';
						
 
                    ?>
                    </tbody>
                </table>
			<?php
	if(Auth::Instance()->logged_in()) echo HTML::anchor('wizard', 'Изменить настройки');
	?>
		
				
		
	</div>
</div>