<?php
/**
 * Administration front controller
 * @author Michael Peacock
 * @copyright IckleMVC Project
 */
class AdministrationController{
	
	private $registry;
	private $sections = array( 'dashboard', 'content', 'users' );
	
	public function __construct( IckleRegistry $registry, $autoProcess=true )
	{
		$this->registry = $registry;
		if( $this->registry->getObject('authentication')->isLoggedIn() )
		{
			if( $this->registry->getObject('authentication')->getUser()->getAdministrator() )
			{
				$this->process();
			}
			else
			{
				$this->notAdministrator();
			}
		}
		else
		{
			$this->requestLogin();
		}
	}
	
	private function requestLogin()
	{
		$this->registry->getObject('template')->buildFromTemplates( 'login.tpl.php' );
	}
	
	private function notAdministrator()
	{
		
	}
	
	private function process()
	{
		
	}
	
	
	
	
	
	
}


?>