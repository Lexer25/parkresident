<?php
		//echo Debug::vars('2', $_SESSION);
		$e_mess=Validation::Factory(Session::instance()->as_array())
				->rule('e_mess','is_array')
				->rule('e_mess','not_empty')
				;
	
		if($e_mess->check())
		{
			$param='Ошибка!<br>';
			
			foreach(Arr::get($e_mess, 'e_mess') as $key=>$value)
			{
				$param.=$value.'<br>';
			}
			?>
			<div id="my-alert" class="alert alert-danger alert-dismissible" role="alert">
				<?php 
					echo $param;
				?>
				
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<?php
			
		} else 
		{
			
		}
		Session::instance()->delete('e_mess');
		
		$ok_mess=Validation::Factory(Session::instance()->as_array())
				->rule('ok_mess','is_array')
				->rule('ok_mess','not_empty')
				;
		if($ok_mess->check())
		{
	
			$param='Команда выполнена успешно<br>';
			foreach(Arr::get($ok_mess, 'ok_mess') as $key=>$value)
			{
				$param.=$value.'<br>';
			}
			?>
			<div id="my-alert" class="alert alert-success alert-dismissible" role="alert">
				<?php 
					echo $param;
				?>
				
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<?php
			
		} else 
		{
		
			
		}
		Session::instance()->delete('ok_mess');
		?>
		<script>
    $(function(){
        window.setTimeout(function(){
            $('#my-alert').alert('close');
        },5000);
    });
</script>