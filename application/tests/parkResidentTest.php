<?php 

/** 02.08.2025 автоматизированное тестироание парковочной системы
*
*/
  
class parkResidentTest extends UnitTest_TestCase 
{ 
	function providerBBB ()
	{
		return array (
			array (2, 2),
			array (6, 2),
			array (2, 6),
			array (6, 6),
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
	
	public function testBBB($a, $b) 
    {
	
		$this->assertEquals($a, $b); 
		
    } 
	
	
	
}