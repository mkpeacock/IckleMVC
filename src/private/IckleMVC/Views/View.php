<?php

namespace IckleMVC\Views;

abstract class View {
	
	protected $registry;
	
	public function __construct( \IckleMVC\Registry\IckleRegistry $registry )
	{
		$this->registry = $registry;
	}
	
	
}


?>