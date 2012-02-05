<?php
namespace IckleMVC\Models;
class Content_Data_Blog_Collection extends Content_Data_Contents{
	
	public function __construct( \IckleMVC\Registry\IckleRegistry $registry )
	{
		$this->registry = $registry;
	}
	
	public function getSQL( $conditions='' )
	{
		$curtime = date('Y-m-d H:i:s');
		$fields = " ";
		$tables = ", content_versions_blog_entries b ";
		$joins = "";
		$conditions = " AND b.ID=v.ID AND c.active=1 AND v.publication_timestamp >= '" . $curtime . "'" . $conditions;
			
		return parent::generateSQL( $fields, $tables, $joins, $conditions, 'blog' );
			
	}
	
	
}