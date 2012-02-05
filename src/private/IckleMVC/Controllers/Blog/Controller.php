<?php
namespace IckleMVC\Controllers;
use IckleMVC\Views as view;
/**
 * Blog controller for basic blog functionality
 * @author Michael Peacock
 */
class Blog_Controller extends Controller{
	

	/**
	 * Constructor
	 * @param IckleRegistry $registry
	 * @param bool $autoProcess
	 * @return void
	 */
	public function __construct( \IckleMVC\Registry\IckleRegistry $registry, $autoProcess=true )
	{
		$this->registry = $registry;
		if( $autoProcess )
		{
			$this->listEntries(0);
		}
	}
	
	private function rssFeed()
	{
		$blogListings = array();
		$view = new view\Blog_Rss( $this->registry );
		$view->generate( $blogListings );
	}
	
	private function listEntries( $page=0 )
	{
		$collection = new \IckleMVC\Models\Content_Data_Blog_Collection( $this->registry );
		$pagination = $collection->buildPagination( $page );
		
		$view = new view\Blog_Listing( $this->registry );
		$view->generate( $collection );
	}
	
	private function viewEntry( $entry )
	{
		$blog = new \IckleMVC\Models\Content_Data_Blog( $this->registry, 0, $pagePath );
		if( $blog->isPublished() )
		{
			$view = new view\Blog_View( $this->registry );
			$view->generate( $blog );
		}
		else
		{
			// 404
		}
	}
	
	
}


?>