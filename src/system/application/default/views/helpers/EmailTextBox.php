<?php

class Zend_View_Helper_EmailTextBox extends Zend_Dojo_View_Helper_ValidationTextBox {
  
  /**
   * HTML element type
   * @var string
   */
  protected $_elementType = 'email';
    
  public function emailTextBox($id, $value = null, array $params = array(), array $attribs = array()){
    return parent::validationTextBox($id, $value, $params, $attribs);
  }
}