<?php

class TreePlanter {
	
	/**
	 * Plant your seeds of data to make a tree
	 */
	private $seeds = array();
	
	public function setSeeds( $seeds )
	{
		$this->seeds = $seeds;
	}
	
	public function plant()
	{
		$tree = new Tree();

		foreach( $this->seeds as $seed )
		{
			$branch = new Branch( $seed['ID'], $seed );
			if( $seed['parent'] == 0 )
			{
				$tree->getTrunk()->addBranch( $branch );
			}
			else
			{
				$tree->getBranch( $seed['parent'] )->addBranch( $branch );
			}
		}
		
		return $tree;
	}
	
}

class TreeSurgeon {
	
	public function __construct()
	{
		
	}
	
	public function payloadAlter( $tree, $alterataions=array() )
	{
		$tree->retrieveAllBranches();
		$branches = $tree->getAllBranches();
		foreach( $branches as $branch )
		{
			$payload = $branch->getPayload();
			$newPayload = array();
			foreach( $alterataions as $existing => $new )
			{
				$newPayload[ $new ] = $payload[ $existing ];
			}
			$branch->setPayload( $newPayload );
			//$tree->getBranch( $branch->getReference() )->setPayload( $newPayload );
			echo 'a' . $branch->getReference();
		}
		echo 'b';
	}
	
}

class Tree{
	
	private $payload = array();
	
	private $trunk;
	private $allBranches = array();
	
	public function __construct(  )
	{
		$this->trunk = new Branch();
		$this->trunk->setParent( $this );
		$this->trunk->setDistanceFromTrunk( 0 );
	}
	
	public function retrieveAllBranches()
	{
		$this->allBranches = array_merge( $this->trunk->getBranches(), $this->trunk->getChildren() );
	}
	
	public function getTrunk()
	{
		return $this->trunk;
	}
	
	public function getBranch( $reference )
	{
		foreach( $this->allBranches as $branch )
		{
			if( $branch->getReference() == $reference )
			{
				return $branch;
			}
		}
	}
	
	public function getAllBranches()
	{
		return $this->allBranches;
	}
	
	public function __toString()
	{
		return print_r( $this->trunk->getBranches(), true );
	}
	
	
}

class Branch {
	
	private $parent = null;
	private $branches = array();
	private $terminates = true;
	private $reference;
	private $payload;
	private $children = array();
	private $distance = 0;
	
	public function __construct( $reference=null, $payload=null )
	{
		$this->reference = $reference;
		$this->payload = $payload;
	}
	
	public function setTerminates( $terminates )
	{
		$this->terminates = $terminates;
	}
	
	public function addBranch( Branch $branch )
	{
		$this->branches[] = $branch;
		$this->terminates = false;
		$this->updateChildren( $this->children );
		$this->setDistanceFromTrunk( $this->distance + 1 );		
	}
	
	public function getChildren()
	{
		return $this->children;
	}
	
	public function updateChildren( $children )
	{
		$this->children = $children;
		echo get_class( $this->parent );
		if( ! is_null( $this->parent ) && is_object( $this->parent ) && strtolower( get_class( $this->parent ) ) == 'branch' )
		{
			$this->parent->updateChildren( array_merge( $this->branches, $this->children ) );
		}
	}
	
	public function getBranches()
	{
		return $this->branches;
	}
	
	public function setParent( $parent )
	{
		$this->parent = $parent;
	}
	
	public function setDistanceFromTrunk( $distance )
	{
		$this->distance = $distance;
		foreach( $this->branches as $branch )
		{
			$branch->setDistanceFromTrunk( $this->distance + 1 );
		}
	}
		
	public function calculateDistanceFromTrunk()
	{
		if( ! is_null( $this->parent ) && is_object( $this->parent ) && strtolower( get_class( $this->parent ) ) == 'branch' )
		{
			return $this->parent->getDistanceFromTrunk() + 1;
		}
		else
		{
			return 0;
		}
	}
	
	public function getReference()
	{
		return $this->reference;
	}
	
	public function getPayload()
	{
		return $this->payload;
	}
	
	public function setPayload( $payload )
	{
		$this->payload = $payload;
	}

	
}

?>