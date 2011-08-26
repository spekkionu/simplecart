<?php

class Form_Element_Time extends Zend_Form_Element {
  public function init() {
    $this->addPrefixPath('Decorator', 'Decorator/', 'decorator');
  }

  /**
   * Set element value
   *
   * @param  mixed $value
   * @return Zend_Form_Element
   */
  public function setValue($value)
  {
    if(!$value) return $value;
    $date = $value['hours'] . ':'.$value['minutes'].' ' . $value['am'];
    $this->_value = $date;
    return $this;
  }


}