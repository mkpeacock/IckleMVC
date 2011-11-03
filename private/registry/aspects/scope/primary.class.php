<?php

class Primaryscope extends Scope {
	
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