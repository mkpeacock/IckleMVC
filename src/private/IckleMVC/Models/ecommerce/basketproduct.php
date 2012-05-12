<?php

class Basketproduct extends Product {

	private $additionalCost; // incorporates customisations
	private $subtotal; // additional + product
	private $taxAmount; // tax on product + tax on customisations
	private $total; // subtotal + taxAmount
		
	/**
	 * Has this product instance been customised by the customer?
	 * @var bool
	 */
	private $customised = false;
	
	public function __construct( IckleRegistry $registry, $id=0 )
	{
		
	}
	
		
	public function hasBeenCustomised()
	{
		return $this->customised;
	}
	
	public function calculateCosts()
	{
		
	}
	
}


?>