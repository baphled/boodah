<?php
require_once dirname(__FILE__) .'/libs/TestHelper.php';
require_once 'unit/IntranetTestSuite.php';
require_once 'application/default/controllers/IndexControllerTest.php';
/**
 * Static test suite.
 * @author Yomi (baphled) Colledge <yomi@boodah.net> 2008-2009
 * @version $Id: AllTests.php 5 2009-04-25 19:42:42Z baphled $
 * @package Zend_PHPUnit_Scaffolding
 * @subpackage TestSuite_AllTests
 *
 * $LastChangedBy: baphled $
 */
class AllTests extends PHPUnit_Framework_TestSuite {
	
	/**
	 * Constructs the test suite handler.
	 */
	public function __construct() {
		$this->setName ( 'AllTests' );
		$this->addTestSuite ( 'IntranetTestSuite' );
		$this->addTestSuite ( 'IndexControllerTest' );		
	}
	
	/**
	 * Creates the suite.
	 */
	public static function suite() {
		return new self ( );
	}
}
