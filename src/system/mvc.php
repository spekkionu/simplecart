<?php
require(dirname(__FILE__).'/application.php');

// Set Controller Paths
$controller = Zend_Controller_Front::getInstance();
$controller->setControllerDirectory(array(
  'default' => SYSTEM . '/application/default/controllers',
  'admin' => SYSTEM . '/application/admin/controllers'
));

// Setup Router with custom routes
$router = $controller->getRouter();
$route_config = new Zend_Config_Yaml( SYSTEM.'/configs/routes.yml', null, array('allow_modifications'=>false));
$router->addConfig($route_config);

// Set Error Reporting
$controller->throwExceptions( (bool) $config->debug->display_errors );
// Set Base Url
if($config->ssl->enable && $_SERVER['REMOTE_PORT'] == $config->ssl->port){
  // This is a ssl request
  $controller->setBaseUrl($config->ssl->base_url);
}else{
  // This is not an ssl request
  $controller->setBaseUrl($config->base_url);
}
$controller->setParam('disableOutputBuffering', true);

// register the default action helpers
Zend_Controller_Action_HelperBroker::addPath(SYSTEM . '/application/default/helpers', 'Zend_Controller_Action_Helper');

// Init Layout
$layout = Zend_Layout::startMvc();
$layout->setLayoutPath(SYSTEM.'/application/default/views/layout');
$view = $layout->getView();
$view->addHelperPath(SYSTEM . '/application/default/views/helpers', 'Zend_View_Helper');
ZendX_JQuery::enableView($view);
$view->jQuery()->enable();

// Setup Navidation Defaults
Zend_View_Helper_Navigation_HelperAbstract::setDefaultAcl($acl);
if(Zend_Auth::getInstance()->hasIdentity()){
  Zend_View_Helper_Navigation_HelperAbstract::setDefaultRole(Zend_Auth::getInstance()->getIdentity()->accesslevel);
}else{
  Zend_View_Helper_Navigation_HelperAbstract::setDefaultRole('guest');
}

if($config->debug->debug_bar){
  $debug = new ZFDebug_Controller_Plugin_Debug(array(
    'jquery_path' => $view->baseUrl('assets/scripts/jquery/jquery-1.6.2.min.js'),
    'plugins' => array(
      'Variables',
      'Html',
      'Log',
      'File' => array('base_path' => SYSTEM),
      'Danceric_Controller_Plugin_Debug_Plugin_Doctrine',
      'Memory',
      'Time',
      'ZFDebug_Controller_Plugin_Debug_Plugin_Auth' => array('user'=>'username','role'=>'accesslevel'),
      'Exception'
    )
  ));
  $controller->registerPlugin($debug);
}


// distpatch controller
$controller->dispatch();