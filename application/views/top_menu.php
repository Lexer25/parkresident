<?php
	//echo Debug::vars('2#', $_SESSION);
	?>
<!-- Static navbar -->
 <div class="navbar navbar-default navbar-fixed-top disable">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</button>
		  <?= HTML::anchor('Dashboard', __('City'),  array('class'=>'navbar-brand')) ?>
    </div>
	<div class="navbar-collapse collapse">
		
				<ul class="nav navbar-nav">
					<li <?php if (Arr::get($_SESSION,'menu_active')=='garage') echo 'class="active"';?>><?php echo  HTML::anchor('parkingPlace', __('Паркинг'), array('class'=>'active')); ?></li>
					<li <?php if (Arr::get($_SESSION,'menu_active')=='placeList') echo 'class="active"';?>><?php  echo  HTML::anchor('place/list', __('Машиноместа'));?></li>
					<li <?php if (Arr::get($_SESSION,'menu_active')=='garage') echo 'class="active"';?>><?php echo  HTML::anchor('garage', __('Гараж'), array('class'=>'active')); ?></li>
					<li <?php if (Arr::get($_SESSION,'menu_active')=='event') echo 'class="active"';?>><?php  echo  HTML::anchor('rubic/event', __('События')); ?></li>
					<li <?php if (Arr::get($_SESSION,'menu_active')=='gate') echo 'class="active"';?>><?php  echo  HTML::anchor('gate/list', __('gate_menu')); ?></li>
					<li <?php if (Arr::get($_SESSION,'menu_active')=='grz') echo 'class="active"';?>><?php  echo  HTML::anchor('grz', __('grz')); ?></li>
					<li <?php if (Arr::get($_SESSION,'menu_active')=='rmo') echo 'class="active"';?>><?php  echo  HTML::anchor('rmo', __('Рабочее место охраны')); ?></li>
					<li <?php //if (Arr::get($_SESSION,'menu_active')=='monitor') echo 'class="active"';?>><?php  //echo  HTML::anchor('monitor', __('Монитор')); ?></li>
					<!--<li <?php if (Arr::get($_SESSION,'menu_active')=='grztest') echo 'class="active"';?>><?php  echo  HTML::anchor('grz/test', __('grztest')); ?></li> -->
					<li <?php echo 'class="active"';?>><?php  echo  HTML::anchor('checkdb', __('checkDB')); ?></li>
					<li <?php echo 'class="active"';?>><?php  echo  HTML::anchor('residentPlace', __('residentPlace')); ?></li>
					<li <?php echo 'class="active"';?>><?php  echo  HTML::anchor('parkingPlace', __('parking')); ?></li>
					<li <?php echo 'class="active"';?>><?php  echo  HTML::anchor('place', __('Машиноместо')); ?></li>
			   </ul>
		<?php
		if(Auth::Instance()->logged_in())
		{?>
		<?php };?>

		<ul class="nav navbar-nav navbar-right">
	
		<li>
		<?php 
			if(Kohana::$config->load('artonitparking_config')->direct_gate_control)
			{
					echo Form::open('rmo/control');
					 echo  Form::button('todo', __('-1'), array('value'=>'open_gate_1', 'class'=>'btn btn-info btn-sm', 'type' => 'submit'));
					 echo ' ';
					 echo  Form::button('todo', __('-2'), array('value'=>'open_gate_2','class'=>'btn btn-info btn-sm', 'type' => 'submit'));
					 echo  Form::hidden('action', Request::current()->controller().'/'.Request::current()->action().' '.Request::current()->directory());
					echo Form::close();
			}
			?>			
		</li>
		<li>
					<div class="navbar-collapse collapse">
					<?php
					//echo Debug::vars('35', $_SESSION);
					if (Arr::get($_SESSION,'checkplaceenable')>0)
					{
						echo Form::button('todo', __('Контроль свободных мест включен!'), array('class'=>'btn btn-success btn-sm', 'type' => 'submit'));	
					} else {
						
						echo Form::button('todo', __('Контроль свободных мест выключен!'), array('class'=>'btn btn-danger btn-sm', 'type' => 'submit'));	
					}
					
					?>
	
				</div>
            
		</li>
			<li>
			<?
			//echo Debug::vars('5.05.2017 Пример подготовки пароля для 123', Auth::instance()->hash_password('123'));
					
			if(Auth::Instance()->logged_in())
			{
				echo 'Пользователь '.Auth::instance()->get_user();
					echo '<div>'.HTML::anchor('logout?action='.Request::current()->controller().'/'.Request::current()->action().'/'.Request::current()->param('id'), __('logout'), array('onclick' => 'return confirm(\'' . __('confirm.delete').'\')')).'</div>';
			} else {
			echo Form::open('login', array('method' => 'post', 'class'=>'form-inline'));
			echo  Form::hidden('action', Request::current()->controller().'/'.Request::current()->action().'/'.Request::current()->param('id'));
			?>
				<div class="form-group">
					<label for="inputEmail" class="sr-only">Имя</label>
					<input type="text" class="form-control input-sm" id="inputEmail" placeholder="Имя" name="username">
					
				</div>
				<div class="form-group">	    
					<label for="inputPassword" class="sr-only">Пароль</label>
					<input type="password" class="form-control input-sm" id="inputPassword" placeholder="Пароль" name="password">
				</div>
				<div class="checkbox input-sm">
						<label><input type="checkbox" name="remember"> Запомнить</label>
				</div>
					<button type="submit" class="btn btn-primary input-sm">Войти</button>
			<?echo Form::close();
			}
			echo __('Вер.').' '.(Kohana::$config->load('artonitparking_config')->ver);
		?>
			</li>
		</ul>
						
    </div>

	<div class="navbar-collapse collapse">
      <?php 
	  
	  if(Auth::Instance()->logged_in())
	  {
      echo __('string_about', array(
      		'db'=> Arr::get(
      			Arr::get(
      					Kohana::$config->load('database')->fb,
      					'connection'
      					),
      		'dsn'),
      		'ver'=> Kohana::$config->load('artonitparking_config')->ver,
      		'developer'=> Kohana::$config->load('artonitparking_config')->developer,
      		)).'<br>';
			echo __('timerefresh', array ('tr'=> date("d.m.Y H:i",time())));
			echo '<br>'.__('Роль Администратор');
	  } else {
		  echo __('Роль Контролёр');
	  }
/* 	  echo Debug::vars(
    Log::EMERGENCY, 1
    Log::ALERT, 1
    Log::CRITICAL, 1
    Log::ERROR, 4
    Log::WARNING, 5
    Log::NOTICE, 6
    Log::INFO, 6
    Log::DEBUG 6)  */
 
      ?>
	  </div>
</div>
