<?php

define('TESTDIR', dirname(dirname(__FILE__)));
define('DATADIR', TESTDIR.DIRECTORY_SEPARATOR.'data');
define('SYSTEM', dirname(TESTDIR).DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR.'system');


// Set Include Path, must include phpunit
set_include_path(
  // Application Library Files
  SYSTEM . DIRECTORY_SEPARATOR.'library'.PATH_SEPARATOR.
  get_include_path()
);

require(SYSTEM.'/library/sfYaml/sfYaml.php' );
require(SYSTEM.'/library/Doctrine.php' );

// Set up autoload.
require( SYSTEM.'/library/Zend/Loader/Autoloader.php' );
$autoloader = Zend_Loader_Autoloader::getInstance();
$autoloader->registerNamespace('Doctrine')->pushAutoloader(array('Doctrine', 'autoload'), 'Doctrine');
$autoloader->pushAutoloader(array('Doctrine', 'modelsAutoload'));
$autoloader->pushAutoloader(array('Doctrine', 'extensionsAutoload'));
$autoloader->setFallbackAutoloader(true);

// Load Config
$config = new Zend_Config_Yaml( SYSTEM.'/configs/config.yml', null, array('allow_modifications'=>true, 'yaml_decoder' => array('sfYaml', 'load')));
$config->system = SYSTEM;
Zend_Registry::set('config', $config);

// Set Character sets and encoding values
ini_set('default_charset', 'UTF-8');

// Set Timezone
date_default_timezone_set('America/Los_Angeles');


SimpleCart_Cache::setConfig($config->cache->toArray());
SimpleCart_Cache::setCacheDir(DATADIR.DIRECTORY_SEPARATOR.'cache');

// Set Locale
Zend_Locale::setDefault('en_US');
$locale = new Zend_Locale('en_US');
Zend_Registry::set('Zend_Locale', $locale);

// Add Form Autoloader Resource
$resourceLoader = new Zend_Loader_Autoloader_Resource(array(
    'basePath'  => SYSTEM,
    'namespace' => '',
));
$resourceLoader->addResourceType('form', 'forms/', 'Form');

error_reporting(0);
ini_set('display_errors', 0);
// Turn off error logging
ini_set('error_log', DATADIR . '/logs/php_errors.log');
ini_set('log_errors', 0);

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
require(SYSTEM.'/models/EmailTemplates.php');
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
$config->database->dsn = "sqlite:///".DATADIR."/cache/testdb.db?mode=666";
// Connect to database
$conn = Doctrine_Manager::connection($config->database->dsn);
Doctrine_Core::loadModels(SYSTEM.'/models');

