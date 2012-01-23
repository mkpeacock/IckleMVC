<?php
namespace IckleMVC\Registry;
/**
 * Ickle MVC MySQL Database Library
 * 
 * @author Michael Peacock
 * @copyright IckleMVC
 * @package IckleMVC/Registry
 * @subpackage Registry
 */
class Database_MySQL extends Database_Database {
	
    /**
	 * Construct our database object
	 */
    public function __construct( IckleRegistry $registry ) 
    {
    	$this->registry = $registry;	
    }
    
    /**
     * Create a new database connection
     * @param String database hostname
     * @param String database username
     * @param String database password
     * @param String database we are using
     * @return int the id of the new connection
     */
    public function newConnection( $host, $user, $password, $database )
    {
    	$this->connections[] = new \mysqli( $host, $user, $password, $database );
    	$connection_id = count( $this->connections )-1;
    	if( mysqli_connect_errno() )
    	{
    		trigger_error('Error connecting to host. '.$this->connections[$connection_id]->error, E_USER_ERROR);
		} 	

    	
    	return $connection_id;
    }
    
    /**
     * Execute an SQL statement
     * @param String $sql
     * @return void
     */
    public function executeQuery( $sql )
    {
    	if( ! $result = $this->connections[$this->activeConnection]->query( $sql ) )
    	{
		    trigger_error('Error executing query: ' . $sql .' - '.$this->connections[$this->activeConnection]->error, E_USER_ERROR);
		}
		else
		{
			$this->previouslyExecuted = $result;
		}
    }
    
    /**
     * Get the number of rows returned from the previously executed query
     * @return int
     */
    public function getNumRows()
    {
    	return $this->previouslyExecuted->num_rows;
    }
    
    /**
     * Get the rows from the most recently executed query, excluding cached queries
     * @return array 
     */
    public function getRows()
    {
    	return $this->previouslyExecuted->fetch_array(MYSQLI_ASSOC);
    }
    
    /**
     * Gets the number of affected rows from the previous query
     * @return int the number of affected rows
     */
    public function getNumAffectedRows()
    {
    	return $this->connections[$this->activeConnection]->affected_rows;
    }
    
    /**
     * Get the ID of the last inserted record
     * @return int
     */
    public function getLastInsertID()
    {
	    return $this->connections[ $this->activeConnection]->insert_id;
    }
    
    public function insertRecord( $table, $record, $updateIfKeyExists=false, $updateSQL="" )
    {
    	// setup some variables for fields and values
    	$fields  = "";
		$values = "";
		
		// populate them
		foreach ($record as $f => $v)
		{
			$fields  .= "`$f`,";
			$values .= ( is_numeric( $v ) && ( intval( $v ) === $v ) ) ? $v."," : "'$v',";
		}
		
		// remove our trailing ,
    	$fields = substr( $fields, 0, -1 );
    	// remove our trailing ,
    	$values = substr( $values, 0, -1 );
    	
		$insert = "INSERT INTO $table ({$fields}) VALUES({$values})";
		if( $updateIfKeyExists )
		{
			$insert .= " ON DUPLICATE KEY UPDATE {$updateSQL} ";
		}
		$this->executeQuery( $insert );
		return true;
    }
    
    public function insertRecords( $table, $records )
    {
    	
    }
	
	public function updateRecords( $table, $changes, $conditions )
	{
		$update = "UPDATE " . $table . " SET ";
    	foreach( $changes as $field => $value )
    	{
    		if( $value == NULL )
    		{
    			$update .= "`" . $field . "`=null,";
    		}
    		else
    		{
    			$update .= "`" . $field . "`='{$value}',";
    		}
    	}
    	   	
    	// remove our trailing ,
    	$update = substr($update, 0, -1);
    	if( $conditions != '' )
    	{
    		$update .= " WHERE " . $conditions;
    	}
    	$this->executeQuery( $update );
    	return true;
	}
	
	public function updateRecordsMulti( $table, $records, $conditions )
	{
		
	}
	
	public function deleteRecords( $table, $condition, $limit )
	{
		
	}
    
    /**
     * Store a query in the query cache for processing later
     * @param String the query string
     * @return the pointed to the query in the cache
     */
    public function cacheQuery( $queryStr )
    {
    	if( !$result = $this->connections[$this->activeConnection]->query( $queryStr ) )
    	{
		    trigger_error('Error executing and caching query: '.$this->connections[$this->activeConnection]->error, E_USER_ERROR);
		    return -1;
		}
		else
		{
			$this->queryCache[] = $result;
			return count($this->queryCache)-1;
		}
    }
    
    /**
     * Get the number of rows from the cache
     * @param int the query cache pointer
     * @return int the number of rows
     */
    public function getNumRowsFromCache( $cache_id )
    {
    	return $this->queryCache[$cache_id]->num_rows;	
    }
    
    /**
     * Get the rows from a cached query
     * @param int the query cache pointer
     * @return array the row
     */
    public function getResultsFromCache( $cache_id )
    {
    	return $this->queryCache[$cache_id]->fetch_array(MYSQLI_ASSOC);
    }
    
    /**
     * Sanitize data
     * @param String the data to be sanitized
     * @return String the sanitized data
     */
    public function sanitizeData( $value )
    {
    	// Stripslashes 
		if ( get_magic_quotes_gpc() ) 
		{ 
			$value = stripslashes ( $value ); 
		} 
		
		// Quote value
		if ( version_compare( phpversion(), "4.3.0" ) == "-1" ) 
		{
			$value = $this->connections[$this->activeConnection]->escape_string( $value );
		} 
		else 
		{
			$value = $this->connections[$this->activeConnection]->real_escape_string( $value );
		}
    	return $value;
    }
    
    /**
    * Disconnect from a specific connection
    * @param int $connectionID
    * @return void
    */
    public function disconnect( $connectionID )
    {
    	if( array_key_exists( $connectionID, $this->connections ) )
    	{
    		$this->connections[ $connectionID ]->close();
    		$this->connections[ $connectionID ] = null;
    	}
    }
    
    /**
     * Destructor
     * Closes all MySQL connections
     * @return void
     */
    public function __destruct()
    {
    	foreach( $this->connections as $connection )
    	{
    		if( ! is_null( $connection ) )
    		{
    			 
    			$connection->close();
    		}
    	}
    }
}
?>