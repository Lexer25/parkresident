<script>
$(function() {
	
     $(".btn").click(
       function() {
         var bname = $(this).attr('org_name');
         var _org_id = $(this).attr('org_id');
        

	
         $(".kartka h1").text(bname);
         $(".kartka h4").html(_org_id);
         
		 
		 document.getElementById("id_gate").value = $(this).attr('org_id');
		 
		
		 
       });
	

   });
 
   	$(function() {		
  		$("#tablesorter").tablesorter({sortList:[[0,0]]});
  	});	
	
 
</script>
<?php

//echo (isset($garage_info))?  Debug::vars('2', $garage_info) : 'no garage_info';
//echo(isset($place_income_garage))?  Debug::vars('2', $place_income_garage) : 'no place_income_garage';
//echo(isset($org_income_garage))?  Debug::vars('2', $org_income_garage) : 'no org_income_garage';
//echo(isset($get_grz_in_parking))?  Debug::vars('2', $get_grz_in_parking) : 'no get_grz_in_parking';
//echo(isset($place_grz_garage_))?  Debug::vars('2', $place_grz_garage_) : 'no place_grz_garage_';
//echo Debug::vars(Session::instance()->as_array()); 


?>
<style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: #f0f0f0;
        }
        .container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }
        .camera-view {
            width: 48%;
            min-width: 280px;
            background: #000;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        .camera-view h3 {
            color: white;
            text-align: center;
            margin: 10px 0;
        }
        .video-wrapper {
            position: relative;
            padding-bottom: 75%; /* 16:9 соотношение */
        }
        video {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }
        .controls {
            text-align: center;
            margin-top: 20px;
        }
        button {
            padding: 8px 16px;
            margin: 0 5px;
            background: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
    </style>
	

<div class="panel panel-primary container-fluid"> 

  <div class="panel-heading">
    <h3 class="panel-title"><?php echo __('Найдена информация по машиноместу № ').' '.Session::instance()->get('place_for_search');?></h3>
    <h3 class="panel-title"><?php //echo __('Разрешить проезд выбранного ГРЗ на машиноместо').' '.Session::instance()->get('place_for_search');?></h3>
  </div>
  <div class="panel-body">
	
<?// Раздел ввода данных
	//echo Debug::vars('12', $garage_info);
	if(!isset($garage_info))
	{
		echo __('comment_for_search');
		echo Form::open('rmo/control');
			echo Form::button('todo', 'Поиск машиноместа', array('value'=>'find_place','class'=>'btn btn-primary', 'type' => 'submit'));
			echo ' ';
			echo Form::input('num_for_search', '1', array( 'type'=>'number', 'max'=>999));
			echo ' ';
			//echo Form::button('todo', 'Поиск ГРЗ', array('value'=>'find_grz','class'=>'btn btn-success', 'type' => 'submit'));	
		echo Form::close();	
	}
//вывод информации по гаражу
if(isset($garage_info))
{
	//echo Debug::vars('12', $garage_info);

	$level_count=array();
	foreach($place_income_garage as $key=>$value)
	{
		//echo Debug::vars('41', $key, $value);
		if(!array_key_exists(Arr::get($value,'ID_PARKING'), $level_count)) $level_count[]=Arr::get($value,'ID_PARKING');
	}
	//echo Debug::vars('44', count($level_count), $level_count);
?>
<table id="tablesorter" class="table table-striped table-hover table-condensed tablesorter">

	<thead allign="center">
		<tr>
			<th><?echo __('Название гаража')?></th>
			<th><?echo __('Кол-во квартир (название)');?></th>
			<th><?echo __('Кол-во ГРЗ<br>(ГРЗ, модель)');?></th>
			<th><?echo __('Кол-во машиномест<br>(номера машиномест)');?></th>
			<th><?echo __('Количество ГРЗ на территории<br>Перечень<br>Дата въезда');?></th>
			<th><?echo __('Осталось<br>свободных<br>мест');?></th>
			
		</tr>

		<tr>
			<th>1</th>
			<th>2</th>
			<th>3</th>
			<th>4</th>
			<th>5</th>
			<th>6</th>
			
		</tr>

		
		</thead>
		<tbody>
		<?php 
			echo '<tr>';
				//название гаража
				echo '<td>'.Arr::get($garage_info, 'NAME').'<br>(id_garage='.Arr::get($garage_info, 'ID').')</td>'; 
				
				//перечень квартир
				echo '<td>';
					foreach($org_income_garage as $key=>$value)
					{
					 echo Arr::get($value, 'NAME').' (id_garage='.Arr::get($value, 'ID').')<br>';
					}
				echo '</td>';
				
				//перечень ГРЗ
				echo '<td>';
					//разрешить въезд известным ГРЗ
					//echo Form::open('rmo/mqtt');
					echo Form::open('rmo/opengateCVS');
					echo Form::hidden('id_garage', Arr::get($garage_info, 'ID'));
					echo Form::hidden('place_for_open', Session::instance()->get('place_for_search'));
					echo Form::hidden('parking_for_open', Arr::get(Arr::get($place_income_garage, Session::instance()->get('place_for_search')), 'ID_PARKING'));
	
						$total_grz=count($place_grz_garage_);
						//echo Debug::vars('57', $place_grz_garage_);
						echo __('Всего ГРЗ:').' '. $total_grz.'<br>';
						foreach($place_grz_garage_ as $key=>$value)
						{
						 if (Arr::get($value, 'ACTIVE') >0)
						 {
							echo Form::button('opendoor', Arr::get($value, 'GRZ').' ('.Arr::get($value, 'NAME').')', array('value'=>Arr::get($value, 'GRZ'), 'class'=>'btn btn-success btn-xs', 'type' => 'submit', 'onclick'=>'return confirm(\''.__('Будет открыт въезд для grz на парковку PARKING_NAME. Открыть', array('grz'=>Arr::get($value, 'GRZ'), 'PARKING_NAME'=>Arr::get(Arr::get($place_income_garage, Session::instance()->get('place_for_search')), 'PARKING_NAME'))).'?\') ? true : false;')).'<br><br>';
						 } else
						 {
							echo Form::button('opendoor', Arr::get($value, 'GRZ').' ('.Arr::get($value, 'NAME').')', array('value'=>Arr::get($value, 'GRZ'), 'disabled'=>'disabled', 'class'=>'btn btn-danger btn-xs', 'type' => 'submit'));
							echo ' '.__('Не активен').'<br>';
							 
						 }
						
				
						}
						
					echo Form::close();	
					
					echo Form::open('rmo/opengate_unknow');
					
					echo Form::hidden('id_garage', Arr::get($garage_info, 'ID'));
					echo Form::hidden('place_for_open', Session::instance()->get('place_for_search'));
					echo Form::hidden('parking_for_open', Arr::get(Arr::get($place_income_garage, Session::instance()->get('place_for_search')), 'ID_PARKING'));
					//разрешить въезд неизвестным ГРЗ
					
					
					echo Form::input('unknow_plate_for_insert', '1', array( 'type'=>'text', 'maxlength'=>'10'));
					echo ' ';
					echo Form::button('todo', 'Вставка неизвестного ГРЗ для проезда', array('value'=>'insert_unknow_plate','class'=>'btn btn-primary', 'type' => 'submit'));
					echo '<br>(не более 10 символов)';
					echo Form::close();	
					
				echo '</td>';
							
								
				echo '<td>'; //echo Debug::vars('50', $place_income_garage); список машиномест
				$total_place=0;
				$total_place=count($place_income_garage);
					echo __('Всего машиномест ').' '.$total_place.'<hr>';
					//echo Debug::vars('94', $place_income_garage);
					$parking_name='189-189';
					
					 
					 echo Arr::get($value, 'PLACENUMBER').' ('.Arr::get($value, 'PARKING_NAME').')<br>';
					
				echo '</td>';
				
				echo '<td>';
				
					//echo Debug::vars('52', count($get_grz_in_parking), $get_grz_in_parking);
					$occuped_place=0;
					$occuped_place=count($get_grz_in_parking);
					 echo __('Занято мест ').$occuped_place.'<hr>';
					
					foreach($get_grz_in_parking as $key=>$value)
					{
						echo $key.' ('.Arr::get($value,'ENTER_TIME').')<br>';
					}
					
				echo '</td>';
				
				echo '<td>'.($total_place - $occuped_place).'</td>';
			echo '</tr>';
		?>
		</tbody>
	</table>
	
<?php
	}
?>
</div>	
</div>
	
 <div class="panel panel-primary"> 

  <div class="panel-heading">
    <h3 class="panel-title"><?php echo __('Панель управления и контроля воротами').' '.Session::instance()->get('place_for_search');?></h3>
    <h3 class="panel-title"><?php //echo __('Разрешить проезд выбранного ГРЗ на машиноместо').' '.Session::instance()->get('place_for_search');?></h3>
  </div>
  <div class="panel-body"> 
	<?php
		//получаю список ворот gate.
		$_gateList=Arr::get(Model::factory('gates')->get_list_gate(), 'res');
		//проверяю: работает ли ffmpeg?
$processName = "ffmpeg.exe";
exec("tasklist | findstr \"$processName\"", $output, $returnCode);
if (!empty($output)) {
    //echo "Процесс $processName работает!";
	$video_set=true;
} else {
    //echo "Процесс $processName не найден.";
	$video_set=false;
}

//отображаю состояние работы видеосистемы
if($video_set)
{
						echo '<span class="label label-success" id="inputField" title="Видеосистема работает правильно.">Видео работает</span>';
					} else {
						
						echo '<span class="label label-warning" title="Видеосистема не работает, надо запустить ffmpeg">Видео не работает</span>';
					}
	?>
	




	<div class="container">
	 <table>
		<tr>
			<?php
			$order_gate=array(3, 4, 2, 7, 5, 6);//порядок вывода ворот на экран
					//foreach(array_slice($_gateList, 0, 6) as $key)
				foreach($order_gate as $key2)// для ворот в указанном порядке организую вывод информации. $key2 - id ворот
				{
					$key=array();
					foreach($_gateList as $key3)//делаю перебор массива с перечнем ворот. Цель - найти $key3, у которого номер ворот совпадает с тем, что надо выводить.
					{
						if(Arr::get($key3, 'id') == $key2) $key=$key3; 
					}
				
				echo '<td>';
				
				
				echo '<div class="camera-view">';
				echo '<h3>'.Arr::get($key, 'name').'</h3>';
				if($video_set)
				{
					echo '<div class="video-wrapper">
						<video id="camera'.Arr::get($key, 'id_cam').'" controls muted></video>
						</div>';
				} else {
					echo __('no_video');
				}
				
				echo '</div>
				<div class="controls">
				
					<button onclick="toggleFullscreen(\'camera'.Arr::get($key, 'id_cam').'\')">Полный экран Камера '.Arr::get($key, 'id_cam').'</button>';
					
					echo Form::open('rmo/sendOpen');
						echo Form::hidden('id', Arr::get($key, 'id')).'<br>';
						echo '<label class="btn btn-line dark btn-xs popup-contact btn-success" for="modalm-1"
							org_name="'. Arr::get($key, 'name').'" 
							org_id="'.Arr::get($key, 'id').'"
							
						>Открыть ворота '.Arr::get($key, 'id').'</label>';
						
						echo Form::close();
					echo '
					
				</div>
			</td>';
					
				}
				?>
		</tr>
	</table>
   </div>
  </div>
 </div>
 
 
  <div class="panel panel-primary"> 

	  <div class="panel-heading">
		<h3 class="panel-title"><?php echo __('Панель управления и контроля воротами').' '.Session::instance()->get('place_for_search');?></h3>
		<h3 class="panel-title"><?php //echo __('Разрешить проезд выбранного ГРЗ на машиноместо').' '.Session::instance()->get('place_for_search');?></h3>
	  </div>
	  <div class="panel-body"> 
		Журнал событий
		<?php
			//таблица с журналом событий формируется в другом файле
			include Kohana::find_file('views/monitor','list');//вывод таблицы с журналом событий
		
		?>
     </div>
 </div>
  

<div class="modalm">


	<div class="panel-body">
	<input class="modalm-open" id="modalm-1" type="checkbox" hidden>
	<div class="modalm-wrap" aria-hidden="true" role="dialog">
		
		<div class="modalm-dialog">
			<div class="modalm-header">
				<h2>Управление воротами</h2>
				<div class="row">
					<div class="kartka">
					  <h1></h1>
						
					  </div>
				</div>
				<label class="btnm-close" for="modalm-1" aria-hidden="true">x</label>
			</div>
			<div class="modalm-body">
			<h2>Укажите причину</h2>
			
				
				
			<form action="rmo/sendOpen" method="post" enctype="multipart/form-data">
					
					<input type="input" name="mess" id="ipp" size="30" required >
					<input type="hidden" name="id" id="id_gate">
					
					<br>
					<br>
					<input type="submit" name="submit" value="Открыть ворота">
			</form> 
			 <br>
			  
		
			</div>
			<div class="modalm-footer">
				<h4>Артонит ПаркРезидент</h4>
				
			</div>
		</div>
	</div>
	</div>

</div>  

    <!-- Подключаем HLS.js -->
    <script src="/parkresident/hls.js"></script>
	
    
    <script>
        // Инициализация плееров
        function initCamera(videoId, streamUrl) {
            const video = document.getElementById(videoId);
            
            if (Hls.isSupported()) {
                const hls = new Hls();
                hls.loadSource(streamUrl);
                hls.attachMedia(video);
                hls.on(Hls.Events.MANIFEST_PARSED, function() {
                    video.play().catch(e => {
                        console.error("Автовоспроизведение запрещено:", e);
                        // Показываем кнопку воспроизведения
                        video.controls = true;
                    });
                });
            } else if (video.canPlayType('application/vnd.apple.mpegurl')) {
                // Для Safari
                video.src = streamUrl;
                video.addEventListener('loadedmetadata', function() {
                    video.play().catch(e => {
                        console.error("Автовоспроизведение запрещено:", e);
                        video.controls = true;
                    });
                });
            }
        }

        // Полноэкранный режим
        function toggleFullscreen(videoId) {
            const video = document.getElementById(videoId);
            if (video.requestFullscreen) {
                video.requestFullscreen();
            } else if (video.webkitRequestFullscreen) {
                video.webkitRequestFullscreen();
            } else if (video.msRequestFullscreen) {
                video.msRequestFullscreen();
            }
        }

        // Инициализация при загрузке страницы
        document.addEventListener('DOMContentLoaded', function() {
            // Замените URL на ваши HLS-потоки
            initCamera('camera1', 'http://172.16.20.252:8080/stream/stream6.m3u8');
            initCamera('camera2', 'http://172.16.20.252:8080/stream/stream8.m3u8');
            initCamera('camera3', 'http://172.16.20.252:8080/stream/stream9.m3u8');
            initCamera('camera4', 'http://172.16.20.252:8080/stream/stream10.m3u8');
            initCamera('camera5', 'http://172.16.20.252:8080/stream/stream11.m3u8');
            initCamera('camera6', 'http://172.16.20.252:8080/stream/stream7.m3u8');
           
        });
    </script>
