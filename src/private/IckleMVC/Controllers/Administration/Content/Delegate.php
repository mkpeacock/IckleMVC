<?php
namespace IckleMVC\Controllers;
class Administration_Content_Delegate extends Controller{
	
	protected $registry;
	
	private $contentTypes = array();
	
	public function __construct( \IckleMVC\Registry\IckleRegistry $registry, $autoProcess=true )
	{
		$this->registry = $registry;
		$this->delegate();
	}
	
	private function delegate()
	{
		$bit = $this->registry->getObject('urlprocessor')->getURLBit( 1 );
		$bit = ( $bit == '' ) ? 'pages' : $bit;
		$class = '\IckleMVC\Controllers\Administration_Content_' . ucfirst( strtolower( $bit ) );
		$delegate = new $class( $this->registry );
	}
	
	
	
	
	
	
	
	
	
}


?>