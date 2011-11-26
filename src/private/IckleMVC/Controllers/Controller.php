<?php
namespace IckleMVC\Controllers;
abstract class Controller{
	
	protected $view;
	protected $registry;
	
	abstract public function __construct( IckleRegistry $registry, $autoProcess=true );
	
	protected function loadView( $view )
	{
		$view = '\IckleMVC\Views\\' . ucwords( $view );
		$this->view = new $view( $this->registry );
		return $this->view;
	}
	
	
	
}

?>