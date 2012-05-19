<?php
namespace IckleMVC\Controllers;
/**
 * Front controller
 * 
 * @author Michael Peacock
 */
class Front_Controller {
	
	/**
	 * Reference to IckleRegistry
	 * @var IckleRegistry
	 */
	private $registry;
	
	/**
	 * All active controllers
	 * @var array
	 */
	private $activeControllers = array();
	
	/**
	 * Dynamic, regexp mappings
	 * @var array
	 */
	private $dynamicMappings = array();
	
	/**
	 * Constructor
	 * @param IckleRegistry $registry
	 * @return void
	 */
	public function __construct( \IckleMVC\Registry\IckleRegistry $registry, $autoProcess=false )
	{
		$this->registry = $registry;
		$mapping = array();
		$mapping['pattern'] = "/^[0-9]{4}\/[0-9]{2}\//";
		$mapping['controller'] = 'blog';
		$mapping['auto_process'] = false;
		$mapping['method'] = 'viewEntryIncludeMonthYear';
		$this->dynamicMappings[] = $mapping;
	}
	
	/**
	 * Set the active controllers in the site
	 * @param array $activeControllers
	 * @return void
	 */
	public function setActiveControllers( $activeControllers=array() )
	{
		$this->activeControllers = $activeControllers;
	}
	
	/**
	 * Set the dynamic mapping regexps
	 * @param array $dynamicMappings
	 * @return void
	 */
	public function setDynamicMappings( $dynamicMappings=array() )
	{
		$this->dynamicMappings = $dynamicMappings;
	}
	
/**
 * Process and route a request
 * @return void
 */
public function process( $fallback=false )
{
	$bit0 = $this->registry->getObject('urlprocessor')->getURLBit(0);
	if( in_array( $bit0, $this->activeControllers ) && $fallback == false )
	{
		$controller = 'IckleMVC\Controllers\\' . ucfirst( strtolower( $bit0 ) ) . '_Controller';
		$controller = new $controller( $this->registry, true );
	}
	else
	{
		// @anothonysterling doesn't like this way, but I do so there :-p
		$match = false;
		foreach( $this->dynamicMappings as $mapping )
		{
			$path = $this->registry->getObject('urlprocessor')->getURLPath();
			if( preg_match( $mapping['pattern'], $path ) && in_array( $mapping['controller'], $this->activeControllers ) )
			{
				$match = true;
				require_once( FRAMEWORK_PATH . 'controllers/'. $mapping['controller'] . '/'. $mapping['controller'] . '.controller.php' );
				$controllerName = ucfirst( $mapping['controller'] ) . 'controller';
				$controller = new $controllerName( $this->registry, $mapping['auto_process'] );
				if( ! $mapping['auto_process'] )
				{
					$controller->$mapping['method']( $path );
				}
			}
		}
		if( ! $match )
		{
			$controller = new \IckleMVC\Pages\Controllers\PageController( $this->registry, true );
		}
	}
}
	
	
	
}



?>