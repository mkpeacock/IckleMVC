<?php
/**
 * IckleMVC: E-Commerce Shopping basket
 * 
 * @author Michael Peacock
 * @copyright Michael Peacock
 * @package ecommerce
 * @subpackage basket
 */
class Basket{
	
	/**
	 * Currency which applies to the basket
	 * @var String
	 */
	private $currency;
	
	/**
	 * Name of tax to apply to the order [costs/rates defined per product]
	 * @var String
	 */
	private $taxName;
	
	/**
	 * Contents of the basket
	 * @var array
	 */
	private $contents = array();
	
	/**
	 * Counter of number of unique customised items in the basket
	 * @var int
	 */
	private $customisedItemsCounter = 0;
	
	/**
	 * Indicates if the basket has already been checked during this execution cycle
	 * used to check if an embedded basket has already done our queries!
	 * @var bool
	 */
	private $checked = false;
	
	/**
	 * Subtotal of the baskets contents
	 * @var float
	 */
	private $subtotal;
	
	/**
	 * Shipping cost for the order
	 * @var float
	 */
	private $shippingCost;
	
	/**
	 * Shipping method 
	 * @var Shippingmethod
	 */
	private $shippingMethod;
	
	/**
	 * Tax cost for the order
	 * @var float
	 */
	private $tax;
	
	/**
	 * Total cost for the order
	 * @var float
	 */
	private $total;
	
	/**
	 * Voucher code applied to the order
	 * @var Voucher
	 */
	private $voucher;
	
	/**
	 * Shopping basket constructor
	 * @param IckleRegistry $registry
	 * @return void
	 */
	public function __construct( IckleRegistry $registry )
	{
		$items = array();
		$itemtypes = array();
		// 1 query to get the types of product in the basket
		$sql = "";
		$this->registry->getObject('db')->executeQuery( $sql );
		if( $this->registry->getObject('db')->numRows() > 0 )
		{
			while( $row = $this->registry->getObject('db')->getRows() )
			{
				$itemtypes[] = $row['type'];
			}
			require_once( FRAMEWORK_PATH . 'models/ecommerce/purchasables.php' );
			$purchasables = new Purchasables();
			
			// 1 query for each TYPE of product in the basket
			foreach( $itemtypes as $type )
			{
				$items = array_merge( $items, $purchasables->getByType( $type ) );
			}
			// order the items as per the basket [for niceness]
			ksort( $items );
			$temp = array();
			foreach( $items as $item )
			{
				if( $item['purchasable']->hasBeenCustomised() )
				{
					$this->contents[ 'customised-' . $item['purchasable']->getID() . '-' . $item['purchasable']->getType() . '-' . $this->customisedItemsCounter ] = array( 'product' => $item['purchasable'], 'quantity' => $item['quantity'], 'reference' => $item['reference'], 'new' => false, 'changed' => false );
					$this->customisedItemsCounter++;
			
				}
				else
				{
					$this->contents[ 'standard-' . $item['purchasable']->getID() . '-' . $item['purchasable']->getType() ] = array( 'product' => $item['purchasable'], 'quantity' => $item['quantity'], 'reference' => $item['reference'], 'changed' => false, 'new' => false );
				}
			}
		}
		$this->checked = true;
	}
	
	/**
	 * Is the basket empty?
	 * @return bool
	 */
	public function isEmpty()
	{
		return ( empty( $this->contents ) );
	}
	
	/**
	 * Add a product to the shopping basket
	 * @param Purchasable $product
	 * @param int $quantity
	 * @return void
	 */
	private function addProduct( Purchasable $product, $quantity )
	{
		if( ! $product->hasBeenCustomised() )
		{
			if( in_array( 'standard-' . $product->getID() , array_keys( $this->contents ) ) )
			{
				$this->contents[ 'standard-' . $product->getID() . '-' . $product->getType() ]['quantity'] += $quantity;
				$this->contents[ 'standard-' . $product->getID() . '-' . $product->getType() ]['changed'] = true; 
			}
			else
			{
				$this->contents[ 'standard-' . $product->getID() . '-' . $product->getType() ] = array( 'product' => $product, 'quantity' => $quantity, 'reference' => null, 'changed' => false, 'new' => true ); 
			}
		}
		else
		{
			$found = false;
			foreach( $this->contents as $key => $data )
			{
				if( $data['product'] == $product )
				{
					$this->contents[ $key ]['quantity'] += $quantity;
					$this->contents[ $key ]['changed'] = true;
					$found = true;
					break;
				}
			}
			if( ! $found )
			{
				$this->contents[ 'customised-' . $product->getID() . '-' . $product->getType() . '-' . $this->customisedItemsCounter ] = array( 'product' => $product, 'quantity' => $quantity, 'reference' => null, 'changed' => false, 'new' => true );
				$this->customisedItemsCounter++;
			}
		}
	}
	
	/**
	 * Update the quantity of a product in the basket
	 * @param int $basketReference
	 * @param int $newQuantity
	 * @return void
	 */
	private function updateQuantity( $basketReference, $newQuantity )
	{
		foreach( $this->contents as $key => $data )
		{
			if( $data['reference'] == $basketReference )
			{
				$this->contents[ $key ]['quantity'] = $newQuantity;
				break;
			}
		}
	}
	
	/**
	 * Remove a product from the basket
	 * @param int $basketReference
	 * @param Purchasable $product
	 * @return void
	 */
	private function removeProduct( $basketReference=null, Purchasable $product=null )
	{
		if( ! is_null( $basketReference ) )
		{
			foreach( $this->contents as $key => $data )
			{
				if( $data['reference'] == $basketReference )
				{
					$this->contents[ $key ]['quantity'] = 0;
					break;
				}
			}
		}
		elseif( ! is_null( $product ) )
		{
			foreach( $this->contents as $key => $data )
			{
				if( $data['product'] == $product )
				{
					$this->contents[ $key ]['quantity'] = 0;
					break;
				}
			}
		}
		
	}
	
	/**
	 * Save the basket
	 * @return void
	 */
	private function save()
	{
		$standardInserts = array();
		$customInserts = array();
		$updates = array();
		foreach( $this->contents as $key => $data )
		{
			if( $data['new'] !== false )
			{
				// updates
				$changes = array();
				$condition = array();
				$update = array( 'changes' => $changes, 'condition' => $condition );
				$updates[] = $update;
			}
			else
			{
				// inserts
				if( $data['product']->hasBeenCustomised() )
				{
					// custominsert
				}
				else
				{
					// standardinserts
				}
			}
		}
	}
	
	
	
}



?>