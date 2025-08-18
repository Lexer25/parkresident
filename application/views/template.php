<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

   

    <title>Artonit Парк ЖК <?
		echo  isset(Kohana::$config->load('artonitparking_config')->city_name)? Kohana::$config->load('artonitparking_config')->city_name : '';
		echo isset($title)? $title : '';
		?></title>

    <!-- Bootstrap core CSS -->
    <?= HTML::style('static/css/bootstrap.css'); ?>
	<?= HTML::style('static/css/modal.css'); ?>
    <?//= HTML::style('static/css/admin.css'); ?>
	<?//= HTML::style('static/css/timesheet.css'); ?>
	<?= HTML::style('static/css/city.css'); ?>
	<?//= HTML::style('static/css/modal.css'); ?>
	<link rel="stylesheet" href="/parkresident/static/css/themes/blue/style.css" type="text/css" />
	
	 
<!-- ... -->
  <!-- 1. Подключить библиотеку jQuery -->
  <!-- <script type="text/javascript" src="/city/static/js/jquery-1.11.1.min.js"></script>  --> 
   <script type="text/javascript" src="/parkresident/static/js/jquery-2.2.4.js"></script>
    
	<!-- Подключить скрипта для монитора онлайн -->  
    <!--<script type="text/javascript" src="/parkresident/static/js/monitor_online.js"></script>-->
 
  
  <!-- 2. Подключить скрипт moment-with-locales.min.js для работы с датами -->
  <script type="text/javascript" src="/parkresident/static/js/moment-with-locales.min.js"></script>
  <!-- 3. Подключить скрипт платформы Twitter Bootstrap 3 -->
  <script type="text/javascript" src="/parkresident/static/js/bootstrap.min.js"></script>
  <!-- 4. Подключить скрипт виджета "Bootstrap datetimepicker" -->
  <script type="text/javascript" src="/parkresident/static/js/bootstrap-datetimepicker.min.js"></script>
  <!-- 5. Подключить CSS платформы Twitter Bootstrap 3 -->  
  <link rel="stylesheet" href="/parkresident/static/css/bootstrap.min.css" />
  <!-- 6. Подключить CSS виджета "Bootstrap datetimepicker" -->  
  <link rel="stylesheet" href="/parkresident/static/css/bootstrap-datetimepicker.min.css" />
  

	<script type="text/javascript" src="/parkresident/static/js/sort/jquery.tablesorter.js"></script>
	

 <style>
       
       .input-container {
            position: relative;
            margin-bottom: 40px;
        }
        .error {
            border: 1px solid red;
        }
        .tooltip {
            position: absolute;
            background-color: #ff4444;
            color: white;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 12px;
            top: -35px;
            left: 0;
            white-space: nowrap;
            opacity: 0;
            transition: opacity 0.3s;
            pointer-events: none;
        }
        .tooltip.active {
            opacity: 1;
        }
        .tooltip:after {
            content: "";
            position: absolute;
            top: 100%;
            left: 10px;
            border-width: 5px;
            border-style: solid;
            border-color: #ff4444 transparent transparent transparent;
        }
    </style>

	
  </head>

  <body>

  <!--container-fluid -->
<div class="container">
	<div class="row">
   		<?php
			include Kohana::find_file('views','top_menu');
			
			include Kohana::find_file('views','alert_line');
			
			echo $content;?>
			<button onclick="topFunction()" id="myBtn" title="Go to top"><?php echo __('top'); ?></button> 
	</div>
	<?php echo Kohana::VERSION(); ?>
</div>  

	

  <script type="text/javascript">
		  window.onscroll = function() {scrollFunction()};

		function scrollFunction() {
			if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
				document.getElementById("myBtn").style.display = "block";
			} else {
				document.getElementById("myBtn").style.display = "none";
			}
		}

		// When the user clicks on the button, scroll to the top of the document
		function topFunction() {
			document.body.scrollTop = 0; // For Safari
			document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
		}
	$(document).ready(function() {
  var navbarHeight = $('.navbar-fixed-top').outerHeight();
  $('body').css('padding-top', navbarHeight + 20);
});
	</script>
<script>
        document.addEventListener('DOMContentLoaded', function() {
            const number1 = document.getElementById('number1');
            const number2 = document.getElementById('number2');
            const tooltip1 = document.getElementById('tooltip1');
            const tooltip2 = document.getElementById('tooltip2');
            const form = document.getElementById('myForm');
            
            function showTooltip(tooltip) {
                tooltip.classList.add('active');
            }
            
            function hideTooltip(tooltip) {
                tooltip.classList.remove('active');
            }
            
            function validateNumbers() {
                const value1 = parseFloat(number1.value);
                const value2 = parseFloat(number2.value);
                
                if (isNaN(value1) || isNaN(value2)) {
                    hideTooltip(tooltip1);
                    hideTooltip(tooltip2);
                    number1.classList.remove('error');
                    number2.classList.remove('error');
                    return true;
                }
                
                if (value1 >= value2) {
                    showTooltip(tooltip1);
                    showTooltip(tooltip2);
                    number1.classList.add('error');
                    number2.classList.add('error');
                    return false;
                } else {
                    hideTooltip(tooltip1);
                    hideTooltip(tooltip2);
                    number1.classList.remove('error');
                    number2.classList.remove('error');
                    return true;
                }
            }
            
            // Проверка при изменении значений
            number1.addEventListener('input', validateNumbers);
            number2.addEventListener('input', validateNumbers);
            
            // Показываем tooltip при фокусе, если есть ошибка
            number1.addEventListener('focus', function() {
                if (number1.classList.contains('error')) {
                    showTooltip(tooltip1);
                }
            });
            
            number2.addEventListener('focus', function() {
                if (number2.classList.contains('error')) {
                    showTooltip(tooltip2);
                }
            });
            
            // Проверка при отправке формы
            form.addEventListener('submit', function(e) {
                if (!validateNumbers()) {
                    e.preventDefault();
                }
            });
        });
    </script>
	
  </body>
</html>
