<?php
namespace IckleMVC\Registry;
/**
 * Template engine
 * @author Michael Peacock
 * @copyright IckleMVC
 * @package IckleMVC/Registry
 * @subpackage template
 */
class Template {
	
	/**
	 * Registry object
	 * @var IckleRegistry
	 */
	private $registry;
		
	/**
	 * Page object
	 * @var Page
	 */	
	private $page;

	/**
	 * Constructor
	 * @param IckleRegistry $registry
	 * @return void
	 */
   	public function __construct( IckleRegistry $registry )
	{
		$this->registry = $registry;
    	//include( FRAMEWORK_PATH . 'registry/aspects/template/page.class.php');
	    $this->page = new \IckleMVC\Registry\Template_Page();
    }
    
    /**
     * Get the page object
     * @return Page
     */
    public function getPage()
    {
    	return $this->page;
    }
    
    /**
     * Add a template bit from a view to our page
     * @param String $tag the tag where we insert the template e.g. {hello}
     * @param String $bit the template bit (path to file, or just the filename)
     * @return void
     */
    public function addTemplateBit( $tag, $bit, $data=array()  )
    {
		if( strpos( $bit, 'views/' ) === false )
		{
		    $bit = PUBLIC_PATH . 'views/' . $this->registry->getSetting('view') . '/templates/' . $bit;
		}
		$this->page->addTemplateBit( $tag, $bit, $data );
    }
    
    /**
     * Add a javascript file to the template
     * @param String $file_url
     * @return void
     */
    public function addJavaScriptFile( $file_url )
    {
    	 $template = PUBLIC_PATH . 'views/' . $this->registry->getSetting('view') . '/templates/snippets/jsfile.tpl.php';
    	 $templateContent = file_get_contents( $template );
    	 $templateContent = str_replace( '{file}', $file_url, $templateContent );
    	 $this->page->addJavaScriptFile( $templateContent );
    }
    
    /**
     * Take the template bits from the view and insert them into our page content
     * Updates the pages content
     * @return void
     */
    private function replaceBits()
    {
	    $bits = $this->page->getBits();
	    // loop through template bits e.g.
	    foreach( $bits as $tag => $template )
	    {
		    $templateContent = file_get_contents( $template['template'] );
		    $tags = array_keys( $template['replacements'] );
		    $tagsNew = array();
		    foreach( $tags as $taga )
		    {
		    	$tagsNew[] = '{' . $taga . '}';
		    }
		    $values = array_values( $template['replacements'] );
		    
		    $templateContent = str_replace( $tagsNew, $values, $templateContent );
		    $newContent = str_replace( '{' . $tag . '}', $templateContent, $this->page->getContent() );
		    $this->page->setContent( $newContent );
	    }
    }
    
    private function replaceCollectionTags( $tag, $collection )
    {
    	$block = '';
    	$blockOld = $this->page->getBlock( $tag );
    	if( $blockOld !== 0 )
    	{
    		$apd = $this->page->getAdditionalParsingData();
    		$apdkeys = array_keys( $apd );
    		// foreach record relating to the query...
    		foreach( $collection as $tags )
    		{
    			$blockNew = $blockOld;
    	
    			// Do we have APD tags?
    			if( in_array( $tag, $apdkeys ) )
    			{
    				// YES we do!
    				$data = $tags->getData();
    				foreach ($data as $ntag => $data)
    				{
    					$blockNew = str_replace("{" . $ntag . "}", $data, $blockNew);
    					// Is this tag the one with extra parsing to be done?
    					if( array_key_exists( $ntag, $apd[ $tag ] ) )
    					{
    						// YES it is
    						$extra = $apd[ $tag ][$ntag];
    						// does the tag equal the condition?
    						if( $data == $extra['condition'] || ( is_array( $extra['condition'] ) && in_array( $data, $extra['condition'] ) )  )
    						{
    	
    							// Yep! Replace the extratag with the data
    							$blockNew = str_replace("{" . $extra['tag'] . "}", $extra['data'], $blockNew);
    						}
    						else
    						{
    							// remove the extra tag - it aint used!
    							$blockNew = str_replace("{" . $extra['tag'] . "}", '', $blockNew);
    						}
    					}
    				}
    			}
    			else
    			{
    				// create a new block of content with the results replaced into it
    				$data = $tags->getData();
    				foreach ($data as $ntag => $data)
    				{
    					$blockNew = str_replace("{" . $ntag . "}", $data, $blockNew);
    				}
    			}
    	
    			$block .= $blockNew;
    		}
    		$pageContent = $this->page->getContent();
    		// remove the seperator in the template, cleaner HTML
    		$newContent = str_replace( '<!-- START ' . $tag . ' -->' . $blockOld . '<!-- END ' . $tag . ' -->', $block, $pageContent );
    		// update the page content
    		$this->page->setContent( $newContent );
    	}
    }
    
