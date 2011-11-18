<?php
/**
 * Abstract model class
 * @author Michael Peacock
 */
abstract class Model{
	
	/**
	 * Indicates if the object is a valid representation, so for database backed objects, if the record was found
	 * @var bool
	 */
	protected $valid=false;
	
	/**
	 * Constructor
	 * @param IckleRegistry $registry
	 * @param mixed $ID
	 * @return void
	 */
	abstract public function __construct( IckleRegistry $registry, $ID=0 );
	
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
	
	
}

?>