<?php
/**
 * Product class
 * 
 * @author Michael Peacock
 * @copyright Michael Peacock
 * @package E-Commerce
 * @subpackage products
 */
class Product extends Content implements Purchasable {
	
	/**
	 * Product cost
	 * @var double
	 */
	protected $cost=0;
	
	/**
	 * Product variations and their cost implications
	 * @var array
	 */
	protected $variations = array();
	
	/**
	 * Shipping costs and their associated shipping methods
	 * @var array
	 */
	private $shippingCosts = array();
	
	/**
	 * Can this product be customised?
	 * @var bool
	 */
	protected $customisable=false;
	
	/**
	 * Products weight [KG assumed]
	 * @var double
	 */
	protected $weight;
	
	/**
	 * Tax rate object
	 * @var Object
	 */
	protected $tax;

	
	/**
	 * Stores default currency, used for all product costs
	 * @var String
	 */
	protected $currency;
	
	/**
	 * Prices: one per currency
	 * @var array
	 */
	protected $prices=array();
	
	/**
	 * Price variations: copy of costVariations on a per currency basis
	 * @var array
	 */
	protected $variationsPerCurrency = array();
	
	protected $shippingCostsPerCurrency = array();
	
	/**
	 * 
	 */
	public function __construct( IckleRegistry $registry, $productID=0 )
	{
		$this->registry = $registry;
		if( $productID > 0 )
		{
			$fields = ", FORMAT(p.cost,2) as product_cost ";
			$tables = " ec_products p, ";
			$joins = "";
			$conditions = " p.ID=v.ID AND ";
			$sql = parent::generateSQL( $fields, $tables, $joins, $conditions, 'product', $productID );
			$this->registry->getObject('db')->executeQuery( $sql );
			if( $this->registry->getObject('db')->getNumRows() == 1 )
			{
				$data = $this->registry->getObject('db')->getRows();
				$this->cost = $data['product_cost'];
				parent::__construct( $this->registry, $data );		
			}
			echo '<pre>' . print_r( $this->getData(), true ) . '</pre>';
		}
	}
	
	/**
	 * Calculate the prices in multiple currencies
	 * @param array $currencies
	 * @return void
	 */
	public function calculatePrices( $currencies = array() )
	{
		foreach( $currencies as $currency )
		{
			$this->prices[ $currency->getName() ] = number_format( $this->cost * $currency->getMultiplier(), 2 );
			foreach( $this->variations as $cv )
			{
				// how will cost variations be structured? should they be objects too?
			}
		}
	}
	
	/**
	 * 
	 */
	public function getID()
	{
		
	}
	
	/**
	 * 
	 */
	public function getType()
	{
		
	}
	
	/**
	 * 
	 */
	public function getCost()
	{
		
	}
	
	/**
	 * 
	 */
	public function hasBeenCustomised()
	{
		
	}
	
	/**
	 * Is the product customisable?
	 * @return bool
	 */
	public function isCustomisable()
	{
		return $this->customisable;
	}
	
	/**
	 * Get an array of the core data, calling this ignores all private parent variables :-)
	 * @param String $prefix
	 * @return array
	 */
	public function getData( $prefix='' )
	{
		$tor = array();
		foreach( $this as $key => $data )
		{
			if( ! is_array( $data ) && ! is_object( $data ) )
			{
				$tor[ $prefix . $key ] = $data;
			}
		}
		return $tor;
	}
	
	public function save()
	{
		// lots to do here!
		if( $this->id == 0 )
		{
			
		}
		else
		{
			
		}
		
		foreach( $this->variations as $variation )
		{
			// do something
		}
	}

	
	
	
	
}





?>