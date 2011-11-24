<?php
namespace IckleMVC\Registry;

/**
 * Page template object
 * 
 * @author Michael Peacock
 */
class Template_Page {

	/**
	 * Page title
	 * @var String
	 */
	private $title;
	
	/**
	 * Page content
	 * @var String
	 */
	private $content;
	
	/**
	 * Template replacement tags
	 * @var array
	 */
	private $tags = array( 'standard' => array(), 'postparse' => array() );
	
	/**
	 * Template replacement template bits
	 * @var array
	 */
	private $bits = array();
	
	/**
	 * Additional parsing data
	 * @var array
	 */
	private $additionalParsingData = array();
	
	/**
	 * Additional JavaScript for inclusion in the page
	 * @var String
	 */
	private $additionalJavaScript = "";

	/**
	 * Page constructor
	 */
    public function __construct() {
    	
    }
    
    
    /**
     * Get the page title from the page
     * @return String
     */
    public function getTitle()
    {
    	return $this->title;
    }
    
    /**
     * Set the page title
     * @param String $title the page title
     * @return void
     */
    public function setTitle( $title )
    {
	    $this->title = $title;
    }
    
    /**
     * Set the page content
     * @param String $content the page content
     * @return void
     */
    public function setContent( $content )
    {
	    $this->content = $content;
    }
    
    /**
     * Add a template tag, and its replacement value/data to the page
     * @param String $key the key to store within the tags array
     * @param String $data the replacement data (may also be an array)
     * @param String $type [default standard]
     * @return void
     */
    public function addTag( $key, $data, $type='standard' )
    {
	    $this->tags[ $type ][ $key ] = $data;
    }
    
    /**
     * Remove a template tag
     * @param String $key the key to remove
     * @param String $type the type of tag it is
     * @return void
     */
    public function removeTag( $key, $type='standard' )
    {
    	unset( $this->tags[ $type ][ $key ] );
    } 
    
    /**
     * Get tags associated with the page
     * @return void
     */
    public function getTags( $type='standard' )
    {
	    return $this->tags[ $type ];
    }
    
    /**
     * Add a template bit to the page, doesnt actually add the content just yet
     * @param String the tag where the template is added
     * @param String the template file name
     * @return void
     */
    public function addTemplateBit( $tag, $bit, $replacements=array() )
    {
	   $this->bits[ $tag ] = array( 'template' => $bit, 'replacements' => $replacements);
    }
    
    /**
     * Add JavaScript file to the page
     * @param String $html
     * @return void
     */
    public function addJavaScriptFile( $html )
	{
		$this->additionalJavaScript .= $html;
	}
	
	/**
	 * Get JavaScript files HTML
	 * @return String
	 */
	public function getJavaScriptFileHTML()
	{
		return $this->additionalJavaScript;
	}
    
    /**
	 * Adds additional parsing data
	 * A.P.D is used in parsing loops.  We may want to have an extra bit of data depending on on iterations value
	 * for example on a form list, we may want a specific item to be "selected"
	 * @param String block the condition applies to
	 * @param String tag within the block the condition applies to
	 * @param String condition : what the tag must equal
	 * @param String extratag : if the tag value = condition then we have an extra tag called extratag
	 * @param String data : if the tag value = condition then extra tag is replaced with this value
	 */
	public function addAdditionalParsingData($block, $tag, $condition, $extratag, $data)
	{
		$this->additionalParsingData[$block] = array($tag => array('condition' => $condition, 'tag' => $extratag, 'data' => $data));
	}
    
    /**
     * Get the template bits to be entered into the page
     * @return array the array of template tags and template file names
     */
    public function getBits()
    {
	    return $this->bits;
    }
    
    /**
     * Get the additional parsing data array
     * @return array
     */
    public function getAdditionalParsingData()
    {
    	return $this->additionalParsingData;
    }
    
    /**
     * Gets a chunk of page content
     * @param String the tag wrapping the block ( <!-- START tag --> block <!-- END tag --> )
     * @return String the block of content
     */
    public function getBlock( $tag )
    {
		$results = preg_match ('#<!-- START '. $tag . ' -->(.+?)<!-- END '. $tag . ' -->#si', $this->content, $tor);
		if( $results == 0 )
		{
			return 0;
		}	
		else
		{
			$tor = str_replace ('<!-- START '. $tag . ' -->', '', $tor[0] );
			$tor = str_replace ('<!-- END '  . $tag . ' -->', '', $tor );
			return $tor;
		}
		
    }
    
    /**
     * Get the page content
     * @return String
     */
    public function getContent()
    {
    	return $this->content;
    }
    
    /**
     * Get content, in a format ready for output to browser, assumes template->parseOutput has been called first
     * @return String
     */
    public function getContentToPrint()
    {
    	$this->content = preg_replace ('#{form_(.+?)}#si', '', $this->content);	
    	$this->content = preg_replace ('#{nbd_(.+?)}#si', '', $this->content);	
    	$this->content = str_replace('</body>', '
	<!-- Powered by IckleMVC the little-but-strong Model-View-Controller framework -->
	</body>', $this->content );
	    return $this->content;
    }
    
    
  
}
    


?>