    /**
     * Replace content on the page with data from the database
     * @param String $tag the tag defining the area of content
     * @param int $cacheId the queries ID in the query cache
     * @return void
     */
    private function replaceDBTags( $tag, $cacheId )
    {
	    $block = '';
		$blockOld = $this->page->getBlock( $tag );
		if( $blockOld !== 0 )
		{
			$apd = $this->page->getAdditionalParsingData();
			$apdkeys = array_keys( $apd );
			// foreach record relating to the query...
			while ($tags = $this->registry->getObject('db')->resultsFromCache( $cacheId ) )
			{
				$blockNew = $blockOld;
				
				// Do we have APD tags?
				if( in_array( $tag, $apdkeys ) )
				{
					// YES we do!
			        foreach ($tags as $ntag => $data) 
			       	{
			        	$blockNew = str_replace("{" . $ntag . "}", $data, $blockNew);
			        	// Is this tag the one with extra parsing to be done?
			        	if( array_key_exists( $ntag, $apd[ $tag ] ) )
			        	{
				        	// YES it is
				        	$extra = $apd[ $tag ][$ntag];
				        	// does the tag equal the condition?
				        	if( $data == $extra['condition'] || ( is_array( $extra['condition'] ) && in_array( $data, $extra['condition'] ) )  )
				        	{
				        		
					        	// Yep! Replace the extratag with the data
					        	$blockNew = str_replace("{" . $extra['tag'] . "}", $extra['data'], $blockNew);
				        	}
				        	else
				        	{
					        	// remove the extra tag - it aint used!
					        	$blockNew = str_replace("{" . $extra['tag'] . "}", '', $blockNew);
				        	}
			        	} 
			        }
				}
				else
				{
					// create a new block of content with the results replaced into it
					foreach ($tags as $ntag => $data) 
			       	{
			        	$blockNew = str_replace("{" . $ntag . "}", $data, $blockNew); 
			        }
				}
				
		        $block .= $blockNew;
			}
			$pageContent = $this->page->getContent();
			// remove the seperator in the template, cleaner HTML
			$newContent = str_replace( '<!-- START ' . $tag . ' -->' . $blockOld . '<!-- END ' . $tag . ' -->', $block, $pageContent );
			// update the page content
			$this->page->setContent( $newContent );
		}
		
	}
	
	/**
     * Replace content on the page with data from the database
     * @param String $tag the tag defining the area of content
     * @param int $cacheId the queries ID in the query cache
     * @return void
     */
    private function replaceDataTags( $tag, $cacheId )
    {
	    $block = '';
		$blockOld = $this->page->getBlock( $tag );
		
		if( $blockOld !== 0 )
		{
			$apd = $this->page->getAdditionalParsingData();
			$apdkeys = array_keys( $apd );
			// foreach data relating to the cache...
			$thetags = $this->registry->getObject('db')->dataFromCache( $cacheId );
			foreach( $thetags as $key => $tags )
			{
				$blockNew = $blockOld;
				
				// Do we have APD tags?
				if( in_array( $tag, $apdkeys ) )
				{
					// YES we do!
			        foreach ($tags as $ntag => $data) 
			       	{
			        	$blockNew = str_replace("{" . $ntag . "}", $data, $blockNew);
			        	// Is this tag the one with extra parsing to be done?
			        	if( array_key_exists( $ntag, $apd[ $tag ] ) )
			        	{
				        	// YES it is
				        	$extra = $apd[ $tag ][$ntag];
				        	// does the tag equal the condition?
				        	if( $data == $extra['condition'] || ( is_array( $extra['condition'] ) && in_array( $data, $extra['condition'] ) ) )
				        	{
				        		
					        	// Yep! Replace the extratag with the data
					        	$blockNew = str_replace("{" . $extra['tag'] . "}", $extra['data'], $blockNew);
				        	}
				        	else
				        	{
					        	// remove the extra tag - it aint used!
					        	$blockNew = str_replace("{" . $extra['tag'] . "}", '', $blockNew);
				        	}
			        	} 
			        }
				}
				else
				{
					// create a new block of content with the results replaced into it
					foreach ($tags as $ntag => $data) 
			       	{
			        	$blockNew = str_replace("{" . $ntag . "}", $data, $blockNew); 
			        }
				}
				
		        $block .= $blockNew;
			}
			$pageContent = $this->page->getContent();
			// remove the seperator in the template, cleaner HTML
			$newContent = str_replace( '<!-- START ' . $tag . ' -->' . $blockOld . '<!-- END ' . $tag . ' -->', $block, $pageContent );
			// update the page content
			$this->page->setContent( $newContent );
		}
		
	}
	
