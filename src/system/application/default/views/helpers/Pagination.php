<?php
class Zend_View_Helper_Pagination extends Zend_View_Helper_Abstract{
  
  
  /**
   * Returns a url to be used to link to a module/controller/action.
   *  Uses The standard url helper but defaults to reset true.
   *
   * @param Zend_Paginator $data Instance of Zend_Paginator
   * @param array $params An array with key=>value to use as the url parameters
     * @return string Url for the link href attribute.
   */
  function pagination(Zend_Paginator $data, $params = array()){
    return $this->view->paginationControl($data, null, null, array('params'=>$params));
  }
}