<?php
namespace IckleMVC\Views;
abstract class Hooks_Abstract {

	protected $registry;
	
	public function __construct( \IckleMVC\Registry\IckleRegistry $registry )
	{
		$this->registry = $registry;
	}	
	
	abstract protected function preParseHook();
	
}

?>