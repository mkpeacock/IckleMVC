<?php

namespace IckleMVC\Models;
/**
 * Settings Data Collection
 * stores a collection of Settings_Data_Model objects
 * @author Michael Peacock
 */
class Settings_Data_Collection extends Data_Collection{
	
	/**
	 * Core SQL statement for retrieveing settings from the database
	 * @var String
	 */
	private $coreSQL = "SELECT * FROM settings WHERE ";
	
	/**
	 * Get core settings from the database
	 * @return void
	 */
	public function buildCoreSettings()
	{
		$this->buildFromSQL( $this->coreSQL . " core=1 " );
	}
	
	/**
	 * Build related settings from the database
	 * @param String $prefix the settings prefix
	 * @return void
	 */
	public function buildRelatedSeetings( $prefix )
	{
		
	}
	
	/**
	 * Build our collection of objects from an SQL statement
	 * @param String $sql
	 * @return void
	 */
	protected function buildFromSQL( $sql )
	{
		$this->registry->getObject('db')->executeQuery( $sql );
		if( $this->registry->getObject('db')->getNumRows() > 0 )
		{
			while( $row = $this->registry->getObject('db')->getRows() )
			{
				$object = new Settings_Data_Model( $this->registry, 0 );
				foreach( $row as $field => $property )
				{
					$method = "set" . ucfirst( $object->fieldNameToProperty( $field ) );
					$object->$method( $property );
				}
				$this->add( $object );
			}
		}
	}
	
	
}

?>