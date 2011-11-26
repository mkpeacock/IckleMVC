<?php

class Permission {
	
	private $registry;
	private $id;
	private $reference;
	private $description;
	
	public function __construct( IckleRegistry $registry, $id=0 )
	{
		
	}
	
	public function getReference()
	{
		return $this->reference;
	}
	
	
	
	
}

?>