<?php
namespace IckleMVC\Core\Data;
/**
 * Data_Save class provides inheritable methods for using when saving to the database
 * @author Michael Peacock
 */
abstract class Save {
	
	/**
	 * Resanitize
	 * - this should be called first and foremost within any save() call by a Data_Model implementation
	 * - and potentially by any other models.  While we _assume_ data set from user input has been sanitized earlier
	 * - data which is populated direct from the database, and not updated as part of a user input may contain
	 * - data which should be sanitized before being part of an update.  E.g. If you toggle an objects active property, 
	 * - and call save() this will update all non ID fields, even though they didn't change. This method looks for any
	 * - unsanitized quotes, and sanitizes them. Nice.
	 * @return void
	 */
	protected function resanitize()
	{
		foreach( $this as $key => $data )
		{
			if( ! is_object( $data ) && ! is_array( $data ) && ! is_numeric( $data ) && preg_match( "/(?<!\\\\)'/", $data ) > 0 )
			{
				$this->$key = $this->registry->getObject('db')->sanitizeData( $data );
			}
		}
		$d = $this->data;
		foreach( $d as $key => $data )
		{
			if( ! is_object( $data ) && ! is_array( $data ) && ! is_numeric( $data ) && preg_match( "/(?<!\\\\)'/", $data ) > 0 )
			{
				$this->data[ $key ] = $this->registry->getObject('db')->sanitizeData( $data );
			}
		}
	}
	
	
	
	
}


?>