<?php
namespace IckleMVC\Libraries;

class Tree_Tree{
	
	private $payload = array();
	
	private $trunk;
	private $allBranches = array();
	
	public function __construct(  )
	{
		$this->trunk = new Tree_Branch();
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

?>