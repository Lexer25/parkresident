<?php defined('SYSPATH') OR die('No direct access allowed.');
//echo Debug::vars('2', $tableList); //exit;
//echo Debug::vars('3', $tableListCheck); //exit;
//echo Debug::vars('4', $procedureList); //exit;
//echo Debug::vars('5', $procedureListCheck); //exit;
?>
<script type="text/javascript">
    $(function() {
        $("#tablesorter_ge").tablesorter({sortList:[[0,0]], headers: {}});
    });
</script>

<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title"><?php echo __('Настройка СКУД для работы парковочной системы');?></h3>
    </div>
    <div class="panel-body">

                <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title"><?php echo __('Настройка параметров');?></h3>
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
							echo '<td>Т1 Время блокировки повторного распознавания delay_cvs (сек)</td>';
							echo Form::open('wizard/cvs');
								echo Form::hidden('type', 'int');
								echo Form::hidden('name', 'delay_cvs');
								echo '<td>'.Form::input('value',$setting->get('delay_cvs')).'</td>';
								echo '<td>'.Form::button('cvs', 'Сохранить').'</td>';
							 echo Form::close();	
                        echo '</tr>';
						
						echo '<tr>';
							echo '<td>'.++$i.'</td>';
							echo '<td>Т3 Время блокировки второй проезд через ревесивные ворота delayReversRepeat. (сек)</td>';
							echo Form::open('wizard/cvs');
								echo Form::hidden('type', 'int');
								echo Form::hidden('name', 'delayReversRepeat');
								echo '<td>'.Form::input('value',$setting->get('delayReversRepeat')).'</td>';
								echo '<td>'.Form::button('cvs', 'Сохранить').'</td>';
							 echo Form::close();	
                        echo '</tr>';
						echo '<tr>';
							echo '<td>'.++$i.'</td>';
							echo '<td>Минимальная длина названия машиноместа. Если введенный номер короче указанной длины, то слева будут добавлены символы "0".</td>';
							echo Form::open('wizard/cvs');
								echo Form::hidden('type', 'int');
								echo Form::hidden('name', 'placeNameLenght');
								echo '<td>'.Form::input('value',$setting->get('placeNameLenght', 3), array('type'=>'number', 'min'=>'2', 'max'=>'4')).'</td>';
								echo '<td>'.Form::button('cvs', 'Сохранить').'</td>';
							 echo Form::close();	
                        echo '</tr>';
						echo '<tr>';
							echo '<td>'.++$i.'</td>';
							echo '<td>Применять форматирование длины названия машиноместа</td>';
							echo Form::open('wizard/cvs');
								echo Form::hidden('type', 'int');
								echo Form::hidden('name', 'placeNameLenghtApply');
								echo '<td>'.Form::checkbox('value', 1, $setting->get('placeNameLenghtApply', 0) == 1).'</td>';
								echo '<td>'.Form::button('cvs', 'Сохранить').'</td>';
							 echo Form::close();	
                        echo '</tr>';
						
 
                    ?>
                    </tbody>
                </table>
               
            </div>
        </div>
		
	

  <?php
		if (Kohana::find_file('views/setup','viewAccessLevel'))  include Kohana::find_file('views/setup','viewAccessLevel');
		if (Kohana::find_file('views/setup','viewControlBox'))  include Kohana::find_file('views/setup','viewControlBox');
		if (Kohana::find_file('views/setup','viewTsList'))  include Kohana::find_file('views/setup','viewTsList');
		if (Kohana::find_file('views/setup','viewDeviceList'))  include Kohana::find_file('views/setup','viewDeviceList');
		
  ?>
    </div>
</div>