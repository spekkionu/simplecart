<?php

/**
 * Error controller
 *
 * @package Simplecart
 * @subpackage Controller
 * @author spekkionu
 * @license New BSD http://www.opensource.org/licenses/bsd-license.php
 */
class ErrorController extends SimpleCart_FrontController
{

  public function errorAction() {
    $errors = $this->_getParam('error_handler');

    switch ($errors->type) {
      case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
      case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
        // 404 error -- controller or action not found
        return $this->_forward('not-found');
        break;
      default:
        // application error; display error page, but don't change status code
        break;
    }
  }

  public function notFoundAction() {
    $this->getResponse()->setRawHeader('HTTP/1.1 404 Not Found');
  }

}