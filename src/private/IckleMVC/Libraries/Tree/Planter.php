<?php
namespace IckleMVC\Libraries;
class Tree_Planter {
	
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
		$tree = new Tree_Tree();

		foreach( $this->seeds as $seed )
		{
			$branch = new Tree_Branch( $seed['ID'], $seed );
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
?>