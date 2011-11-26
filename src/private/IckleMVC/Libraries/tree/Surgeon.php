<?php
namespace IckleMVC\Libraries;
class Tree_Surgeon {
	
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
		}
	}
	
}