<?php

class Zend_View_Helper_EmailTextBox extends Zend_View_Helper_FormText {

  /**
   * HTML element type
   * @var string
   */
  protected $_elementType = 'email';

  public function emailTextBox($id, $value = null, array $attribs = array()){
    return parent::formText($id, $value, $attribs);
  }
}