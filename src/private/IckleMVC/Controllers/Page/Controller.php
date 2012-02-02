<?php
namespace IckleMVC\Controllers;
use IckleMVC\Views as view;
/**
 * Page controller for basic front-end CMS functionality
 * @author Michael Peacock
 */
class Page_Controller{
	
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
			$page = new \IckleMVC\Models\Content_Data_Page( $this->registry, 0, $pagePath, ( $pagePath == '' ) ? true : false );
			if( $page->isPublished() )
			{
				if( ! $page->requiresAuthentication() || ( $page->requiresAuthentication() && $this->registry->getObject('autentication')->getUser()->isLoggedIn() ) )
				{
					$view = new view\Page_View( $this->registry );
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