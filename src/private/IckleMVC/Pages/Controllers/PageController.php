<?php
namespace IckleMVC\Pages\Controllers;
use IckleMVC\Pages\Views\View;
use IckleMVC\Pages\Models\Page;
/**
 * Page controller for basic front-end CMS functionality
 * @author Michael Peacock
 */
class PageController{
	
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
	public function __construct( \IckleMVC\Registry\IckleRegistry $registry, $autoProcess=true )
	{
		$this->registry = $registry;
		if( $autoProcess )
		{
			$pagePath = $this->registry->getObject('db')->sanitizeData( $this->registry->getObject('urlprocessor')->getURLPath() );
			$page = new Page( $this->registry, 0, $pagePath, ( $pagePath == '' ) ? true : false );
			if( $page->isPublished() )
			{
				if( ! $page->requiresAuthentication() || ( $page->requiresAuthentication() && $this->registry->getObject('autentication')->getUser()->isLoggedIn() ) )
				{
					$view = new View( $this->registry );
					$view->generate( $page );
				}
				else
				{
					// login page
				}
			}
			else
			{
				// 404
			}
		}
	}
	
	
}


?>