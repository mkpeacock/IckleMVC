<?php
/**
 * Front menu
 */
class Frontmenu {
    
    private $pages = array();
	private $pageKeys = array();
	private $structure = array();
	private $primary  = array();
	private $sub = array();
	private $menuType = 'sub';
	
	private $customStuffSet = false;
	private $customLinkName = '';
	private $customLinkPath = '';
	private $customFeatherID = 0;
	private $customMisc = array();
	private $includedExtentions = array();
	private $registry;
	private $extracrumbs = array();
	
	/**
	 * Constructor
	 * @param IckleRegistry $registry
	 * @return void
	 */
	public function __construct( IckleRegistry $registry )
	{
		$this->registry = $registry;
	}
	
	/**
	 * Set custom data for menu builder extensions, or error pages
	 * @param String $name page name
	 * @param String $path page path
	 * @param int $feather ID of current page
	 * @param array $custom custom data to send to MBE
	 * @return void
	 */
	public function setCustomStuff( $name, $path, $feather, $custom = array() )
	{
		$this->customStuffSet = true;
		$this->customLinkName = $name;
		$this->customLinkPath = $path;
		$this->customFeatherID = $feather;
		$this->customMisc = $custom;
	}
	
	/**
	 * Set the type of menu to build
	 * @param String $type
	 * @return void
	 */
	public function setMenuType( $type )
	{
		$this->menuType = $type;
	}
	
	/**
	 * Build our menu
	 * @param int $currentPageID
	 * @return void
	 */
	public function buildMenu( $currentPageID=0 )
	{
		require_once( FRAMEWORK_PATH . 'models/content/contents.php' );
		require_once( FRAMEWORK_PATH . 'models/content/pages.php' );
		$pages = new Pages( $this->registry );
		$this->pages = $this->registry->getObject('contenttreebuilder')->setSQL( $pages->getSQL( " c.menu=1 AND c.active=1 AND " ) )->generateStructure( 'page' )->getStructure();
		require_once( FRAMEWORK_PATH . 'libraries/internal/tree/tree.class.php' );
		$treeplanter = new TreePlanter();
		$treesurgeon = new TreeSurgeon();
		$treeplanter->setSeeds( $this->pages );
		$tree = $treeplanter->plant();
		$treesurgeon->payloadAlter( $tree, array( 's_name' => 'name' ) );
		echo '<pre>' . $tree . '</pre>';
		exit();
		switch( $this->menuType )
		{
			case 'tree':
				$this->buildTreeMenu( $currentPageID );
				$cache = $this->registry->getObject('db')->cacheData( $this->structure );
				$this->registry->getObject('template')->getPage()->addTag('ickle_tree_menu', array( 'DATA', $cache ) );
				$cache = $this->buildBreadCrumb( $currentPageID );
				$this->registry->getObject('template')->getPage()->addTag('ickle_crumbs', array( 'DATA', $cache ) );
				break;
			case 'drop':
				$this->buildDropDownMenu( $currentPageID );
				$cache = $this->buildBreadCrumb( $currentPageID );
				$this->registry->getObject('template')->getPage()->addTag('ickle_crumbs', array( 'DATA', $cache ) );
				break;
			case 'sub':
				$this->buildPrimaryMenu( $currentPageID );
				$this->buildSubMenu( $currentPageID );
				$cache = $this->registry->getObject('db')->cacheData( $this->primary );
				$this->registry->getObject('template')->getPage()->addTag('ickle_primary_menu', array( 'DATA', $cache ) );
				$cache = $this->registry->getObject('db')->cacheData( $this->sub );
				$this->registry->getObject('template')->getPage()->addTag('ickle_sub_menu', array( 'DATA', $cache ) );
				$cache = $this->buildBreadCrumb( $currentPageID );
				$this->registry->getObject('template')->getPage()->addTag('ickle_crumbs', array( 'DATA', $cache ) );
				break;
			case 'none':
				break;
		}

	}
	
