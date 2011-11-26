<?php
namespace IckleMVC\Controllers;
class Administration_Content_Pages extends Controller {
	
	protected $registry;
	
	public function __construct( \IckleMVC\Registry\IckleRegistry $registry, $autoProcess=true )
	{
		$this->registry = $registry;
	}
	
	
	public function listPages()
	{
		
	}
	
	
	
}


?>