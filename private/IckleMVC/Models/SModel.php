<?php
namespace IckleMVC\Models;
abstract class SModel {

    public function __construct( IckleRegistry $registry )
    {
    	
    }
    
    abstract protected function buildFromSQL( $sql );
}
?>