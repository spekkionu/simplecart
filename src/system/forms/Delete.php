<?php

class Form_Delete extends SimpleCart_Form {

  public function loadDefaultDecorators(){
    $this->setDecorators(array(
      'FormElements',
      array('Description', array('tag'=>'p','class'=>'form-help')),
      array('Fieldset', array()),
      array('Form', array('id'=>'form-category','class'=>'validate', 'accept-charset'=>'utf-8'))

    ));
  }

  public function init(){
    parent::init();

    $element = new Zend_Form_Element_Submit('submit');
    $element->setLabel('Confirm Delete');
    $element->setDecorators($this->button);
    $element->setIgnore(TRUE);
    $element->setAttrib('class', 'ui-button ui-widget ui-state-default ui-corner-all');
    $this->addElement($element);

    $this->addElement('hash', 'csrf', array('ignore' => true, 'decorators'=>$this->hidden));

  }
}