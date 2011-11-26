<?php
namespace IckleMVC\Models;

abstract class Data_Collection implements \IteratorAggregate, \Countable{
	
	protected $objects = array();

    public function __construct( \IckleMVC\Registry\IckleRegistry $registry )
    {
    	$this->registry = $registry;
    }
    
    public function getIterator()
    {
    	return new \ArrayIterator( $this->objects );
    }
    
    public function count()
    {
    	return count( $this->objects );
    }
    
    public function add( $object )
    {
    	$this->objects[] = $object;
    }
    
    abstract protected function buildFromSQL( $sql );
    
}
?>