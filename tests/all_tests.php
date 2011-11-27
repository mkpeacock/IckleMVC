<?php

define( "FRAMEWORK_PATH", dirname( __FILE__ ) ."/../src/private/" );

require_once( FRAMEWORK_PATH . 'splClassLoader.php' );
$classLoader = new SplClassLoader('IckleMVC\Registry', FRAMEWORK_PATH );
$classLoader->register();
$classLoader = new SplClassLoader('IckleMVC\Models', FRAMEWORK_PATH );
$classLoader->register();
$classLoader = new SplClassLoader('IckleMVC\Controllers', FRAMEWORK_PATH );
$classLoader->register();
$classLoader = new SplClassLoader('IckleMVC\Libraries', FRAMEWORK_PATH );
$classLoader->register();
$classLoader = new SplClassLoader('IckleMVC\Views', FRAMEWORK_PATH );
$classLoader->register();

$defaultRegistryObjects = array(
									'db' => '\IckleMVC\Registry\Database_MySQL',
									'template' => '\IckleMVC\Registry\Template',
									'scope' => '\IckleMVC\Registry\PrimaryScope',
									'urlprocessor' => '\IckleMVC\Registry\URLProcessor',
									'frontmenu' => '\IckleMVC\Registry\FrontMenu',
									'contenttreebuilder' => '\IckleMVC\Registry\ContentTreeBuilder',
									'authentication' => '\IckleMVC\Registry\Authentication'
									

								);
								
								
require_once('test_constants.php');
require_once('../../simpletest/autorun.php');
define( "TESTS_PATH", dirname( __FILE__ ) ."/" );
class AllTests extends TestSuite {
    function AllTests() {
        $this->TestSuite('All tests');
        $this->addFile( TESTS_PATH . 'database_test.php');
    }
}
?>