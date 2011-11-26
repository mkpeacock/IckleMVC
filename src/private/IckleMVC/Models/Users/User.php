<?php
namespace IckleMVC\Models;
/**
 * Basic user class
 * @author Michael Peacock
 * @copyright IckleMVC Project
 */
class Users_User extends Model{
	
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
	 * @var String
	 */
	private $email;
	private $active;
	private $banned;
	private $administrator;
	protected $valid;
	
    public function __construct( \IckleMVC\Registry\IckleRegistry $registry, $id=0, $username='', $password='' ) 
    {
    	$this->registry = $registry;
    	
    	$sql = "SELECT u.ID, u.username, u.email, u.active, u.joined, u.last_logged_in, u.banned, u.deleted, u.administrator 
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
    	else
    	{
    		$sql .= " AND 1=2 ";
    	}
    	$sql .= " LIMIT 1";
    	$this->registry->getObject('db')->executeQuery( $sql );
    	if( $this->registry->getObject('db')->getNumRows() > 0 )
    	{
    		$data = $this->registry->getObject('db')->getRows();
    		$this->id = $data['ID'];
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
    
    public function isValid()
    {
    	return $this->valid;
    }
    
    public function isAdministrator()
    {
    	return $this->administrator;
    }
    
    public function getID()
    {
    	return $this->id;
    }
    
    public function save()
    {
    	
    }
    

}
?>