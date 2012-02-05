<?php
namespace IckleMVC\Models;
class Content_Data_Blog_Collection extends Content_Data_Contents{
	
	public function __construct( \IckleMVC\Registry\IckleRegistry $registry )
	{
		$this->registry = $registry;
	}
	
	public function buildPagination( $page=0 )
	{
		$p = new \IckleMVC\Libraries\Pagination_Generator( $this->registry->getObject('db') );
		$p->setLimit(10);
		$p->setOffset( $page );
		$p->setQuery( $this->getSQL() );
		$p->generatePagination();
		$this->pagination = $p;
	}
	
	public function buildFromPagination()
	{
		$this->buildFromArray( $this->pagination->getResults(), new Content_Data_Blog_Model( $this->registry ) ); 
	}
	
	
	
	public function getSQL( $conditions='' )
	{
		$curtime = date('Y-m-d H:i:s');
		$fields = " ";
		$tables = " ";
		$joins = "";
		$conditions = " AND c.active=1 AND v.publication_timestamp <= '" . $curtime . "'" . $conditions;
			
		return parent::generateSQL( $fields, $tables, $joins, $conditions, 'blog' );
			
	}
	
	
}