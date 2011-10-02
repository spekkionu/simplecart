<?php
/**
 * States Options Class
 *
 * @package    Simplecart
 * @author     spekkionu
 * @license    New BSD http://www.opensource.org/licenses/bsd-license.php
 */
class SimpleCart_States {

	private static $file = null;

	private static $states = null;

	/**
	 * Constructor is static for a static class
	 * @return unknown_type
	 */
	private function __construct(){}

	/**
	 * Sets the xml file to use to load states
	 * @param string $file
	 * @return void
	 */
	public static function setXML($file = null){
    if(is_null($file)) $file = dirname(__FILE__) . '/Options/states.xml';
		if(!is_file($file)) throw new Exception('States XML file does not exist.');
		self::$file = realpath($file);
		self::$states = null;
	}

	/**
	 * Loads the states from the xml file.
	 * @return void
	 */
	private static function loadStates(){
		if(is_null(self::$file)) self::setXML();
    // Load xml file
	  $xml = simplexml_load_file(self::$file);
	  $states = array();
	  // loop through states
	  foreach($xml as $state){
	  	$states[(string) $state->code] = array(
	  	  'code' => (string) $state->code,
	  	  'name' => (string) $state->name,
	  	  'isus' => ($state->isus == 'yes') ? true : false
	  	);
	  }
	  // Cache states
	  self::$states = $states;
	  // Clear xml instance
	  unset($xml, $states, $state);
	}

	/**
	 * Clears the states from the cache.
	 * @return void
	 */
	public static function clearCache(){
		self::$states = null;
	}

  /**
   * Checks if data has been cached
   * @return boolean
   */
  public static function isCached(){
    return is_null(self::$states);
  }

	/**
	 * Returns array of states
	 * @param bool $us_only
	 * @return array
	 */
	public static function getArray($us_only = true){
		// Load States if they are not yet loaded.
		if(is_null(self::$states)) self::loadStates();
		$states = self::$states;
		foreach($states as $key=>$value){
			if($us_only && !$value['isus']) unset($states[$key]);
		}
		return $states;
	}

	/**
	 * Returns states as key=>value pair array
	 * @param bool $us_only
	 * @return array
	 */
	public static function getPairs($us_only = true){
		// Load States if they are not yet loaded.
    if(is_null(self::$states)) self::loadStates();
    $states = array();
    foreach(self::$states as $key=>$state){
    	if(!$us_only || $state['isus']){
    		$states[$state['code']] = $state['name'];
    	}
    }
    return $states;
	}

	/**
	 * Returns a single state by abbreviation
	 * @param string $state
	 * @param bool $name_only
	 * @return string|array
	 */
	public static function getState($state, $name_only = true){
		// Load States if they are not yet loaded.
    if(is_null(self::$states)) self::loadStates();
    if(array_key_exists($state, self::$states)){
    	return ($name_only) ? self::$states[$state]['name'] : self::$states[$state];
    }else{
    	return false;
    }
	}

}