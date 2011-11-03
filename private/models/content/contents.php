<?php

/**
 * Content S model
 */
class Contents{
	
	// time formats for query: escaped for sprintf
	private $timestampFormatter = '%%D %%M %%Y %%H:%%i';
	private $dateFormatter = '%%D %%M %%Y';
	private $pickerFormatter = '%%m/%%d/%%Y';
	private $timeFormatter = '%%H:%%i';
	
	/**
	 * Generate SQL statement
	 * Primarilly used by child content models, so they don't need to know the table structure
	 * @param String $fields
	 * @param String $tables
	 * @param String $joins
	 * @param String $conditions
	 * @param int $id
	 * @return String the SQL statement
	 */
	public function generateSQL( $fields='', $tables='', $joins='', $conditions='', $type, $deleted=0, $deletedOr=0 )
	{
		$this->defaultSQL = "SELECT 
								c.ID as s_content_id, 
								c.path as s_path, 
								c.active as s_active, 
								c.order as s_order, 
								c.parent as s_parent, 
								c.requires_authentication as s_requires_authenitcation, 
								c.created as s_content_created, 
								DATE_FORMAT( c.created,  '".$this->timestampFormatter."' ) as s_content_created_friendly,  
								c.type as s_content_type_id, 
								t.reference as s_content_type_reference,  
								t.name as s_content_type_name, 
								v.ID as s_version_id, 
								v.created as s_version_created, 
								DATE_FORMAT( v.created, '".$this->timestampFormatter."' ) as s_version_created_friendly,  
								v.name as s_name, 
								v.title as s_title, 
								v.heading as s_heading, 
								v.content as s_content, 
								v.publication_timestamp as s_publication_timestamp, 
								IFNULL( DATE_FORMAT( v.publication_timestamp, '".$this->dateFormatter."' ), 'N/A') as s_publication_friendly,  
								DATE_FORMAT( v.publication_timestamp, '".$this->pickerFormatter."' ) as s_publication_picker, 
								DATE_FORMAT( v.publication_timestamp, '".$this->timeFormatter."' ) as s_publication_time,  
								v.expiry_timestamp as s_expiry_timestamp, 
								IFNULL( DATE_FORMAT( v.expiry_timestamp, '".$this->dateFormatter."' ), 'N/A' ) as s_expiry_friendly,  
								DATE_FORMAT( v.expiry_timestamp, '".$this->pickerFormatter."' ) as s_expiry_picker, 
								DATE_FORMAT( v.expiry_timestamp, '".$this->timeFormatter."' ) as s_expiry_time, 
								IFNULL( ua.ID, 0 ) as s_content_creator_user_id, 
								IFNULL( ua.username, 'N/A') as s_content_creator_username, 
								IFNULL( ub.ID, 0 ) as s_version_creator_user_id, 
								IFNULL( ub.username, 0 ) as s_version_creator_username 
								%s 
							 FROM 
							 	%s 
							 	content_types t,  
							 	content_versions v 
							 		LEFT JOIN 
							 			users ub ON ( ub.ID=v.creator ), 
							 	content c 
							 		LEFT JOIN 
							 				users ua ON ( ua.ID=c.creator ) %s 
							 WHERE %s v.ID=c.current_version_id 
							 		AND t.ID=c.type 
							 		AND t.reference='%s' 
							 		AND ( c.deleted=%d OR c.deleted=%d ) 
							 ORDER BY c.`order` ASC";
							 
		return sprintf( $this->defaultSQL, $fields, $tables, $joins, $conditions, $type, $deleted, $deletedOr );
	}
	
}

?>