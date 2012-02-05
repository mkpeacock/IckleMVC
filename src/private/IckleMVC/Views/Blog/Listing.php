<?php
namespace IckleMVC\Views;
class Blog_Listing extends AbstractView{
	
	
	public function generate( $collection=null )
	{
		$collection->buildFromPagination();
		if( count( $collection ) == 0 )
		{
			
		}
		else
		{
			$this->registry->getObject('template')->buildFromTemplates( 'header.tpl.php', 'blog/empty.tpl.php', 'footer.tpl.php' );
		}
	}
	
	
}