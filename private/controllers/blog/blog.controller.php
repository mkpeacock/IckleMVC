<?php
/**
 * Blog controller for basic blog functionality
 * @author Michael Peacock
 */
class Blogcontroller{
	
	/**
	 * Reference to the registry object
	 * @var IckleRegistry
	 */
	private $registry;
	
	/**
	 * Constructor
	 * @param IckleRegistry $registry
	 * @param bool $autoProcess
	 * @return void
	 */
	public function __construct( IckleRegistry $registry, $autoProcess=true )
	{
		$this->registry = $registry;
		if( $autoProcess )
		{
			
		}
	}
	
	public function test()
	{
		
	}
	
	
}


?>