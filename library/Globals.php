<?php
/**
 * Class used to centralise some of our common functionality
 * 
 * @author Yomi (baphled) Colledge
 * @version $Id$
 *
 */
class Globals {
	
	/**
	 * @property Zend_Cache $_cache		Used to store our cache
	 */
	static private $_cache;
	
	/**
	 * Gets our cache
	 * 
	 * @return Zend_Cache
	 * 
	 */
	static function getCache() {
		if (self::$_cache) {
			return self::$_cache;
		}
		
		self::$_cache = Zend_Cache::factory(
			'Core',
			'File',
			array('lifetime'=>60*60, 'automatic_serialization'=>true),
			array('cache_dir'=>realpath(dirname(__FILE__) .'/../cache/'))
		);
			
		return self::$_cache;
	}
}