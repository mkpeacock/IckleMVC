<?php
namespace IckleMVC\Models;

/**
 * Root content type
 * 
 * @author Michael Peacock
 * @copyright IckleMVC Project, CentralApps Limited
 */
class Content_Content extends Data_Model{
	
	
	protected $activeName = 'Active';
	protected $inactiveName = 'Disabled';

	
	// time formats for query: escaped for sprintf
	private $timestampFormatter = '%%D %%M %%Y %%H:%%i';
	private $dateFormatter = '%%D %%M %%Y';
	private $pickerFormatter = '%%m/%%d/%%Y';
	private $timeFormatter = '%%H:%%i';
	
	/**
	 * Default content SQL query
	 * @var String
	 */
	private $defaultSQL = 	"";
	
	/**
	 * Content model constructor
	 * @param IckleRegistry $registry
	 * @param array $coreData
	 * @return void
	 */
	public function __construct( \IckleMVC\Registry\IckleRegistry $registry, $contentData=array() )
	{
		if( ! empty( $contentData ) )
		{
			$this->buildFromArray( $contentData );
			/**
			$this->id = $contentData['content_id'];
			$this->versionID = $contentData['version_id'];
			$this->contentCreator = $contentData['content_creator_user_id'];
			$this->contentCreatorUsername = $contentData['content_creator_username'];
			$this->contentCreated = $contentData['content_created'];
			$this->contentCreatedFriendly = $contentData['content_created_friendly'];
			
			
			$this->versionCreator = $contentData['version_creator_user_id']; 
			$this->versionCreatorUsername = $contentData['version_creator_username'];
			$this->versionCreated = $contentData['version_created'];
			$this->versionCreatedFriendly = $contentData['version_created_friendly'];
			
			$this->publicationTimestamp = $contentData['publication_timestamp'];
			$this->publicationDateFriendly = $contentData['publication_friendly'];
			$this->publicationDatePicker = $contentData['publication_picker'];
			$this->publicationTime = $contentData['publication_time'];
			
			$this->expiryTimestamp = $contentData['expiry_timestamp'];
			$this->expiryDateFriendly = $contentData['expiry_friendly'];
			$this->expiryDatePicker = $contentData['expiry_picker'];
			$this->expiryTime = $contentData['expiry_time'];
			
			$this->type = $contentData['content_type_id'];
			$this->typeName = $contentData['content_type_name'];
			$this->typeReference = $contentData['content_type_reference'];
			$this->friendlyURL = $contentData['path'];
			$this->name = $contentData['name'];
			$this->title = $contentData['title'];
			$this->heading = $contentData['content'];
			$this->content = $contentData['content'];
			
			$this->metaKeywords = $contentData['meta_keywords'];
			$this->metaDescription = $contentData['meta_description'];
			$this->active = $contentData['active'];
			$this->requiresAuthentication = $contentData['requires_authentication'];
			$this->parent = $contentData['parent'];
			$this->order = $contentData['order'];
			*/
		}
	}
	
	/**
	 * Generate SQL statement
	 * Primarilly used by child content models, so they don't need to know the table structure
	 * @param String $fields
	 * @param String $tables
	 * @param String $joins
	 * @param String $conditions
	 * @param int $id
	 * @return String the SQL statement
	 */
	public function generateSQL( $fields='', $tables='', $joins='', $conditions='', $type, $id=0, $path='', $pop=0, $deleted=0, $deletedOr=0 )
	{
		$scopeReference = $this->registry->getObject('scope')->getReference();
		$scopeTypeReference = $this->registry->getObject('scope')->getTypeReference();
		
		$this->defaultSQL = "SELECT 
								c.ID as content_id, 
								c.path, 
								c.active, 
								c.order, 
								c.parent, 
								c.requires_authentication, 
								c.created as content_created, 
								DATE_FORMAT( c.created,  '".$this->timestampFormatter."' ) as content_created_friendly,  
								c.type as content_type_id, 
								t.reference as content_type_reference,  
								t.name as content_type_name, 
								v.ID as version_id, 
								v.created as version_created, 
								DATE_FORMAT( v.created, '".$this->timestampFormatter."' ) as version_created_friendly,  
								v.name, 
								v.title, 
								v.heading, 
								v.content, 
								v.meta_keywords, 
								v.meta_description,  
								v.publication_timestamp, 
								IFNULL( DATE_FORMAT( v.publication_timestamp, '".$this->dateFormatter."' ), 'N/A') as publication_friendly,  
								DATE_FORMAT( v.publication_timestamp, '".$this->pickerFormatter."' ) as publication_picker, 
								DATE_FORMAT( v.publication_timestamp, '".$this->timeFormatter."' ) as publication_time,  
								v.expiry_timestamp, 
								IFNULL( DATE_FORMAT( v.expiry_timestamp, '".$this->dateFormatter."' ), 'N/A' ) as expiry_friendly,  
								DATE_FORMAT( v.expiry_timestamp, '".$this->pickerFormatter."' ) as expiry_picker, 
								DATE_FORMAT( v.expiry_timestamp, '".$this->timeFormatter."' ) as expiry_time, 
								IFNULL( ua.ID, 0 ) as content_creator_user_id, 
								IFNULL( ua.username, 'N/A') as content_creator_username, 
								IFNULL( ub.ID, 0 ) as version_creator_user_id, 
								IFNULL( ub.username, 0 ) as version_creator_username 
								%s 
							 FROM 
							 	%s 
							 	content_types t,  
							 	content_versions v 
							 		LEFT JOIN 
							 			users ub ON ( ub.ID=v.creator ), 
							 	content c 
							 		LEFT JOIN 
							 				users ua ON ( ua.ID=c.creator ) %s 
							 WHERE %s v.ID=c.current_version_id 
							 		AND ( SELECT COUNT(*) FROM content_scope_associations csa, scopes s, scopes_types st WHERE csa.content_id=c.ID AND s.ID=csa.scope_id AND st.ID=s.type AND ( s.reference=%d OR st.reference='global') AND st.reference='%s' ) > 0  
							 		AND t.ID=c.type 
							 		AND t.reference='%s' 
							 		AND ( c.ID=%d OR ( c.path<>'' AND c.path='%s' ) OR ( 1=%d ) ) 
							 		AND ( c.deleted=%d OR c.deleted=%d ) 
							 ORDER BY c.`order` ASC
							 LIMIT 1";
		return sprintf( $this->defaultSQL, $fields, $tables, $joins, $conditions, $scopeReference, $scopeTypeReference, $type, $id, $path, $pop, $deleted, $deletedOr );
	}
	
