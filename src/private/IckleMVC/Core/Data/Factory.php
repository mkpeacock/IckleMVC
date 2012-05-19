<?php
namespace IckleMVC\Core\Data;
abstract class Factory {
	
	protected $registry;
	
	public function __construct( $registry )
	{
		$this->registry = $registry;
	}
	
	abstract protected function buildFromSQL( $sql );
	
	
}
