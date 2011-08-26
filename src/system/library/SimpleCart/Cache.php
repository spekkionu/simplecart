<?php

class SimpleCart_Cache {

  private static $manager = null;
  private static $cachedir = null;
  private static $config;

  /**
   * Returns Zend Cache Manager Instance
   * @return Zend_Cache_Manager
   */
  public static function getManager(){
    if(is_null(self::$manager)){
      // Init Manager
      self::$manager = new Zend_Cache_Manager;
      $front = array('automatic_serialization'=>true, 'cache_id_prefix' => 'default_');
      if(isset(self::$config['store']['lifetime']) && self::$config['store']['lifetime'] > 0){
        $front['lifetime'] = intval(self::$config['store']['lifetime']);
      }else{
        $front['lifetime'] = null;
      }
      $cachedir = self::$cachedir.DIRECTORY_SEPARATOR.'zend'.DIRECTORY_SEPARATOR.'default';
      if(!is_dir($cachedir)){
        // Directory does not exist, try to create it
        if(@mkdir($cachedir, 0777, true) === false){
          throw new Exception('Cache directory does not exist and could not be created.');
        }
      }
      $cachedir = realpath($cachedir);
      $front['caching'] = (bool)self::$config['store']['enabled'];
      $options = array('frontend'=>array('name'=>'Core','options'=>$front),'backend'=>array('name'=>'File','options'=>array('cache_dir'=>$cachedir)));
      self::$manager->setCacheTemplate('default', $options);
      $options['frontend']['options']['cache_id_prefix'] = Zend_Cache_Manager::PAGETAGCACHE.'_';
      $options['backend']['options']['cache_dir'] = self::$cachedir.DIRECTORY_SEPARATOR.'zend'.DIRECTORY_SEPARATOR.Zend_Cache_Manager::PAGETAGCACHE;
      $cachedir = $options['backend']['options']['cache_dir'];
      if(!is_dir($cachedir)){
        // Directory does not exist, try to create it
        if(@mkdir($cachedir, 0777, true) === false){
          throw new Exception('Cache directory does not exist and could not be created.');
        }
      }
      self::$manager->setTemplateOptions(Zend_Cache_Manager::PAGETAGCACHE, $options);
    }
    return self::$manager;
  }

  /**
   * Sets Cache Config Options
   * @param array $config
   */
  public static function setConfig(array $config){
    self::$config = $config;
  }

  /**
   * Sets the cache directory
   * @param string $path
   * @return void
   */
  public static function setCacheDir($path){
    if(!is_dir($path)){
      throw new Exception('Cache directory does not exist.');
    }
    self::$cachedir = realpath($path);
  }

  public static function getMenuCache(){
    $manager = self::getManager();
    if($manager->hasCache('menu')){
      // Cache already set, return it
      return $manager->getCache('menu');
    }else{
      // Create Cache and store it for later use
      $cache = self::createCache(self::$config['menu'], self::$cachedir . DIRECTORY_SEPARATOR . 'menu', 'menu');
      $manager->setCache('menu', $cache);
      return $cache;
    }
  }

  /**
   * Returns Route Cache
   * @return Zend_Cache_Core
   */
  public static function getRouteCache(){
    $manager = self::getManager();
    if($manager->hasCache('routes')){
      // Cache already set, return it
      return $manager->getCache('routes');
    }else{
      // Create Cache and store it for later use
      $cache = self::createCache(self::$config['routes'], self::$cachedir . DIRECTORY_SEPARATOR . 'routes', 'routes');
      $manager->setCache('routes', $cache);
      return $cache;
    }
  }

  /**
   * Creates a cache instance
   * @param array $config
   * @param string $cachedir
   * @return Zend_Cache_Core
   */
  private static function createCache(array $config, $cachedir, $key){
    if(!is_dir($cachedir)){
      // Directory does not exist, try to create it
      if(@mkdir($cachedir, 0777, true) === false){
        throw new Exception('Cache directory does not exist and could not be created.');
      }
    }
    $cachedir = realpath($cachedir);
    $front = array('automatic_serialization'=>true, 'cache_id_prefix' => $key.'_');
    if(isset($config['lifetime']) && $config['lifetime'] > 0){
      $front['lifetime'] = intval($config['lifetime']);
    }else{
      $front['lifetime'] = null;
    }
    $front['caching'] = (bool)$config['enabled'];
    if($config['type'] == 'File'){
      $cache = Zend_Cache::factory('Core', 'File', $front, array('cache_dir'=>$cachedir));
    }else{
      // Use Two Level Cache
      $cache = Zend_Cache::factory('Core', 'Two Levels', $front, array(
        'slow_backend' => 'File',
        'slow_backend_options' => array(
          'cache_dir'=> $cachedir
        ),
        'fast_backend' => $config['type'],
        'fast_backend_custom_naming' => (isset($config['custom']) && $config['custom']),
        'fast_backend_options' => $config['options']
      ));
    }
    return $cache;
  }

  /**
   * Returns the cache instance for the given key
   * @param string $key
   * @return Zend_Cache_Core
   */
  public static function getCache($key){
    $key = preg_replace("/[^a-z0-9_]/i", '', strtolower($key));
    if(!$key) throw new Exception('Must provide a valid key.');
    $manager = self::getManager();
    if($manager->hasCache($key)){
      // Cache already set, return it
      return $manager->getCache($key);
    }else{
      // Create Cache and store it for later use
      $cache = self::createCache(self::$config['store'], self::$cachedir.DIRECTORY_SEPARATOR.'zend'.DIRECTORY_SEPARATOR.$key, $key);
      $manager->setCache($key, $cache);
      return $cache;
    }
  }

  /**
   * Returns htmlpurifier cache directory
   * @param string $key The instance path
   * @return string
   */
  public static function getHtmlPurifierCache($key = 'default'){
    $key = preg_replace("/[^a-z0-9_]/i", '', strtolower($key));
    if(!$key) $key = 'default';
    if(is_null(self::$cachedir)) throw new Exception('Cache directory not set.');
    if(!is_dir(self::$cachedir . DIRECTORY_SEPARATOR . 'htmlpurifier' . DIRECTORY_SEPARATOR.$key)){
      // Directory does not exist, try to create it
      if(@mkdir(self::$cachedir . DIRECTORY_SEPARATOR . 'htmlpurifier' . DIRECTORY_SEPARATOR.$key, 0777, true) === false){
        throw new Exception('HTML Purifier cache directory does not exist and could not be created.');
      }
    }
    return self::$cachedir . DIRECTORY_SEPARATOR . 'htmlpurifier' . DIRECTORY_SEPARATOR.$key;
  }


}