    /**
     * Replace tags in our page with content
     * @param array $tags
     * @return void
     */
    private function replaceTags( $tags=array() )
    {
	    // go through them all
	    foreach( $tags as $tag => $data )
	    {
		    // if the tag is an array, then we need to do more than a simple find and replace!
		    if( is_array( $data ) )
		    {
			    if( $data[0] == 'SQL' )
			    {
				    // it is a cached query...replace tags from the database
				    $this->replaceDBTags( $tag, $data[1] );
			    }
			    elseif( $data[0] == 'DATA' )
			    {
				     // it is some cached data...replace tags from cached data
				    $this->replaceDataTags( $tag, $data[1] );
			    }
	    	}
	    	elseif( is_object( $data ) && in_array( 'IteratorAggregate', class_implements( $data ) ) )
	    	{
	    		$this->replaceCollectionTags( $tag, $data );
	    	}
	    	else
	    	{	
		    	// replace the content	    	
		    	$newContent = str_replace( '{' . $tag . '}', $data, $this->page->getContent() );
		    	// update the pages content
		    	$this->page->setContent( $newContent );
	    	}
	    }
    }
    
    /**
     * Set the content of the page based on a number of templates
     * pass template file locations as individual arguments
     * @return void
     */
    public function buildFromTemplates()
    {
	    $bits = func_get_args();
	    $content = "";
	    foreach( $bits as $bit )
	    {
		    
		    if( strpos( $bit, 'views/' ) === false )
		    {
			    $bit = PUBLIC_PATH . 'views/' . $this->registry->getSetting('view') . '/templates/' . $bit;
		    }
		    if( file_exists( $bit ) == true )
		    {
			    $content .= file_get_contents( $bit );
		    }
		    
	    }
	    $this->page->setContent( $content );
    }
    
    /**
     * Convert an array of data into some tags
     * @param array the data 
     * @param string a prefix which is added to field name to create the tag name
     * @return void
     */
    public function dataToTags( $data, $prefix )
    {
	    foreach( $data as $key => $content )
	    {
		    $this->page->addTag( $prefix.$key, $content);
	    }
    }
    
    /**
     * Take the title we set in the page object, and insert them into the view
     * @return void
     */
    public function parseTitle()
    {
    	$title = $this->page->getTitle();
    	if( $this->registry->getSetting( 'template_title_prefix') != '' )
    	{ 
    		$title = $this->registry->getSetting( 'template_title_prefix') . $title;
    	}
    	if( $this->registry->getSetting( 'template_title_suffix') != '' )
    	{
    		$title = $title . $this->registry->getSetting( 'template_title_suffix');
    	}
	    $newContent = str_replace('<title>', '<title>'. $title, $this->page->getContent() );
	    $this->page->setContent( $newContent );
    }
    
    /**
     * Parse any additional elements
     * @return void
     */
    public function parseExtras()
    {
    	$newContent = str_replace('</head>', $this->page->getJavaScriptFileHTML() . '</head>', $this->page->getContent() );
	    $this->page->setContent( $newContent );
    }
    
    /**
     * Parse the page object into some output
     * @return void
     */
    public function parseOutput()
    {
    	// "magic" tags
    	$this->getPage()->addTag( 'Y', date('Y') );
    	
    	
	    $this->replaceBits();
	    $this->replaceTags( $this->page->getTags() );
	    $this->replaceBits();
	    $this->replaceTags( $this->page->getTags( 'postparse' ) );
	    $this->parseTitle();
	    $this->parseExtras();
	    
	    return $this;
    }
}
?>