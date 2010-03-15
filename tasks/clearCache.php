<?php
/**
 * @author Yomi (baphled) Akindayini
 * 
 * CLI method used to manage our applications cache
 * Flags
 *  -m Model
 *  -c Controller
 *  -a Action
 *  -v Verbose
 *  all Clear all caches
 * 
 */
require_once 'Zend/Loader.php';
Zend_Loader::registerAutoload();

$libs = array();
$libs[] = realpath(dirname(__FILE__) .'/../application/');
$libs[] = realpath(dirname(__FILE__) .'/../application/default/models/');
$libs[] = realpath(dirname(__FILE__) .'/../library/');
$libs[] = realpath(dirname(__FILE__) .'/../init/');

$inc_path = implode(PATH_SEPARATOR, $libs);
set_include_path($inc_path .PATH_SEPARATOR .get_include_path());

$rules = array();
$rules['m|module-s'] = 'The optional module to use.';
$rules['c|controller-s'] = 'The optional controller to use.';
$rules['a|action-s'] = 'The optional action to use.';

$rules['all'] = 'If set, the entire cache will be destroyed.';
$rules['v|verbose'] = 'If set, exceptions and other debugging information will be displayed.';

$root = realpath(dirname(__FILE__) . '/../');
        
try {
	$request = new Baphled_Controller_Request_Cli($rules);
	$front = Zend_Controller_Front::getInstance();
	$front ->setParam('noViewRenderer',true);
	$front->setParam('noErrorHandler',true);
	$front->setRouter(new Baphled_Controller_Router());
	$front->setRequest($request);
	$front->setResponse(new Zend_Controller_Response_Cli());
	$front->setControllerDirectory($root .'/application/default/controllers/');
	$front->throwExceptions($request->getOption('verbose'));
	$front->dispatch();
} catch (Exception $e) {
	echo 'Unexpected error: ';
	echo 'Exception:' . $e->getMessage() .'\n';
	echo $e->getTraceAsString();
	die($e->getCode());
}

die(0);