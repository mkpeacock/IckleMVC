<?php

define( "FRAMEWORK_PATH", dirname( __FILE__ ) ."/" );

/**
 * Ickle MVC Bootstrap file
 * Performs core includes, registry and object setup and request process
 * @author Michael Peacock
 */
class Bootstrap
{
	/**
	 * Registry object
	 */
	private $registry;
	
	/**
	 * Bootstrap constructor
	 * @return void
	 */
	public function __construct()
	{
		session_name('icklemvc');
		session_start();
		session_regenerate_id();
		
		require_once( 'splClassLoader.php' );
		$classLoader = new SplClassLoader('IckleMVC\Registry', FRAMEWORK_PATH );
		$classLoader->register();
		$classLoader = new SplClassLoader('IckleMVC\Models', FRAMEWORK_PATH );
        $classLoader->register();
        $classLoader = new SplClassLoader('IckleMVC\Controllers', FRAMEWORK_PATH );
        $classLoader->register();
        $classLoader = new SplClassLoader('IckleMVC\Libraries', FRAMEWORK_PATH );
        $classLoader->register();
        $classLoader = new SplClassLoader('IckleMVC\Views', FRAMEWORK_PATH );
        $classLoader->register();
		
		$defaultRegistryObjects = array(
											'db' => 'IckleMVC\Registry\Database_MySQL',
											'template' => 'IckleMVC\Registry\Template',
											'scope' => 'IckleMVC\Registry\PrimaryScope',
											'urlprocessor' => 'IckleMVC\Registry\URLProcessor',
											'frontmenu' => 'IckleMVC\Registry\FrontMenu',
											'contenttreebuilder' => 'IckleMVC\Registry\ContentTreeBuilder',
											'authentication' => 'IckleMVC\Registry\Authentication'
											
		
										);
		
		
		
		//require_once( FRAMEWORK_PATH . 'registry/registry.class.php' );
		$this->registry = new \IckleMVC\Registry\IckleRegistry( $defaultRegistryObjects );
		$this->defaultRegistrySetup();
		
		
				
		if( ACCESS_POINT == 'BACK' )
		{
			$this->administrationDelegation();
		}
		else
		{
			$this->frontEndDelegation();
		}
		
		$this->notifications();
		$this->registry->getObject('template')->parseOutput();
		print $this->registry->getObject('template')->getPage()->getContentToPrint();
		
	}
	
	private function frontEndDelegation()
	{
		if( ( (bool) intval( $this->registry->getSetting('site_ssl') ) ) )
		{
			$this->registry->getObject('template')->getPage()->addTag( 'siteurl', str_replace( 'http://', 'https://', $this->registry->getSetting('site_url' ) ) );
		}
		else
		{
			$this->registry->getObject('template')->getPage()->addTag( 'site_url', $this->registry->getSetting('site_url' ) );
		}
		$fc = new \IckleMVC\Controllers\Front_Controller( $this->registry );
		$fc->setActiveControllers( array('blog') );
		$fc->process();
	}
	
	private function administrationDelegation()
	{
		if( ( (bool) intval( $this->registry->getSetting('admin_ssl') ) ) )
		{
			$this->registry->getObject('template')->getPage()->addTag( 'siteurl', str_replace( 'http://', 'https://', $this->registry->getSetting('site_url' ) ) );
		}
		else
		{
			$this->registry->getObject('template')->getPage()->addTag( 'site_url', $this->registry->getSetting('site_url' ) );
		}
		$this->registry->getObject('template')->getPage()->addTag('admin_folder', $this->registry->getSetting('admin_folder') );
		$this->registry->storeSetting( $this->registry->getSetting('administration_view'), 'view' );
		
		$fc = new \IckleMVC\Controllers\Administration_Controller( $this->registry );
	}
	
	/**
	 * Setup and store the core, default registry objects
	 * @return void
	 */
	private function defaultRegistrySetup()
	{
		$db_credentials = array();
		require_once( FRAMEWORK_PATH . 'config.php' );
		$this->registry->getObject('db')->newConnection( $db_credentials['default_host'], $db_credentials['default_user'], $db_credentials['default_password'], $db_credentials['default_database'] );
		
		$settings = new \IckleMVC\Models\Settings_Data_Collection( $this->registry );
		$settings->buildCoreSettings();
		foreach( $settings as $setting )
		{
			$this->registry->storeSetting( $setting->getKey(), $setting->getData() );
		}
		
		$this->registry->getObject('authentication')->authenticationCheck();
	}
	
	private function notifications()
	{
		if( isset( $_SESSION['notification_message'] ) && is_array( $_SESSION['notification_message'] ) )
		{
			$this->registry->getObject('template')->addTemplateBit( 'notification_message', 'snippets/notification_message.tpl.php' );
			$this->registry->getObject('template')->getPage()->addTag( 'message_type', $_SESSION['notification_message']['type'] );
			$this->registry->getObject('template')->getPage()->addTag( 'message_heading', $_SESSION['notification_message']['heading'] );
			$this->registry->getObject('template')->getPage()->addTag( 'message_text', $_SESSION['notification_message']['message'] );
			unset( $_SESSION['notification_message'] );
		}
		else
		{
			$this->registry->getObject('template')->getPage()->addTag( 'notification_message', '' );
		}
	}
	
	/**
	 * Get the registry object
	 * @return Object
	 */
	public function getRegistry()
	{
		return $this->registry;
	}
	
}

?>