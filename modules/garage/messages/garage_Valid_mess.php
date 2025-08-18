<?php defined('SYSPATH') or die('No direct script access.'); 
$messages = array(
   'name'   =>
      array(
         'not_empty'       => ':field Не может быть пустым',
		 'max_lenght'   => ':field не должно превышать :param2 символов',
		 'Model_garage::checkNameIsUnique' => 'Гараж с названием ":value" уже присутсвует в списке зарегистрированных гаражей. Регистрация не выполнена.',
      ),
  
	  
	  
);
 
return $messages;