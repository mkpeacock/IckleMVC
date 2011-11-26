<?php
/**
 * Currency S model
 * 
 * @author Michael Peacock
 */
class Currencies {
	
	private $registry;
	private $default;
	private $currencies=array();
	
	public function __construct( IckleRegistry $registry )
	{
		$this->registry = $registry;
	}
	
	public function getCurrencies()
	{
		require_once( FRAMEWORK_PATH . 'ecommerce/currency.php' );
		$sql = "";
		$this->registry->getObject('db')->executeQuery( $sql );
		if( $this->registry->getObject('db')->getNumRows() > 0 )
		{
			while( $row = $this->registry->getObject('db')->getRows() )
			{
				$currency = new Currency( $this->registry, 0 );
				
				
				$this->currencies[] = $currency;
				if( $row['default_currency'] == 1 )
				{
					$this->default = $currency;
				}
			}
			
		}
		return $this->currencies;
	}
	
	
	
	
	
}


?>