	/**
	 * Save changes to the content type
	 * @param bool $versionChanged
	 * @return void
	 */
	public function save( $versionChanged=true )
	{
		$content = array();
		$content['creator'] = ( $this->contentCreator == 0 ) ? null : $this->contentCreator;
		
		
		if( $this->id == 0 )
		{
			
		}
		else
		{
			if( $versionChanged )
			{
				
			}
		}
	}
	
	/**
	 * Is the content published?
	 */
	public function isPublished()
	{
		return ( date('Y-m-d H:i:s') >= $this->publicationTimestamp && ( date('Y-m-d H:i:s') <= $this->expiryTimestamp || $this->expiryTimestamp == '0000-00-00 00:00:00' ) ) ? true : false;
	}
	
	/**
	 * Set the content elements friendlyURL
	 * @param String $path the friendlyURL
	 * @param bool $formatted indicates if the path is already nicely formatted
	 * @param bool $ignoreDuplicates indicates if the function should ignore duplicate paths [@todo match by content type too - assumed generateType is called first]
	 * @param bool $changing indicates if we are changing a path, and need to recheck certain things!
	 * @return void
	 */
	public function setFriendlyURL( $path, $formatted=true, $ignoreDuplicates=false, $changing=false  )
	{
		if( $formatted == false )
		{
			$path = preg_replace('/[^a-zA-Z0-9\s]/', '', $path );
			$path = str_replace( ' ', '-', $path );
			$path = strtolower( $path );
		}
		// ignore duplicates or this isn't NEW content and we are not changing the path manually (secretly)
		if( $ignoreDuplicates || ( $this->id > 0 && $changing == false ) )
		{
			$this->friendlyURL = $path;
		}
		else
		{
			// look for pre-existing path
			$sql = "SELECT path FROM content WHERE deleted=0 AND path='{$path}' ORDER BY path ASC  LIMIT 1";
			$this->registry->getObject('db')->executeQuery( $sql );
			if( $this->registry->getObject('db')->numRows() > 0 )
			{
				// look for pre-existing path followed by -NUMBER
				$sql = "SELECT path FROM content WHERE path REGEXP '^{$path}-[0-9]*' ORDER BY path DESC LIMIT 1";
				$this->registry->getObject('db')->executeQuery( $sql );
				if( $this->registry->getObject('db')->numRows() > 0 )
				{
					$data = $this->registry->getObject('db')->getRows();
					$data = explode( '-', $data['path'] );
					$value = end( $data );
					// increment the number and set the path
					$value++;
					$this->friendlyURL = $path . '-' . $value;
				}
				else
				{
					// path exists, no subsequent -NUMBER paths exist, this is the first
					$this->friendlyURL = $path . '-1';
				}
			}
			else
			{
				// no duplicates
				$this->friendlyURL = $path;
			}
		}
		
	}
	
	public function getID()
	{
		return $this->id;
	}
	
	public function requiresAuthentication()
	{
		return $this->requiresAuthentication;
	}
	
	/**
	 * Get an array of the core data
	 * @param String $prefix
	 * @return array
	 */
	public function getData( $prefix='' )
	{
		$tor = array();
		foreach( $this as $key => $data )
		{
			if( ! is_array( $data ) && ! is_object( $data ) )
			{
				$tor[ $prefix . $key ] = $data;
			}
		}
		foreach( $this->_data as $key => $data )
		{
			if( ! is_array( $data ) && ! is_object( $data ) )
			{
				$tor[ $prefix . $key ] = $data;
			}
		}
		return $tor;
	}
	
	/**
	 * Get the title of the content element
	 * @return String
	 */
	public function getTitle()
	{
		return $this->title;
	}
	
}

?>