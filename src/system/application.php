<?php

define('SYSTEM', dirname(__FILE__));
if(!defined('WEBROOT')){
  define('WEBROOT', $_SERVER['DOCUMENT_ROOT']);
}

// Set Include Path
set_include_path(
  // Application Library Files
  SYSTEM . DIRECTORY_SEPARATOR.'library'
);

require_once(SYSTEM.'/library/sfYaml/sfYaml.php' );
require_once(SYSTEM.'/library/Doctrine.php' );

// Set up autoload.
require_once( 'Zend/Loader/Autoloader.php' );
$autoloader = Zend_Loader_Autoloader::getInstance();
$autoloader->registerNamespace('Doctrine')->pushAutoloader(array('Doctrine', 'autoload'), 'Doctrine');
$autoloader->pushAutoloader(array('Doctrine', 'modelsAutoload'));
$autoloader->pushAutoloader(array('Doctrine', 'extensionsAutoload'));
$autoloader->pushAutoloader('smartyAutoload', 'Smarty_');
$autoloader->setFallbackAutoloader(true);

// Load Config
$config = new Zend_Config_Yaml( SYSTEM.'/configs/config.yml', null, array('allow_modifications'=>true, 'yaml_decoder' => array('sfYaml', 'load')));
$config->system = SYSTEM;
Zend_Registry::set('config', $config);

// Set Character sets and encoding values
ini_set('default_charset', 'UTF-8');

// Set Timezone
date_default_timezone_set($config->locale->timezone);


SimpleCart_Cache::setConfig($config->cache->toArray());
SimpleCart_Cache::setCacheDir(SYSTEM.DIRECTORY_SEPARATOR.'cache');

// Set Locale
Zend_Locale::setDefault($config->locale->locale);
$locale = new Zend_Locale($config->locale->locale);
Zend_Registry::set('Zend_Locale', $locale);

// Setup Cache Settings
$config->cache->cache_dir = realpath( SYSTEM.'/cache');

// Add Form Autoloader Resource
$resourceLoader = new Zend_Loader_Autoloader_Resource(array(
    'basePath'  => SYSTEM,
    'namespace' => '',
));
$resourceLoader->addResourceType('form', 'forms/', 'Form');

error_reporting($config->debug->error_reporting);
ini_set('display_errors', $config->debug->display_errors);

if($config->debug->error_logging){
  // Turn on error logging
  ini_set('error_log', SYSTEM . '/logs/php_errors.log');
  ini_set('log_errors', 1);
}

// Setup Default Mail Transport
if($config->mail->type == 'smtp'){
  // Use SMTP
  // Unset any empty settings
  foreach($config->mail->smtp->options as $key=>$value){
    if(is_null($value) || $value == ''){
      unset($config->mail->smtp->options->$key);
    }
  }
  Zend_Mail::setDefaultTransport(new Zend_Mail_Transport_Smtp($config->mail->smtp->server, $config->mail->smtp->options->toArray()));
}else{
  // Use Sendmail
  if(isset($config->mail->options->forcereturn) && $config->mail->options->forcereturn){
    Zend_Mail::setDefaultTransport(new Zend_Mail_Transport_Sendmail('-f'.$config->mail->options->forcereturn));
  }
}

// Setup Email Template class
require_once(SYSTEM.'/models/EmailTemplates.php');
EmailTemplates::setConfig($config->mail);
EmailTemplates::setTemplateDir(SYSTEM.'/configs/emails');

// Setup Doctrine
$manager = Doctrine_Manager::getInstance();
$manager->setAttribute(Doctrine_Core::ATTR_QUOTE_IDENTIFIER, true);
$manager->setAttribute(Doctrine_Core::ATTR_VALIDATE, Doctrine_Core::VALIDATE_ALL);
$manager->setAttribute( Doctrine_Core::ATTR_USE_NATIVE_ENUM, true );
$manager->setAttribute(Doctrine_Core::ATTR_MODEL_LOADING, Doctrine_Core::MODEL_LOADING_PEAR);
$manager->setAttribute(Doctrine_Core::ATTR_TABLE_CLASS_FORMAT, 'Table_%s');
$manager->setAttribute( Doctrine_Core::ATTR_AUTOLOAD_TABLE_CLASSES,true);
$manager->setAttribute(Doctrine_Core::ATTR_AUTO_ACCESSOR_OVERRIDE, true);
$manager->setCharset( 'utf8' );
$manager->setCollate( 'utf8_unicode_ci' );
// Set DSN
if($config->database->phptype == 'sqlite'){
  $config->database->dsn = "sqlite:///".SYSTEM."/cache/".$config->database->database.".sqlite?mode=666";
}else{
  $config->database->dsn = $config->database->phptype .
                        '://' . $config->database->username .
                        ':' . $config->database->password.
                        '@' . $config->database->hostspec .
                        '/' . $config->database->database .
                        '?' . http_build_query($config->database->options, '', '&');
}
// Connect to database
$conn = Doctrine_Manager::connection($config->database->dsn);
Doctrine_Core::loadModels(SYSTEM.'/models');

// Setup Query Profiling
if($config->debug->database){
  // Setup Query Profiler
  if($config->debug->firebug){
    $profiler = new Imind_Profiler_Doctrine_Firebug();
  }else{
    $profiler = new Doctrine_Connection_Profiler();
  }
  $conn->setListener($profiler);
}

// Setup Zend_Currency
$cache = SimpleCart_Cache::getCache('currency');
Zend_Currency::setCache($cache);
$currency = new Zend_Currency();
Zend_Registry::set('Zend_Currency', $currency);

// Start Session
Zend_Session::start();

// Initialize Auth Class
$auth = Zend_Auth::getInstance();
$auth->setStorage(new Zend_Auth_Storage_Session('simplecart_auth'));

// Initialize Acl
$acl = require_once(SYSTEM.'/configs/acl.php');
Zend_Registry::set('Zend_Acl', $acl);

/**
 * Recursive wrapper for stripslashes()
 *
 * @param mixed $value
 * @return mixed
 */
function stripslashes_deep(&$value){
  $value = is_array($value) ? array_map('stripslashes_deep', $value) : stripslashes($value);
  return $value;
}

if(function_exists('get_magic_quotes_gpc')){
  if(get_magic_quotes_gpc() or ( ini_get('magic_quotes_sybase') and ( strtolower(ini_get('magic_quotes_sybase')) != 'off' ) )){
    stripslashes_deep($_GET);
    stripslashes_deep($_POST);
  }
}else{
  function get_magic_quotes_gpc(){
    return 0;
  }
}
