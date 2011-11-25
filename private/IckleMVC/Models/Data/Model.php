<?php
namespace IckleMVC\Models;
/**
 * Abstract model class
 * @author Michael Peacock
 */
abstract class Data_Model extends Data_Save{
	
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
	
	protected $primaryKeyField = null;
	protected $table;
		
	/**
	 * Constructor
	 * @param IckleRegistry $registry
	 * @param mixed $ID
	 * @return void
	 */
	abstract public function __construct( \IckleMVC\Registry\IckleRegistry $registry, $ID=0 );
	
	protected function setPrimaryKeyField( $pkf='id' )
	{
		$this->primaryKeyField = $pkf;
	}
	
	protected function setTable( $table='' )
	{
		$this->table = $table;
	}
	
	protected function buildFromQuery( $sql )
	{
		$this->registry->getObject('db')->executeQuery( $sql );
		if( $this->registry->getObject('db')->getNumRows() == 1 )
		{
			$this->valid = true;
			$row = $this->registry->getObject('db')->getRows();
			foreach( $row as $field => $data )
			{
				$this->fieldNameToProperty( $field );
				$this->$field = $data;
			}
		}
	}
	
	/**
	 * Save the model 
	 * @return void
	 */
	public function save()
	{
		$pkf = $this->primaryKeyField;
		$this->resanitize();
		
		if( null != $pkf && intval( $this->$pkf ) > 0 )
		{
			// update
			if( is_numeric( $this->$pkf ) && $this->$pkf === intval( $this->$pkf ) )
			{
				// its an int
				$this->registry->getObject('db')->updateRecords( $this->table, $this->data, "{$this->primaryKeyField}={$this->$pkf}" );
			}
			else
			{
				// its a string
				$this->registry->getObject('db')->updateRecords( $this->table, $this->data, "{$this->primaryKeyField}='{$this->$pkf}'" );
			}
		}
		else
		{
			// insert
			$this->registry->getObject('db')->insertRecords( $this->table, $this->data );
		}
	}
	
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
	
	/**
	 * Overloading isset
	 * @param String $name the name of the field
	 * @return bool
	 */
	public function __isset( $name )
	{
		return isset( $this->data[ $name ] );
	}
	
	/**
	 * Call overloading: currently only for getProperty(), and setProperty( $value ) methods
	 * @param String $name
	 * @param array $arguments
	 * @return mixed
	 */
	public function __call( $name, $arguments )
	{
		
		if( strlen( $name ) > 3 )
		{
			$initial = substr( $name, 0, 3 );
			$remainder = $this->propertyFromMethod( $name );
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
	 * Convert a field name to a property name
	 * @param String $name the name of the database field e.g. my_database_field
	 * @return String the name of the property e.g. myDatabaseField
	 */
	public function fieldNameToProperty( $name )
	{
		$property = implode( ( array_map( 'ucfirst', explode( '_', $name ) ) ) );
		return lcfirst( $property );	
	}
		
	/**
	 * Converts a property name into a field name
	 * @param String $property the name of the object property e.g. id, name, someProperty
	 * @return String e.g. id, name, some_property
	 */
	public function propertyToFieldName( $property )
	{
		return strtolower( preg_replace("/([A-Z])/",'_\\1',$property) );
	}
	
	/**
	 * Get the name of an object property from a method name, pased from the __call() method above
	 * @param String $methodName
	 * @return String
	 */
	private function propertyFromMethod( $methodName )
	{
		return lcfirst( substr( $methodName, 3, strlen( $methodName ) ) );
		
	}
	
	/**
	 * Lower cases the first letter of a string
	 * - look at moving this out into a static class
	 * @param String $str
	 * @return String
	 */
	private function lcfirst( $str )
	{
		return strtolower( substr( $str, 0, 1 ) ) . substr( $str, 1 );
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