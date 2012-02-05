<?php
namespace IckleMVC\Views;
class Blog_Listing extends AbstractView{
	
	
	public function generate( $collection=null )
	{
		$collection->buildFromPagination();
		if( count( $collection ) == 0 )
		{
			$this->registry->getObject('template')->buildFromTemplates( 'header.tpl.php', 'blog/empty.tpl.php', 'footer.tpl.php' );
		}
		else
		{
			$this->registry->getObject('template')->getPage()->addTag( 'list', $collection );
			$this->registry->getObject('template')->buildFromTemplates( 'header.tpl.php', 'blog/listing.tpl.php', 'footer.tpl.php' );
		}
	}
	
	
}