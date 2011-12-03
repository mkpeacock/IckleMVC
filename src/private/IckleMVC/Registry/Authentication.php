<?php
namespace IckleMVC\Registry;
/**
 * Authentication processor
 * 
 * @author Michael Peacock
 * @copyright IckleMVC
 * @package IckleMVC/Registry
 */
class Authentication {
	
	/**
	 * Reference to the user Object
	 * @var User
	 */
	private $user;
	
	/**
	 * Salt used for authentication
	 * @var String
	 */
	private $salt = 'IMVC46g456fgdf';
	
	/**
	 * Reference to the registry Object
	 * @var IckleRegistry
	 */
	private $registry;
	
	/**
	 * Authentication constructor
	 * @param IckleRegistry $registry
	 * @return void
	 */
    public function __construct( IckleRegistry $registry ) 
    {
    	$this->registry = $registry;
    	$this->user = new \IckleMVC\Models\Users_User( $this->registry, 0 );
    }
    
    /**
     * Check for authentication details, and process the attempt
     */
    public function authenticationCheck()
    {
    	if( isset( $_POST ) && is_array( $_POST ) && count( $_POST ) > 0 && ( isset( $_POST['ickle_auth_user'] ) || isset( $_POST['ickle_auth_pass'] ) ) )
    	{
    		$this->user = new \IckleMVC\Models\Users_User( $this->registry, 0, ( isset( $_POST['ickle_auth_user'] ) ? $this->registry->getObject('db')->sanitizeData( $_POST['ickle_auth_user'] ) : '' ), ( isset( $_POST['ickle_auth_pass'] ) ? $this->registry->getObject('db')->sanitizeData( $_POST['ickle_auth_pass'] ) : '' ) );
    		if( $this->user->isValid() )
    		{
    			$_SESSION['ickle_auth_userid'] = $this->user->getID();
    			// notify: success
    			$this->registry->notify( 'success', 'Success', 'You are now logged in');
    			
    		}
    		else
    		{
    			// notify: error
    			$this->registry->notify( 'error', 'Invalid credentials', 'Sorry, the details you supplied were not correct; please try again');
    		}
    	} 
    	elseif( isset( $_SESSION['ickle_auth_userid'] ) && intval( $_SESSION['ickle_auth_userid'] ) > 0 )
    	{
    		$this->user = new  \IckleMVC\Models\Users_User( $this->registry, intval( $_SESSION['ickle_auth_userid'] ) );
    	}
    	else
    	{
    		
    	}
    }
    
    /**
     * Get the user object
     * @return User
     */
    public function getUser()
    {
    	return $this->user;
    }
    
    /**
     * Is the current user a logged in user?
     * @return bool
     */
    public function isLoggedIn()
    {
    	return ( $this->user->getID() > 0 ) ? true : false; 
    }
    
    /**
     * Passowrd hashing logic: lets keep this out of the user model and keep it with the authentication class
     *  - users of this code should feel free (and be encouraged!) to change this hashing "algorithm" and the salt for their own installations
     * @param String $password [plain text]
     * @param String hashed password
     */
    public function hashPassword( $password )
    {
    	return sha1( sha1( $this->salt ) . sha1( $password ) . sha1( $this->salt ) );
    }
    
    
}
?>