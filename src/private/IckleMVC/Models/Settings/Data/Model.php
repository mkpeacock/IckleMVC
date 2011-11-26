<?php

namespace IckleMVC\Models;
/**
 * Settings_Data_Model class
 * represents a record from the settings table in object form
 * @author Michael Peacock
 */
class Settings_Data_Model extends Data_Model {
	
	/**
	 * Constructor
	 * @param \IckleMVC\Registry\IckleRegistry $registry
	 * @param int $ID
	 * @return void
	 */
	public function __construct( \IckleMVC\Registry\IckleRegistry $registry, $ID=0 )
	{
		$this->registry = $registry;
		if( $ID > 0 )
		{
			$this->buildFromSQL("SELECT * FROM settings WHERE id={$ID} LIMIT 1");
		}
	}
	
	
	
	
	
}



?>