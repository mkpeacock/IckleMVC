<?php
/**
 * Currency class
 * 
 * @author Michael Peacock
 */
class Currency{
	
	private $id;
	private $name;
	private $symbol;
	private $default;
	private $multiplier;
	
	public function __construct( IckleRegistry $registry, $id=0 )
	{
		
	}
	
	public function setID( $id )
	{
		$this->id = $id;
	}
	
	public function setName( $name )
	{
		$this->name = $name;
	}
	
	public function setSymbol( $symbol )
	{
		$this->symbol = $symbol;
	}
	
	public function setDefault( $default )
	{
		$this->default = $default;
	}
	
	public function setMultiplier( $multiplier )
	{
		$this->multiplier = $multiplier;
	}
	
	public function save()
	{
		
	}
	
	
}


?>