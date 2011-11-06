<?php
/**
 * Basic user class
 * @author Michael Peacock
 * @copyright IckleMVC Project
 */
class User {
	
	/**
	 * User ID
	 * @var int
	 */
	private $id;
	
	/**
	 * Users username
	 * @var String
	 */
	private $username;
	
	/**
	 * Users email address
	 */
	private $email;
	private $active;
	private $banned;
	private $administrator;
	private $valid;
	
    public function __construct( IckleRegistry $registry, $id=0, $username='', $password='' ) 
    {
    	$this->registry = $registry;
    	
    	$sql = "SELECT u.username, u.email, u.active, u.joined, u.last_logged_in, u.banned, u.deleted, u.administrator 
    			FROM users u 
    			WHERE 1=1 ";
    	if( $id > 0 )
    	{
    		$sql .= " AND u.ID=" . $id;
    	}
    	elseif( $username != '' && $password != '' )
    	{
    		$sql .= " AND u.username='{$username}' AND u.password_hash='" . $this->registry->getObject('authentication')->hashPassword( $password ) . "'";
    	}
    	$sql .= " LIMIT 1";
    	$this->registry->getObject('db')->executeQuery( $sql );
    	if( $this->registry->getObject('db')->getNumRows() > 0 )
    	{
    		$data = $this->registry->getObject('db')->getRows();
    		$this->valid = true;
    		$this->username = $data['username'];
    		$this->email = $data['email'];
    		$this->active = $data['active'];
    		$this->banned = $data['banned'];
    		$this->administrator = $data['administrator'];
     	}
    	else
    	{
    		$this->valid = false;
    	}
    }
    
    public function isAdministrator()
    {
    	return $this->administrator;
    }
    
    public function getID()
    {
    	return $this->id;
    }
    

}
?>