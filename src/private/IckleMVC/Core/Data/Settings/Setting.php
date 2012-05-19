<?php

namespace IckleMVC\Core\Data\Settings;
use IckleMVC\Core\Data\Model;
/**
 * Settings_Data_Model class
 * represents a record from the settings table in object form
 * @author Michael Peacock
 */
class Setting extends Model {
	
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