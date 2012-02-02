<?php
namespace IckleMVC\Views;
abstract class AbstractView {
	
	protected $registry;
	private $applicationView=null;
	
	public function __construct( \IckleMVC\Registry\IckleRegistry $registry, $applicationView=null)
	{
		$this->registry = $registry;
		$this->applicationView = $applicationView;
	}
	
	
	abstract public function generate( $model=null );
	
	protected function output()
	{
		if( is_object( $this->applicationView ) )
		{
			$applicationView->preParseHook();
		}
		$this->registry->getObject('template')->parseOutput();
		print $this->registry->getObject('template')->getPage()->getContentToPrint();
		exit();
	}
	
	
}