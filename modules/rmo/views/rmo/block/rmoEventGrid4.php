 <div class="panel panel-primary"> 

	  <div class="panel-heading">
		<h3 class="panel-title"><?php echo __('Журнал событий');?></h3>
		
	  </div>
	  <div class="panel-body"> 
		Журнал событий
		<?php
			//таблица с журналом событий формируется в другом файле
			include Kohana::find_file('views/monitor','list');//вывод таблицы с журналом событий
		
		?>
     </div>
 </div>
 