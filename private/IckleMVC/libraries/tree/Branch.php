<?php
namespace IckleMVC\Libraries;

class Tree_Branch {
	
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
	
	public function addBranch( Tree_Branch $branch )
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