<?php
/**
 * IckleMVC Abstract Scope Class
 * 
 * @author Michael Peacock
 * @copyright Michael Peacock
 */
abstract class Scope {
		
	/**
	 * Reference to the registry object
	 */
	protected $registry;
	
	protected $id;
	
	protected $reference;
	
	protected $typeId;
	
	protected $typeReference;
	
	protected $typeName;
	
	//abstract public function newConnection( $host, $user, $password, $database );
    
	
	public function getID()
	{
		return $this->id;
	}
	
	public function getReference()
	{
		return $this->reference;
	}
	
	public function getTypeReference()
	{
		return $this->typeReference;
	}
	
	
	
}

?>