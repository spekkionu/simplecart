<?php

/**
 * Route URL helper
 *
 * @package Simplecart
 * @subpackage View_Helper
 * @author spekkionu
 * @license New BSD http://www.opensource.org/licenses/bsd-license.php
 */
class Zend_View_Helper_Route extends Zend_View_Helper_Abstract
{

  /**
   * Generates an url given the name of a route.
   *
   * @access public
   * @param  mixed $name The name of a Route to use. If null it will use the current Route
   * @param  array $urlOptions Options passed to the assemble method of the Route object.
   * @param  bool $reset Whether or not to reset the route defaults with those provided
   * @return string Url for the link href attribute.
   */
  function route($route="default", array $urlOptions = array(), $reset = true, $encode = true) {
    return $this->view->url($urlOptions, $route, $reset, $encode);
  }

}