	/**
	 * Build breadcrumb trail
	 * @param int $currentPage
	 * @return int (database cache)
	 */
	private function buildBreadCrumb( $currentPage )
	{
		$parents = array();
		if( $this->pages['page-' . $currentPage]['parent'] != 0 )
		{
			$parents = $this->registry->getObject('contenttreebuilder')->getParents( $currentPage );
		}
		$crumbs = array();
		foreach( $this->pages as $key => $data )
		{
			if( in_array( $data['ID'], $parents ) )
			{
				$crumb = array();
				$crumb['crumb_link_name'] = $data['menu_link_name'];
				$crumb['crumb_link_path'] = $data['menu_link_path'];
				$crumb['crumb_link_as'] = '';
				$crumb['crumb_link_ae'] = '';
				$crumbs[] = $crumb;
			}
		}
		if( isset( $crumbs[0] ) )
		{
			$this->registry->getObject('template')->getPage()->addTag('section_name', $crumbs[0]['crumb_link_name']);
			$this->registry->getObject('template')->getPage()->addTag('section', $crumbs[0]['crumb_link_path']);
		}
		else
		{
			$n = $this->pages['page-' . $currentPage]['s_name'];
			$this->registry->getObject('template')->getPage()->addTag('section_name', $n );
			$p = $this->pages['page-' . $currentPage]['s_path'];
			$this->registry->getObject('template')->getPage()->addTag('section', $p );
		}
		
		if( !empty( $this->extracrumbs ) )
		{
			foreach( $this->extracrumbs as $crumb )
			{
				$crumbs[] = $crumb;
			}
		}
		$crumb = array();
		if( $this->customStuffSet == true  )
		{
			$crumb['crumb_link_name'] = $this->customLinkName;
			$crumb['crumb_link_path'] = $this->customLinkPath;
		}
		else
		{
			$crumb['crumb_link_name'] = $this->pages['page-' . $currentPage]['s_name'];
			$crumb['crumb_link_path'] = $this->pages['page-' . $currentPage]['s_path'];
		}
		
		$crumb['crumb_link_as'] = '<!--';
		$crumb['crumb_link_ae'] = '-->';
		$crumbs[] = $crumb;
		
		$cache = $this->registry->getObject('db')->cacheData( $crumbs );
		
		return $cache;
			
	}
	
	private function buildDropDownMenu( $currentPage )
	{
	
	}
	
	/**
	 * Build the primary navigation for a primary/sub style menu
	 * @param int $currentPage the current page the user is on
	 * @return void
	 */
	private function buildPrimaryMenu( $currentPage )
	{
		$primary = array();
		$parents = array();
		if( $this->pages['page-' . $currentPage]['parent'] != 0 )
		{
			$parents = $this->registry->getObject('contenttreebuilder')->getParents( $currentPage );
		}
		foreach( $this->pages as $key => $data )
		{
			$link = array();
			$link['menu_link'] = $data['s_path'];
			$link['menu_text'] = $data['s_name'];
			$link['menu_title'] = $data['s_title'];
			$link['isCurrent'] = 'not-current';
			
			if( count( $this->registry->getObject('contenttreebuilder')->getChildren( $data['ID'] ) ) > 0 )
			{
				$link['hasChildren'] = 'has-children';
			}
			else
			{
				$link['hasChildren'] = 'no-children';
			}
			if( $data['parent'] == 0 )
			{
				if( in_array( $data['ID'], $parents ) )
				{
					$link['isAParent'] = 'parent';
					$link['isCurrent'] = 'not-current';
				}
				else
				{
					$link['isAParent'] = 'not-parent';
					$link['isCurrent'] = 'not-current';
				}
				if( $data['ID'] == $currentPage )
				{
					$link['isCurrent'] = 'current';
				}
				$primary[] = $link;
			}
		}

		$this->primary = $primary;
	}
	
	/**
	 * Build a sub menu in a primary/sub menu type
	 * @param int $currentPage the current page we are on
	 * @return void
	 */
	private function buildSubMenu( $currentPage )
	{
		$sub = array();
		$parents = array();
		$otherChildren = array();
		// get a list of children of the current pages parent
		if( $this->pages['page-' . $currentPage]['parent'] != 0 )
		{
			
			$parents = $this->registry->getObject('contenttreebuilder')->getParents( $currentPage );
			foreach( $parents as $pid )
			{
				$otherChildren = array_merge( $otherChildren ,$this->registry->getObject('contenttreebuilder')->getChildren(  $pid, false ) );
			}
			
		}
		
		// get list of children of current page
		$immediateChildren = $this->registry->getObject('contenttreebuilder')->getChildren( $currentPage, false );
		
		// build our menu
		foreach( $this->pages as $key => $data )
		{
			$link = array();
			$link['menu_link'] = $data['s_path'];
			$link['menu_text'] = $data['s_name'];
			$link['menu_title'] = $data['s_title'];
			$link['isCurrent'] = 'not-current';
			if( count( $this->registry->getObject('contenttreebuilder')->getChildren( $data['ID'] ) ) > 0 )
			{
				$link['hasChildren'] = 'has-children';
			}
			else
			{
				$link['hasChildren'] = 'no-children';
			}
			//echo $data['ID'];
			// if the page we are on is a special page with menu builder extention, pass control to get children
			/**if( ( in_array( $data['ID'], $parents ) ||  $data['ID'] == $currentPage || in_array( $data['ID'], $immediateChildren) || in_array( $data['ID'], $otherChildren) ) && $data['parent'] != 0  && $data['placeholder'] == 1 && $data['menugen'] != '' && file_exists( FRAMEWORK_PATH.'registry/menubuilder_extentions/' . $data['menugen'] . '.php' ) )
			{
				//echo 'a';
				if( ! in_array( $data['menugen'], $this->includedExtentions ) )
				{
					include( FRAMEWORK_PATH.'registry/menubuilder_extentions/' . $data['menugen'] . '.php' );
					$this->includedExtentions[] = $data['menugen'];
				}
				$mbe = new MenuBuilderExtention( $this->registry );
				if( $this->customStuffSet == true ) { $mbe->setFeather( $this->customFeatherID ); }
				$mbe->setCustomMisc( $this->customMisc );
				$children = $mbe->generateChildren( $data );
				$this->extracrumbs = array_merge($this->extracrumbs, $mbe->getExtracrumbs() );
				foreach( $children as $child  )
				{
					
					$sub[] = $child;
				}
				
			} 
			else*/
			if( $data['parent'] != 0 )
			{
				if( in_array( $data['ID'], $parents ) )
				{
					$link['isAParent'] = 'parent';
					$link['isCurrent'] = 'not-current';
					$sub[] = $link;
					
				}
				elseif( $data['ID'] == $currentPage )
				{
					$link['isAParent'] = 'not-parent';
					$link['isCurrent'] = 'current';
					$sub[] = $link;
				}
				elseif( in_array( $data['ID'], $immediateChildren) )
				{
					$link['isAParent'] = 'not-parent';
					$link['isCurrent'] = 'not-current';
					$sub[] = $link;
				}
				elseif( in_array( $data['ID'], $otherChildren) )
				{
					$link['isAParent'] = 'not-parent';
					$link['isCurrent'] = 'not-current';
					$sub[] = $link;
				}
				
			}
		}
		$this->sub = $sub;
	}
	
