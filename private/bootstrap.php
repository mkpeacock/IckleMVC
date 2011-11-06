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
		
		$defaultRegistryObjects = array();
		$db = array( 'abstract' => 'database', 'folder' => 'database', 'file' => 'mysql.database', 'class' => 'MySQLDatabase', 'key' => 'db' );
		$defaultRegistryObjects['db'] = $db;
		$template = array( 'abstract' => null, 'folder' => 'template', 'file' => 'template', 'class' => 'Template', 'key' => 'template' );
		$defaultRegistryObjects['template'] = $template;
		$urlp = array( 'abstract' => null, 'folder' => 'urlprocessor', 'file' => 'urlprocessor', 'class' => 'URLProcessor', 'key' => 'urlprocessor' );
		$defaultRegistryObjects['urlprocessor'] = $urlp;
		$scope = array( 'abstract' => 'scope', 'folder' => 'scope', 'file' => 'primary', 'class' => 'Primaryscope', 'key' => 'scope' );
		$defaultRegistryObjects['scope'] = $scope;
		$ctb = array( 'abstract' => null, 'folder' => 'contenttreebuilder', 'file' => 'contenttreebuilder', 'class' => 'ContentTreeBuilder', 'key' => 'contenttreebuilder' );
		$defaultRegistryObjects['contenttreebuilder'] = $ctb;
		$fm = array( 'abstract' => null, 'folder' => 'menu', 'file' => 'frontmenu', 'class' => 'Frontmenu', 'key' => 'frontmenu' );
		$defaultRegistryObjects['frontmenu'] = $fm;
		$auth = array( 'abstract' => null, 'folder' => 'authentication', 'file' => 'authentication', 'class' => 'Authentication', 'key' => 'authentication' );
		$defaultRegistryObjects['authentication'] = $auth;
		
		require_once( FRAMEWORK_PATH . 'registry/registry.class.php' );
		$this->registry = new IckleRegistry( $defaultRegistryObjects );
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
		require_once( FRAMEWORK_PATH . 'controllers/front/front.controller.php' );
		$fc = new Frontcontroller( $this->registry );
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
		require_once( FRAMEWORK_PATH . 'controllers/administration/administration.controller.php' );
		$fc = new AdministrationController( $this->registry );
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
		$sql = "SELECT * FROM settings WHERE core=1";
		$this->registry->getObject('db')->executeQuery( $sql );
		if( $this->registry->getObject('db')->getNumRows() > 0 )
		{
			while( $row = $this->registry->getObject('db')->getRows() )
			{
				$this->registry->storeSetting( $row['key'], $row['data'] );
			}
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