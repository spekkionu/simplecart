<?php

abstract class SimpleCart_Controller extends Zend_Controller_Action {

  /**
   * Config onject
   * @var Zend_Config $config
   */
  protected $config;

  /**
   * Acess Control instance
   * @var Zend_Acl $acl
   */
  protected $acl;

  /**
   * Logger
   * @var Zend_Log $logger
   */
  protected $logger;

  /**
   * Error Logger
   * @var Zend_Log $error_logger
   */
  protected $error_logger;

  /**
   * Identity of logged in user
   * @var object $identity
   */
  protected $identity = null;

  /**
   * Flash Messenger plugin
   * @var Zend_Controller_Action_Helper_FlashMessenger $flashMessenger
   */
  protected $flashMessenger = null;

  /**
   * Controller init
   */
  public function init(){
    $this->config = Zend_Registry::get('config');
    $auth = Zend_Auth::getInstance();
    if($auth->hasIdentity()){
      $this->identity = $auth->getIdentity();
    }
    $this->view->identity = $this->identity;
    $this->acl = Zend_Registry::get('Zend_Acl');
    $this->logger = new Zend_Log(new Zend_Log_Writer_Stream($this->config->system.'/logs/access.log'));
    $this->error_logger = new Zend_Log(new Zend_Log_Writer_Stream($this->config->system.'/logs/error.log'));
    $this->flashMessenger = $this->_helper->getHelper('FlashMessenger');
    $this->view->site_info = array(
      'name' => $this->config->site->name,
      'domain' => $this->config->site->domain
    );
    if($this->config->ssl->enable && $_SERVER['REMOTE_PORT'] == $this->config->ssl->port){
      // This is a ssl request
      $this->view->site_info['domain'] = $this->config->ssl->domain;
    }
    $this->view->site_info['url'] = ($_SERVER['REMOTE_PORT'] == 443)?'https://':'http://' . $this->view->site_info['domain'].$this->view->baseUrl();
  }

  /**
   * Sends response as JSON string
   * @param mixed $data
   * @return void
   */
  protected function sendJson($data){
    return $this->_helper->getHelper('json')->sendJson($data);
  }

  /**
   * Log a message at a priority
   *
   * @param  string   $message   Message to log
   * @param  integer  $priority  Priority of message
   * @return void
   * @throws Zend_Log_Exception
   */
  protected function log($message, $priority = Zend_Log::INFO){
    $this->logger->log($message, $priority);
  }

  /**
   * Log an error
   *
   * @param  string   $message   Message to log
   * @param  integer  $priority  Priority of message
   * @return void
   * @throws Zend_Log_Exception
   */
  protected function logError($message, $priority = Zend_Log::ERR){
    $this->error_logger->log($message, $priority);
  }

  /**
   * Checks if logged in user has access to the requested resource.
   * @param string $resource
   * @param string $privilege
   * @return bool
   */
  protected function isAllowed($resource=null, $privilege=null){
    $auth = Zend_Auth::getInstance();
    if(!$auth->hasIdentity()) return false;
    $identity = $auth->getIdentity();
    return $this->acl->isAllowed($identity->accesslevel, $resource, $privilege);
  }

  /**
   * Redirect to URL of the form /module/controller/action/params
   *
   * @param  string $action
   * @param  string $controller
   * @param  string $module
   * @param  array  $params
   * @return void
   */
  protected function redirect($action, $controller = null, $module = null, array $params = array()) {
    $redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');
    $redirector->gotoSimpleAndExit($action, $controller, $module, $params);
  }

  /**
   * Redirects to a named route
   *
   * @param  string  $name Route name
   * @param  array   $urlOptions
   * @param  boolean $reset
   * @return void
   */
  protected function routeRedirect($name = null, array $urlOptions = array(), $reset = false) {
    $redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');
    $redirector->gotoRouteAndExit($urlOptions, $name, $reset);
  }

  /**
   * Adds a flashMessage
   * @param string $message
   * @param string $type The message type (used as class for container)
   */
  protected function addMessage($message, $type='alert') {
    $this->flashMessenger->resetNamespace();
    $this->flashMessenger->addMessage(array($type => $message));
  }

  /**
   * Returns any flash messages
   * @return array
   */
  protected function getMessages() {
    $this->flashMessenger->resetNamespace();
    $messages = $this->flashMessenger->getMessages();
    return $messages;
  }

  /**
   * Adds a custom flash message that will not be loaded by default
   * @param string $message
   */
  protected function addCustomFlashMessage($message) {
    $this->flashMessenger->setNamespace('custom-flash-messages');
    $this->flashMessenger->addMessage($message);
    $this->flashMessenger->resetNamespace();
  }

  /**
   * Returns any custom flash messages
   * @return array
   */
  protected function getCustomFlashMessages() {
    $this->flashMessenger->setNamespace('custom-flash-messages');
    $messages = $this->flashMessenger->getMessages();
    $this->flashMessenger->resetNamespace();
    return $messages;
  }

}