<?php
namespace IckleMVC\Core;
abstract class AbstractView {
	
	protected $registry;
	protected $templateEngine;
	protected $frontMenu;
	private $applicationView=null;
	
	public function __construct( \IckleMVC\Registry\IckleRegistry $registry, $applicationView=null)
	{
		$this->registry = $registry;
		$this->templateEngine = $this->registry->getObject('template');
		$this->frontMenu = $this->registry->getObject('frontmenu');
		$this->applicationView = $applicationView;
	}
	
	
	abstract public function generate( $model=null );
	
	protected function output()
	{
		if( is_object( $this->applicationView ) )
		{
			$applicationView->preParseHook();
		}
		$this->templateEngine->parseOutput();
		print $this->templateEngine->getPage()->getContentToPrint();
		exit();
	}
	
	
}