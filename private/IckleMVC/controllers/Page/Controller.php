<?php
namespace IckleMVC\Controllers;
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
			$page = new \IckleMVC\Models\Content_Page( $this->registry, 0, $pagePath, ( $pagePath == '' ) ? true : false );
			if( $page->isPublished() )
			{
				if( ! $page->requiresAuthentication() || ( $page->requiresAuthentication() && $this->registry->getObject('autentication')->getUser()->isLoggedIn() ) )
				{
					$this->registry->getObject('template')->dataToTags( $page->getData(), 'page_' );
					$this->registry->getObject('template')->getPage()->setTitle( $page->getTitle() );
					// display the page
					$templateObject = $this->registry->getObject('template');
					call_user_func_array(array( $templateObject, "buildFromTemplates"), $page->getTemplateFiles() );
					
					//$this->registry->getObject('template')->buildFromTemplates( 'header.tpl.php', 'main.tpl.php', 'footer.tpl.php' );
					$this->registry->getObject('frontmenu')->buildMenu( $page->getID() );
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