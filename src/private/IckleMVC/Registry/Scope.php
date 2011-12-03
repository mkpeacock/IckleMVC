<?php
namespace IckleMVC\Registry;
/**
 * IckleMVC Abstract Scope Class
 * 
 * @author Michael Peacock
 * @copyright IckleMVC
 * @package IckleMVC/Registry
 * @subpackage Scope
 */
abstract class Scope {
		
	/**
	 * Reference to the registry object
	 */
	protected $registry;
	
	/**
	 * Scope ID
	 * @var int
	 */
	protected $id;
	
	/**
	 * Scope reference
	 * @var string
	 */
	protected $reference;
	
	/**
	 * Scope type ID number
	 * @var int
	 */
	protected $typeId;
	
	
	/**
	 * Reference of the type of scope
	 * @var string
	 */
	protected $typeReference;
	
	/**
	 * Scope type name
	 * @var string
	 */
	protected $typeName;

	/**
	 * Get the ID Number
	 * @var int
	 */
	public function getID()
	{
		return $this->id;
	}
	
	/**
	 * Get the reference
	 * @return string
	 */
	public function getReference()
	{
		return $this->reference;
	}
	
	/**
	 * Get the type reference
	 * @return string
	 */
	public function getTypeReference()
	{
		return $this->typeReference;
	}
	
	
	
}

?>