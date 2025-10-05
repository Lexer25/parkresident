<?php 

/** 02.08.2025 автоматизированное тестироание парковочной системы
*
*/
  
class parkResidentTest extends UnitTest_TestCase 
{ 
	function providerBBB ()
	{
		return array (
			array ('12L', 3, 2),
			array ('3Д', 3,2),
			array ('001', 3,2),
			array ('0001', 3,2),
			
		); 
	}
	
	function providerAAA ()
	{
		return array (
			array (2, 2),
		); 
	}
	
	/**
     * @dataProvider providerBBB
     */
	
	public function testBBB($a, $b, $c) 
    {
		///$name, $lenght=3, $charSubstr='0'
		echo Model::factory('place')->nameNormailze($a);
		
    } 
	
	
	
}