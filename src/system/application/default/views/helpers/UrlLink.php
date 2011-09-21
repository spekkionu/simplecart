<?php

/**
 * Url helper
 *
 * @package Simplecart
 * @subpackage View_Helper
 * @author spekkionu
 * @license New BSD http://www.opensource.org/licenses/bsd-license.php
 */
class Zend_View_Helper_UrlLink extends Zend_View_Helper_Abstract
{

  /**
   * Returns a url to be used to link to a module/controller/action.
   *  Uses The standard url helper but defaults to reset true.
   *
   * @param string $module Used as the module for the link.  Use empty string or null if default module.
   * @param string $controller Used as the controller
   * @param string $action Used as the action
   * @param array $params An array with key=>value to use as the url parameters
   * @return string Url for the link href attribute.
   */
  function urlLink($module="", $controller="", $action="", $params = array()) {
    $urlOptions = array();
    if (!empty($module))
      $urlOptions['module'] = $module;
    if (!empty($controller))
      $urlOptions['controller'] = $controller;
    if (!empty($action))
      $urlOptions['action'] = $action;
    $urlOptions = $urlOptions + $params;
    return $this->view->url($urlOptions, 'default', true, true);
  }

}