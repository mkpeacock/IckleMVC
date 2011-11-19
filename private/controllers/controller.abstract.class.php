<?php

abstract class Controller{
	
	protected $view;
	protected $registry;
	
	abstract public function __construct( IckleRegistry $registry, $autoProcess=true );
	
	protected function loadView( $view )
	{
		require_once( FRAMEWORK_PATH . 'views/' . $view . '.view.php' );
		$view = str_replace( '.', '', $view ) . 'View';
		$this->view = new $view( $this->registry );
		return $this->view;
	}
	
	
	
}

?>