<?php

abstract class SModel {

    public function __construct( IckleRegistry $registry )
    {
    	
    }
    
    abstract protected function buildFromSQL( $sql );
}
?>