<?php
require_once( __DIR__  . '/../private/registry/registry.class.php');
Mock::generate('IckleRegistry');

require_once( __DIR__ . '/../private/registry/aspects/database/database.abstract.php');
require_once( __DIR__ . '/../private/registry/aspects/database/mysql.database.class.php');

class TestOfDatabase extends UnitTestCase {
	
	protected $database;
	
	/**
	 * 
	 */
	public function setUp()
    {
    	  $this->database = new MySQLDatabase( new MockIckleRegistry() );      
    }
    
    public function testNewConnection()
    {
    	$connectionID = $this->database->newConnection( default_host, default_user, default_password, default_database );
    	$this->assertTrue( ( $connectionID >= 0 ), 'Database connection failed' );
    }
    
   
}
?>