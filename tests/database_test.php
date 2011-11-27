<?php

define( "FRAMEWORK_PATH", dirname( __FILE__ ) ."/../src/private/" );

require_once( FRAMEWORK_PATH . 'splClassLoader.php' );
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
									'db' => '\IckleMVC\Registry\Database_MySQL',
									'template' => '\IckleMVC\Registry\Template',
									'scope' => '\IckleMVC\Registry\PrimaryScope',
									'urlprocessor' => '\IckleMVC\Registry\URLProcessor',
									'frontmenu' => '\IckleMVC\Registry\FrontMenu',
									'contenttreebuilder' => '\IckleMVC\Registry\ContentTreeBuilder',
									'authentication' => '\IckleMVC\Registry\Authentication'
									

								);
										

Mock::generate('IckleMVC\Registry\IckleRegistry', 'MockIckleRegistry');


class TestOfDatabase extends UnitTestCase {
	
	protected $database;
	protected $mysqli;
	
	/**
	 * 
	 */
	public function setUp()
    {
    	  $this->database = new \IckleMVC\Registry\Database_MySQL( new MockIckleRegistry() );     
    	  $this->mysqli = new \mysqli( default_host, default_user, default_password, default_database ); 
    	  $this->mysqli->query("CREATE TABLE  `ickle`.`test_dba` (`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,`some_field` VARCHAR( 255 ) NOT NULL) ENGINE = MYISAM");
    }
    
    public function tearDown()
    {
    	$this->mysqli->query("DROP TABLE `test_dba`");
    }
    
    public function testNewConnection()
    {
    	$connectionID = $this->database->newConnection( default_host, default_user, default_password, default_database );
    	$this->assertTrue( ( $connectionID >= 0 ), 'Database connection failed' );
    }
    
    public function testInsert()
    {
    	$insert = array( 'some_field' => 'my_value' );
    	$this->database->insertRecords( 'test_dba', $insert );
    	$result = $this->mysqli->query("SELECT * FROM test_dba");
    	if( $result->num_rows == 1 )
    	{
    		//$row = $result->fetch_row();
    		//echo '<pre>' . print_r( $row, true ) . '</pre>';
    	}
    	else
    	{
    		$this->fail( "No records inserted" );
    	}
    	
    	
    	
    	
    }
    
   
}
?>