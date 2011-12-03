<?php
namespace IckleMVC\Registry;
/**
 * Content Tree Structure Generator
 * 
 * @author Michael Peacock
 * @copyright IckleMVC
 * @package IckleMVC/Registry
 */
class ContentTreeBuilder {
	
	/**
	 * Reference to IckleRegistry
	 * @var IckleRegistry
	 */
	private $registry;
	
	/**
	 * Content tree
	 * @var array
	 */
	private $content;
	
	/**
	 * Cache of content data
	 * @var int
	 */
	private $cacheID;
	
	/**
	 * Type of content
	 * @var String
	 */
	private $type;
	
	/**
	 * SQL used to generate the tree
	 * @var String
	 */
	private $contentSQL;
	
	/**
	 * Content Tree Structure constructor
	 * @param IckleRegistry $registry
	 * @return void
	 */
    public function __construct( IckleRegistry $registry ) 
    {
    	$this->registry = $registry;
    }
    
    /**
     * Set the standard SQL for listing the content
     * @param String $sql
     * @return void
     */
    public function setSQL( $sql )
    {
    	$this->contentSQL = $sql;
    	return $this;
    }
    
    /**
     * Generate the tree structure
     * @param String $type
     * @return void
     */
    public function generateStructure( $type )
    {
    	$this->type = $type;
		$this->registry->getObject('db')->executeQuery( $this->contentSQL );
		if( $this->registry->getObject('db')->getNumRows() > 0 )
		{
			$this->content = array();
			$tempContent = array();
			while( $content = $this->registry->getObject('db')->getRows() )
			{
				$content['ID'] = $content['s_content_id'];
				$content['version_id'] = $content['s_version_id'];
				$content['parent'] = $content['s_parent'];
				$content['order'] = $content['s_order'];
				$content['depth'] = 0;
				$content['openers'] = '';
				$content['closers'] = '';
				$tempContent[ $this->type . '-' . $content['s_content_id'] ] = $content;
			}
			
			$open = 0;
			$parentsForFirstChildCheck = array();
			$closers = array();
			
			foreach( $tempContent as $key => $data )
			{
				
				if( $data['parent'] == 0 )
				{
					$tempContent[ $key ]['depth'] = 0;
				}
				elseif( ! isset( $tempContent[ $this->type. '-'. $data['parent'] ] ) )
				{
					$tempContent[ $key ]['depth'] = 0;
				}
				else
				{
					$tempContent[ $key ]['depth'] = $tempContent[ $this->type. '-'. $data['parent'] ]['depth'] + 1;
				}
				
				// priority and frequency for sitemaps
				$tempContent[ $key ]['priority'] = number_format((1 - ( ( $tempContent[ $key ]['depth'] * 0.2 ) > 0.8 ? 0.2 : ( $tempContent[ $key ]['depth'] * 0.2 ) )),2,'.','');
				$tempContent[ $key ]['frequency'] = ( $tempContent[ $key ]['priority'] == 1 ) ? 'daily' : 'weekly';
				
				if( ! in_array( $data['parent'], $parentsForFirstChildCheck ) )
				{
					$parentsForFirstChildCheck[] = $data['parent'];
					$tempContent[ $key ]['position'] = 'first-child';
				}
				
				$tempContent[ $key ]['openers'] = '</li>';
				$tempContent[ $key ]['hc'] = '';
				$tempContent[ $key ]['ex'] = '';
				
				if( count( $this->getChildren( $data['ID'] ) ) > 0 )
				{
					$pages[ $key ]['ex'] = 'class="sm2_expander"';
					if( $pages[ $key ]['collapsed'] == 1 )
					{
						$pages[ $key ]['hc'] = 'class="sm2_liClosed"';
						
					}
					else
					{
						$pages[ $key ]['hc'] = 'class="sm2_liOpen"';
					}

					$pages[ $key ]['openers'] = '<ul id="parent-'. $data['ID'] . '">';
					$l = $this->findHighestOrder( $this->getChildren( $data['ID'] ) );
					if( isset( $lowest[ $l ] ) )
					{
						$lowest[ $l ]++;
					}
					else
					{
						$lowest[ $l ] = 1;
					}
				}
			}
			$this->content = $tempContent;
		
			$tempContent = array();
			$k = 0;
			foreach( $this->content as $key => $content )
			{
				$indents = '';
				for( $i = 0; $i < $content['depth']; $i++ )
				{
					$indents .= '&nbsp;&nbsp;&nbsp;&nbsp;';
				}
				$content['indents'] = $indents;
				$content['style'] = ( $k % 2) ? 'row' : 'row_alt';
	
				
				$content['type'] = $this->type;
				
				$pageclosers = '';
				if( isset( $lowest[ $content['order'] ] ) )
				{
					for( $i=0; $i < $lowest[ $content['order'] ]; $i++ )
					{
						$pageclosers .= '</ul></li>';
					}
				}
				$content['closers'] = $pageclosers;
				$tempContent[] = $content;
				
				$k++;
		}
				
		$contentCache = $this->registry->getObject('db')->cacheData( $tempContent );
		$this->cacheID = $contentCache;
		}
		
		
		
		return $this;
    }
    
    /**
     * Find the lowest order  from an array of content elements
     * @param array $content
     * @return int
     */
    public function findLowestOrder( $content )
	{
		$orders = array();
		foreach( $content as $element )
		{
			$orders[] = $this->content[ $this->type . '-' . $element ]['order'];
			
		}
		return min($orders);
	}
	
	/**
	 * Find the highest order from an array of content elements
	 * @param array $content
	 * @return int
	 */
	public function findHighestOrder( $content )
	{
		$orders = array();
		foreach( $content as $element )
		{
			$orders[] = $this->content[$this->type . '-' . $element ]['order'];
			
		}
		return max($orders);
	}
	
	/**
	 * Get parents of a content element
	 * @param int $contentElement
	 * @return array
	 */
	public function getParents( $contentElement )
	{
		$parentKey = $this->content[$this->type . '-' . $contentElement ]['parent'];
		if( $parentKey == 0 )
		{
			return array();
		}
		else
		{
			if( $this->content[ $this->type . '-' .$parentKey]['parent'] == 0 )
			{
				return array($parentKey);
			}
			else
			{
				$pkarray = array($parentKey);
				return array_merge( $pkarray, $this->getParents($parentKey) );
			}
		}
		
	}
	
	/**
	 * Get IDs for children 
	 * @param $content int ID of the content element to get children of
	 * @return array  of IDs of children
	 */
	public function getChildren( $content )
	{
		$list = array();
		foreach( $this->content as $key => $data )
		{
			if( $data['parent'] == $content  )
			{
				$list[] = $data['ID'];
				$childCatsa = $this->getChildren( $data['ID'] );
				$list = array_merge( $list, $childCatsa );
			}
		}
		return $list;
	}
    
    
    public function generateLoop( $prefix='' )
    {
		$this->registry->getObject('template')->getPage()->addTag( $prefix . 'structure', array( 'DATA', $this->cacheID ) );
    }
    
    public function getStructure()
    {
    	return $this->content;
    }
    
    public function getCacheID()
    {
    	return $this->cacheID;
    }
    
    public function getContentSQL()
    {
    	return $this->contentSQL;
    }

}
?>