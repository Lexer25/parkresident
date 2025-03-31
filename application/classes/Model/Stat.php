<?php defined('SYSPATH') OR die('No direct access allowed.');

class Model_Stat extends Model
{

	public function reviewKeyCode($keycode)// преобразование  кода от вида ХХХ001А в длинное десятичное и в десятичное с запятой
	{
		 $post=Validation::factory(array('key'=>trim($keycode)));
		 $post->rule('key', 'not_empty')
			->rule('key', 'regex', array(':value', '/[A-F0-9]+/'));//'/[a-fA-F0-9]++$/iD'
		if($post->check())
			{
			 //echo Debug::vars('31', $keycode); exit;	
			$key=substr(Arr::get($post,'key'),0, 6);

			 $key_arr=str_split ($key);

			 $numReverse2=array('0', '8','4','C','2','A','6','E','1','9','5','D','3','B','7','F');


			 
			 $result1 = hexdec(Arr::get($numReverse2, hexdec(Arr::get($key_arr, 5)))
						 .Arr::get($numReverse2, hexdec(Arr::get($key_arr,4))))
						 .','. str_pad(hexdec(Arr::get($numReverse2,hexdec(Arr::get($key_arr, 3)))
						 .Arr::get($numReverse2, hexdec(Arr::get($key_arr, 2)))
						 .Arr::get($numReverse2, hexdec(Arr::get($key_arr, 1)))
						 .Arr::get($numReverse2, hexdec(Arr::get($key_arr, 
		0)))), 5, '0', STR_PAD_LEFT);

			
			 $result2 = str_pad(hexdec(Arr::get($numReverse2, 
					hexdec(Arr::get($key_arr, 5)))
						 .Arr::get($numReverse2, hexdec(Arr::get($key_arr, 4)))
						 .Arr::get($numReverse2, hexdec(Arr::get($key_arr, 3)))
						 .Arr::get($numReverse2, hexdec(Arr::get($key_arr, 2)))
						 .Arr::get($numReverse2, hexdec(Arr::get($key_arr, 1)))
						 .Arr::get($numReverse2, hexdec(Arr::get($key_arr, 
		0)))), 10, '0', STR_PAD_LEFT);
		$result=$result2.', '.$result1;
				
			} else {
				 //echo Debug::vars('60', $keycode); exit;
				$result='--';
				
			}
		 
		 

		return $result;
	}
	
	
	public function getParam ($data)
	{
		$res='';
		$res=substr($data, stripos ($data, '=')+1);
		return $res;
	}
	
	public function conversionCode ($card_code)//преобразонвие длинного десятичного числа в формат ХХХ001А
	{
		//$card_code=1832932;
		$numReverse2=array('0', '8','4','C','2','A','6','E','1','9','5','D','3','B','7','F');

			$key_arr=array_reverse (str_split (dechex ($card_code)));
		$res='';
		foreach ($key_arr as $val=>$value)
		{
			$res=$res.Arr::get($numReverse2, hexdec($value));
			
		}
					 ;
	return array('card'=>str_pad($res,6,0).'001A');
	}
	
	public static function checkBeforInsert($card)// ппроверка наличия этой карты в базе данных
	{
		//echo Debug::vars('80',$card);	exit;
		$sql='select count(*) from kp_cards kpc where kpc.cardcode=\''.$card.'\'';
		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->get('COUNT');
		$res=TRUE;
		if($query!=0)
		{
			//$validation->error($card, 'check_age'); 
			$res=FALSE;
		}			
		
			
		return $query==0;
	}
	
}
