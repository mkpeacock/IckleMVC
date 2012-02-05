<?php
namespace IckleMVC\Controllers;
/**
 * Blog controller for basic blog functionality
 * @author Michael Peacock
 */
class Blog_Controller extends Controller{
	
	/**
	 * Reference to the registry object
	 * @var IckleRegistry
	 */
	private $registry;
	
	/**
	 * Constructor
	 * @param IckleRegistry $registry
	 * @param bool $autoProcess
	 * @return void
	 */
	public function __construct( IckleRegistry $registry, $autoProcess=true )
	{
		$this->registry = $registry;
		if( $autoProcess )
		{
			
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
	
	public function test()
	{
		
	}
	
	
}


?>