<?php
namespace IckleMVC\Core\Data;
use IckleMVC\Core\Exception;
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
	
	public function pop()
	{
		if( count( $this->objects ) > 0 )
		{
			return $this->objects[0];
		}
		else
		{
			throw new EmptyCollectionException();		
		}
	}
    
}
?>