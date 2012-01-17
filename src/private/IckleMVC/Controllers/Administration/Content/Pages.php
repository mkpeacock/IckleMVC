<?php
namespace IckleMVC\Controllers;
/**
 * Page controller
 * @author Michael Peacock
 * @copyright IckleMVC
 * @package IckleMVC/Controllers
 * @subpackage Administration
 */
class Administration_Content_Pages extends Controller {
	
	protected $registry;
	
	public function __construct( \IckleMVC\Registry\IckleRegistry $registry, $autoProcess=true )
	{
		$this->registry = $registry;
		$this->listPages();
	}
	
	
	public function listPages()
	{
		$pages = new \IckleMVC\Models\Content_Data_Pages( $this->registry );
		$this->loadView( 'administration_content_pages_list' )->generate( $pages );
	}
	
	
	
}


?>