	/**
	 * Build a tree based menu
	 * @param int $currentPage the current page the user is on
	 */
	private function buildTreeMenu( $currentPage )
	{
		$structure = array();
		$parents = array();
		if( $this->pages['page-' . $currentPage]['parent'] != 0 )
		{
			$parents = $this->$this->registry->getObject('contenttreebuilder')->getParents( $currentPage );
		}
		$immediateChildren = $this->registry->getObject('contenttreebuilder')->getChildren( $currentPage, false );
		$otherChildren = array();
		foreach( $parents as $key => $parent )
		{
			$otherChildren = array_merge( $otherChildren, $this->registry->getObject('contenttreebuilder')->getChildren( $parent, false ) );
		}

		foreach( $this->pages as $key => $data )
		{
			$link = array();
			$link['menu_link'] = $data['s_path'];
			$link['menu_text'] = $data['s_name'];
			$link['menu_title'] = $data['s_title'];
			$link['isCurrent'] = 'not-current';
			
			if( $data['parent'] == 0 )
			{
				if( $data['ID'] == $currentPage )
				{
					$link['isCurrent'] = 'current';
				}
				if( count( $this->registry->getObject('contenttreebuilder')->getChildren( $data['ID'] ) ) > 0 )
				{
					$link['hasChildren'] = 'has-children';
				}
				else
				{
					$link['hasChildren'] = 'no-children';
				}
				if( in_array( $data['ID'], $parents ) )
				{
					$link['isAParent'] = 'parent';
				}
				else
				{
					$link['isAParent'] = 'not-parent';
					
				}
				$structure[] = $link;
				
			}
			elseif( $data['ID'] == $currentPage )
			{
				
				$data['isCurrent'] = 'current';
				if( count( $immediateChildren ) > 0 )
				{
					$link['isAParent'] = 'parent';
				}
				else
				{
					$link['isAParent'] = 'not-parent';
				}
				//echo 'c';
				//$structure[] = $data;
				
			}
			elseif( in_array( $data['ID'], $parents ) )
			{
				$link['isAParent'] = 'parent';
				//$structure[] = $data;
			}
			
			/**if( ( in_array( $data['ID'], $immediateChildren) || in_array( $data['ID'], $otherChildren ) ) && $data['parent'] != 0  && $data['placeholder'] == 1 && $data['menugen'] != '' && file_exists( FRAMEWORK_PATH.'registry/menubuilder_extentions/' . $data['menugen'] . '.php' ) )
			{
				include( FRAMEWORK_PATH.'registry/menubuilder_extentions/' . $data['menugen'] . '.php' );
				$mbe = new MenuBuilderExtention( $this->registry );
				if( $this->customStuffSet == true ) { $mbe->setFeather( $this->customFeatherID ); }
				$mbe->setCustomMisc( $this->customMisc );
				$children = $mbe->generateChildren( $data );
				$this->extracrumbs = array_merge($this->extracrumbs, $mbe->getExtracrumbs() );
				foreach( $children as $child  )
				{
					if( in_array( $data['ID'], $immediateChildren) )
					{
						$child['isAParent'] = 'not-parent';
						$structure[] = $child;
					}
					elseif( in_array( $data['ID'], $otherChildren ) )
					{
						$child['isAParent'] = 'not-parent';
						$structure[] = $child;
					}		
				}
				
			} 
			else*/
			if( in_array( $data['ID'], $immediateChildren) )
			{
				$link['isAParent'] = 'not-parent';
				$structure[] = $link;
			}
			elseif( in_array( $data['ID'], $otherChildren ) )
			{
				$link['isAParent'] = 'not-parent';
				$structure[] = $link;
			}		
		}
		
		$this->structure =  $structure;
	}
    
}
?>