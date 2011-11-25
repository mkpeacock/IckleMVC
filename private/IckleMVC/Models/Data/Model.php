<?php
namespace IckleMVC\Models;
/**
 * Abstract model class
 * @author Michael Peacock
 */
abstract class Data_Model{
	
	/**
	 * Indicates if the object is a valid representation, so for database backed objects, if the record was found
	 * @var bool
	 */
	protected $valid=false;
	
	/**
	 * Core data supplied from the database
	 * @var array
	 */
	protected $data = array();
		
	/**
	 * Constructor
	 * @param IckleRegistry $registry
	 * @param mixed $ID
	 * @return void
	 */
	abstract public function __construct( \IckleMVC\Registry\IckleRegistry $registry, $ID=0 );
	
	/**
	 * Save the model 
	 * @return void
	 */
	abstract public function save();
	
	/**
	 * Returns if the model is valid or not
	 * @return bool
	 */
	public function isValid()
	{
		return $this->valid;
	}
	
	/**
	 * Sets the validity of the object
	 * @param bool $valid
	 * @return void
	 */
	public function setValid( $valid )
	{
		$this->valid = $valid;
	}
	
	public function __call( $name, $arguments )
	{
		
		if( strlen( $name ) > 3 )
		{
			$initial = substr( $name, 0, 3 );
			$remainder = $this->getProperty( $name );
			switch( $initial )
			{
				case 'set':
					$this->$remainder = $arguments[0];
					return $this;
					break;
				case 'get':
					return $this->$remainder;
					break;
			} 
		}
	}
	
	/**
	 * Get the name of an object property from a method name, pased from the __call() method above
	 * @param String $methodName
	 * @return String
	 */
	private function getProperty( $methodName )
	{
		$property = substr( $methodName, 3, strlen( $methodName ) );
		return strtolower( substr( $property, 0, 1 ) ) . substr( $property, 1 );
	}
	
	/**
	 * Overloader for setting properties
	 * @param String $name
	 * @param mixed $value
	 * @return void 
	 */
	public function __set( $name, $value )
	{
		$this->data[ $name ] = $value;
	}
	
	/**
	 * Overloader for getting properties
	 * @param String $name
	 * @return mixed
	 */
	public function __get( $name )
	{
		if( array_key_exists( $name, $this->data ) )
		{
			return $this->data[ $name ];
		}
	}
	
	
}

?>