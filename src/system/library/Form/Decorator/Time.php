<?php
class Decorator_Time extends Zend_Form_Decorator_Abstract {

          
  public function buildInput() {

    $element = $this->getElement();
    $options = array(
      '1' => '1',
      '2' => '2',
      '3' => '3',
      '4' => '4',
      '5' => '5',
      '6' => '6',
      '7' => '7',
      '8' => '8',
      '9' => '9',
      '10' => '10',
      '11' => '11',
      '12' => '12'
    );
    $attribs = $element->getAttribs();
    $name = $element->getName().'[hours]';
    $value = $element->getValue();
    $date = ($value) ? new Zend_Date($value, 'h:mm a') : null;
    $hour = ($date) ? $date->toString('g', 'php') : null;
    $hours = $element->getView()->formSelect($name, $hour, $attribs, $options);
    $options = array();
    for($i = 0; $i < 60; $i++){
      $min = str_pad($i, 2, '0', STR_PAD_LEFT);
      $options[$min] = $min;
    }
    $name = $element->getName().'[minutes]';
    $min = ($date) ? $element->getView()->dateFormat($value, 'i') : null;
    $minutes = $element->getView()->formSelect($name, $min, $attribs, $options);
    $name = $element->getName().'[am]';
    $a = ($date) ? $element->getView()->dateFormat($value, 'A') : null;
    $am = $element->getView()->formSelect($name, $a, $attribs, array('AM'=>'AM','PM'=>'PM'));

    return $hours .$this->getSeparator(). $minutes .$this->getSeparator(). $am;
  }

  public function render($content){
    $element = $this->getElement();
    if (!$element instanceof Zend_Form_Element) {
      return $content;
    }
    if (null === $element->getView()) {
      return $content;
    }
    return $this->buildInput();
  }

}