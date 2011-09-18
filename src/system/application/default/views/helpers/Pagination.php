<?php

/**
 * Pagination View Helper
 *
 * @package Simplecart
 * @subpackage View_Helper
 * @author spekkionu
 */
class Zend_View_Helper_Pagination extends Zend_View_Helper_Abstract
{

  /**
   * Renders pagination
   *
   * @param Zend_Paginator $pager Instance of Zend_Paginator
   * @param array $params An array with key=>value to use as the url parameters
   * @return string Pagination control
   */
  function pagination(Zend_Paginator $pager, $params = array()) {
    return $this->view->paginationControl($pager, null, null, array('params' => $params));
  }

}