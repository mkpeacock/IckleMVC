<?php
						

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
    	$this->database->newConnection( default_host, default_user, default_password, default_database );
    	$insert = array( 'some_field' => 'my_value' );
    	$this->database->insertRecord( 'test_dba', $insert );
    	$result = $this->mysqli->query("SELECT * FROM test_dba");
    	if( $result->num_rows == 1 )
    	{
    		$row = $result->fetch_assoc();
    		if( $row['id'] == 1 && $row['some_field'] == 'my_value' )
    		{
    			$this->pass();
    		}
    		else
    		{
    			$this->fail( "Table contents don't match what was inserted" );
    		}
    	}
    	else
    	{
    		$this->fail( "No records inserted" );
    	}	
    	    	
    }
    
    public function testInsertWithKnownDuplicatePK()
    {
    	
    }
    
    public function testUpdate()
    {
    	
    }
    
   
}
?>