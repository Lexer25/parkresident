<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <link rel="icon" href="http://localhost/parkoffice/favicon.ico" type="image/x-icon">

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
  
 
  
  
    

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="../../assets/js/html5shiv.js"></script>
      <script src="../../assets/js/respond.min.js"></script>
    <![endif]-->
    
    
   <!--  Скрипты для сортировки таблицы 
     <script type="text/javascript" src="/city/static/js/sort/jquery-latest.js"></script> --> 
	<script type="text/javascript" src="/parkresident/static/js/sort/jquery.tablesorter.js"></script>
	 
 <style>
   body { padding-top: 120px; }
  </style>
	


  </head>

  <body>

  <!--container-fluid -->

   		<?php
			include Kohana::find_file('views','top_menu');
			include Kohana::find_file('views','alert_line');
			echo $content;?>
			<button onclick="topFunction()" id="myBtn" title="Go to top"><?php echo __('top'); ?></button> 

	<?php echo Kohana::VERSION(); ?>
 

	

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
	
	</script>
  </body>
</html>
