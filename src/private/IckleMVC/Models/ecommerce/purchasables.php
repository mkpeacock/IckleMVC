<?php
/**
 * Get purchasables from within the basket or an order
 * 
 * @author Michael Peacock
 */
class Purchasables{
	
	private $coreBasketSQL = "";
	private $coreOrderSQL = "";
	
	public function __construct()
	{
		require_once( FRAMEWORK_PATH . 'models/ecommerce/purchasable.php');
		
	}
	
	public function getByType( $type, $userID, $ipAddress, $session, $orderID )
	{
		switch( $type )
		{
			case 'product':
				$this->getProducts( $userID, $ipAddress, $session, $orderID );
				break;
		}
	}
	
	private function getProducts( $userID, $ipAddress, $session, $orderID )
	{
		$tor = array();
		require_once( FRAMEWORK_PATH . 'models/content/content.php');
		require_once( FRAMEWORK_PATH . 'models/ecommerce/product.php');
		require_once( FRAMEWORK_PATH . 'models/ecommerce/basketproduct.php');
		$sql = "";
		$this->registry->getObject('db')->executeQuery( $sql );
		if( $this->registry->getObject('db')->numRows() > 0 )
		{
			while( $row = $this->registry->getObject('db')->getRows() )
			{
				$product = new Basketproduct( $this->registry, 0 );
				
				$tor[ 'order-' . $row['lookup_id'] ] = array( 'reference'=> $row['lookup_id'], 'purchasable' => $product, 'quantity' => $row['quantity'], 'customised' => $product->hasBeenCustomised() );
			}
		}		
		return $tor;
	}
	
	
	
	
}



?>