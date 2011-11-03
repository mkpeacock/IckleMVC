<?php
/**
 * Ickle MVC Framework
 * index.php
 * @author Michael Peacock
 */
error_reporting( -1 );
define( "PUBLIC_PATH", dirname( __FILE__ ) ."/" );
require_once('../private/bootstrap.php');
$bootstrap = new Bootstrap();
//$bootstrap->processRequest();
//require_once( FRAMEWORK_PATH . 'models/content/content.php' );
//require_once( FRAMEWORK_PATH . 'models/content/page.php' );
//$page = new Pagecontent( $bootstrap->getRegistry(), 0, 'test-page' );
//require_once( FRAMEWORK_PATH . 'models/ecommerce/purchasable.php' );
//require_once( FRAMEWORK_PATH . 'models/ecommerce/product.php' );
//require_once( FRAMEWORK_PATH . 'models/ecommerce/purchasable.php' );
//$product = new Product( $bootstrap->getRegistry(), 1 );
//echo $content->generateSQL();
//print $bootstrap->getRegistry()->getObject('template')->generateOutput()->getPage()->getContent();


?>
