<?
/**
 * IndexControllerTest - Test the default index controller
 * 
 * @author
 * @version 
 */
require_once '../../../libs/TestHelper.php';

/**
 * IndexController Test Case
 */
class IndexControllerTest extends Zend_Test_PHPUnit_ControllerTestCase {
	
	/**
	 * Prepares the environment before running a test.
	 */
	public function appBootstrap() {
		$this->frontController->registerPlugin ( new Initializer() );
	}
	
	/**
	 * Tests FooController->barAction()
	 */
	public function testIndexAction() {
		$this->dispatch ( '/' );
		$this->assertController ( 'index' );
		$this->assertAction ( 'index' );
	}
}