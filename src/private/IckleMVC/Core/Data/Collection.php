<?php
namespace IckleMVC\Core\Data;

abstract class Collection implements \IteratorAggregate, \Countable{
	
	protected $objects = array();
    
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
	
	public function pop()
	{
		if( count( $this->objects ) > 0 )
		{
			return $this->objects[0];
		}
		else
		{
			throw new \Exception("Empty collection: nothing to pop");		
		}
	}
    
}
?>