<?php

namespace IckleMVC\Registry;
/**
 * Template engine
 * @author Michael Peacock
 * @copyright IckleMVC
 * @package IckleMVC/Registry
 * @subpackage Scope
 */
class Primaryscope extends \IckleMVC\Registry\Scope {
	
	/**
	 * Primary scope constructor
	 * @param IckleRegistry $registry
	 * @return void
	 */
	public function __construct( IckleRegistry $registry )
	{
		$this->registry = $registry;
		$this->id = 1;
		$this->reference = 0;
		$this->typeId = 1;
		$this->typeReference = 'primary';
		$this->typeName = 'Primary Scope';
	}
	
	
}


?>