<?php
namespace IckleMVC\Pages\Models;
use IckleMVC\Core\Data;
/**
 * Pagecontent model
 * 
 * @author Michael Peacock
 */
class Page extends Data\Content_Model {
	
	private $templateFiles;
	
	/**
	 * Page content model constructor
	 * @param IckleRegistry $registry
	 * @param int $id the ID of the page [if known]
	 * @param String $path the path of the page [if known]
	 * @param bool $pop - used to get the first in a list, e.g. viewing home page (no path)
	 * @return void
	 */
	public function __construct( \IckleMVC\Registry\IckleRegistry $registry, $id=0, $path='', $pop=false )
	{
		$this->registry = $registry;
		$defaultfiles = $this->registry->getObject('db')->sanitizeData( serialize( array( 'header.tpl.php', 'main.tpl.php', 'footer.tpl.php' ) ) );
		$fields = ", IFNULL( tplf.files, '" . $defaultfiles ."' ) as template_files ";
		$tables = " content_versions_pages p LEFT JOIN templates tpl ON (p.template=tpl.ID) LEFT JOIN templates_files tplf ON (tpl.file=tplf.ID), ";
		$joins = "";
		$conditions = " p.ID=v.ID AND ";
			
		if( $path != '' )
		{
			$sql = parent::generateSQL( $fields, $tables, $joins, $conditions, 'page', 0, $path );
			$this->registry->getObject('db')->executeQuery( $sql );
			if( $this->registry->getObject('db')->getNumRows() == 1 )
			{
				$data = $this->registry->getObject('db')->getRows();
				$this->templateFiles = unserialize( $data['template_files'] );
				parent::__construct( $this->registry, $data );		
			}
		}
		elseif( $pop )
		{
			// POP: we don't have a path so we are trying to view the home page! Pop from the top!
			$sql = parent::generateSQL( $fields, $tables, $joins, $conditions, 'page', 0, '', 1 );
			$this->registry->getObject('db')->executeQuery( $sql );
			if( $this->registry->getObject('db')->getNumRows() == 1 )
			{
				$data = $this->registry->getObject('db')->getRows();
				$this->templateFiles = unserialize( $data['template_files'] );
				parent::__construct( $this->registry, $data );		
			}
		}
	}
	
	public function getTemplateFiles()
	{
		return $this->templateFiles;
	}
	
	
	
	
}



?>