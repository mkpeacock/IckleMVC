<?php
namespace IckleMVC\Models;
/**
 * Page S model
 * 
 * @author Michael Peacock
 */
class Content_Pages extends Content_Contents {
	
	/**
	 * Page S content model constructor
	 * @param IckleRegistry $registry
	 */
	public function __construct( \IckleMVC\Registry\IckleRegistry $registry )
	{
		$this->registry = $registry;
	}
	
	public function getSQL( $conditions='' )
	{
		
		$defaultfiles = $this->registry->getObject('db')->sanitizeData( serialize( array( 'header.tpl.php', 'main.tpl.php', 'footer.tpl.php' ) ) );
		$fields = ", IFNULL( tplf.files, '" . $defaultfiles ."' ) as template_files ";
		$tables = " content_versions_pages p LEFT JOIN templates tpl ON (p.template=tpl.ID) LEFT JOIN templates_files tplf ON (tpl.file=tplf.ID), ";
		$joins = "";
		$conditions = " AND p.ID=v.ID " . $conditions;
			
		return parent::generateSQL( $fields, $tables, $joins, $conditions, 'page' );
			
	}
	
	
	
	
	
}



?>