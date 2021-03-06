<?php
namespace IckleMVC\Controllers;
/**
 * Administration front controller
 * @author Michael Peacock
 * @copyright IckleMVC Project
 * @package IckleMVC/Controllers
 * @subpackage Administration
 */
class Administration_Controller{
	
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
	public function __construct( \IckleMVC\Registry\IckleRegistry $registry, $autoProcess=true )
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
		$bit = $this->registry->getObject('urlprocessor')->getURLBit( 0 );
		$bit = ( $bit == '' ) ? 'content' : $bit;
		if( in_array( $bit, $this->sections ) )
		{
			// delegate control: lets have a think more about Routing at some point...
			$class = '\IckleMVC\Controllers\Administration_' . ucfirst( $bit ) . '_Delegate';
			$delegate = new $class( $this->registry ); 
		}
	}
	
	
	
	
	
	
}


?>