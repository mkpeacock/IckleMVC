<?php
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