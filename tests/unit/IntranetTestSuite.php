<?php
require_once dirname(__FILE__) .'/../libs/TestHelper.php';
require_once 'phpunit_fixture/PHPUnitFixturesUnitSuite.php';

/**
 * Static test suite.
 * @version $Id: IntranetTestSuite.php 5 2009-04-25 19:42:42Z baphled $
 * @package Zend_PHPUnit_Scaffolding
 * 
 * $LastChangedBy: baphled $
 */
class IntranetTestSuite extends PHPUnit_Framework_TestSuite {
	
	/**
	 * Constructs the test suite handler.
	 */
	public function __construct() {
		$this->setName ( 'IntranetTestSuite' );

		$this->addTestSuite ( 'PHPUnitFixturesUnitSuite' );
	
	}
	
	/**
	 * Creates the suite.
	 */
	public static function suite() {
		return new self ( );
	}
}

