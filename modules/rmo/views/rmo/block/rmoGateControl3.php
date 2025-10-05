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
            min-width: 200px;
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

 <div class="panel panel-primary"> 

  <div class="panel-heading">
    <h3 class="panel-title"><?php echo __('Панель управления воротами');?></h3>
   
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
/* if($video_set)
{
						echo '<span class="label label-success" id="inputField" title="Видеосистема работает правильно.">Видео работает</span>';
					} else {
						
						echo '<span class="label label-warning" title="Видеосистема не работает, надо запустить ffmpeg">Видео не работает</span>';
					} */
					echo '<span class="label label-warning" title="Видео отключено">Видео отключено</span>';
					
					
					//echo '<span class="label label-default pull-right" title="Порядок вывода настривается в файле parkresident\application\config\artonitparking_config.php переменная \'order_gate\'. Например: \'order_gate\'=>array(3, 4, 2, 7, 5, 6)"">Настройка</span>';
					echo '<acronym class="pull-right" title="Порядок вывода настривается в файле parkresident\application\config\artonitparking_config.php переменная \'order_gate\'. Например: \'order_gate\'=>array(3, 4, 2, 7, 5, 6)">Настройка</acronym>';
	?>


	<div class="container">
	
	 <table>
		<tr>
			<?php
			
			
			
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