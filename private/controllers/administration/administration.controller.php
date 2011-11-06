<?php
/**
 * Administration front controller
 * @author Michael Peacock
 * @copyright IckleMVC Project
 */
class AdministrationController{
	
	/**
	 * Registry object
	 * @var IckleRegistry
	 */
	private $registry;
	
	/**
	 * Valid administration sections
	 * @var array
	 */
	private $sections = array( 'dashboard', 'content', 'users' );
	
	/**
	 * Constructor
	 * @param IckleRegistry $registry
	 * @param bool $autoProcess
	 * @return void
	 */
	public function __construct( IckleRegistry $registry, $autoProcess=true )
	{
		$this->registry = $registry;
		if( $this->registry->getObject('authentication')->isLoggedIn() )
		{
			if( $this->registry->getObject('authentication')->getUser()->isAdministrator() )
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
	
	/**
	 * Request the user logs in
	 * @return void
	 */
	private function requestLogin()
	{
		$this->registry->getObject('template')->buildFromTemplates( 'login.tpl.php' );
	}
	
	/**
	 * Request the user logs in as an administrator
	 * @return void
	 */
	private function notAdministrator()
	{
		$this->registry->getObject('template')->buildFromTemplates( 'login.tpl.php' );
		$this->registry->notify('error', 'Access denied', 'Sorry, only administrators have permission to access the administration area');
	}
	
	/**
	 * User is logged in and is an administrator: process their request
	 * @return void
	 */
	private function process()
	{
		echo 'success';
	}
	
	
	
	
	
	
}


?>