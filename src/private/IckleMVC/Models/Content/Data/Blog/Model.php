<?php
namespace IckleMVC\Models;
/**
 * Blog entry model
 * 
 * @author Michael Peacock
 */
class Content_Data_Blog_Model extends Content_Data_Content {
	

	/**
	 * Blog entry model constructor
	 * @param IckleRegistry $registry
	 * @param int $id the ID of the blog entry [if known]
	 * @param String $path the path of the blog entry [if known]
	 * @return void
	 */
	public function __construct( \IckleMVC\Registry\IckleRegistry $registry, $id=0, $path='' )
	{
		$this->registry = $registry;
		
		$fields = "";
		$tables = "";
		$joins = "";
		$conditions = "";
			
		if( $path != '' )
		{
			$sql = parent::generateSQL( $fields, $tables, $joins, $conditions, 'page', 0, $path );
			$this->registry->getObject('db')->executeQuery( $sql );
			if( $this->registry->getObject('db')->getNumRows() == 1 )
			{
				$data = $this->registry->getObject('db')->getRows();
				parent::__construct( $this->registry, $data );		
			}
		}
	}
	
	// Get a collection of comments which have been posted on the entry
	public function getComments()
	{
		return array(); 
	}
	
	
	
	
}



?>