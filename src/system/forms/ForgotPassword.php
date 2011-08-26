<?php

class Form_ForgotPassword extends SimpleCart_Form {

  public function loadDefaultDecorators(){
    $this->setDecorators(array(
      'FormElements',
      array('Description', array('tag'=>'p','class'=>'form-help')),
      array('Fieldset', array()),
      array('DijitForm', array('id'=>'form-forgot-password', 'accept-charset'=>'utf-8'))

    ));
  }

  public function init(){
    parent::init();
    $this->getView()->dojo()->requireModule('dojox.validate.regexp');


    $validator = new Validate_Doctrine_RecordExists(array(
      'table' => 'Admin',
      'field' => 'email'
    ));
    $validator->setMessages(array(
      Validate_Doctrine_RecordExists::ERROR_NO_RECORD_FOUND => "Account with email address %value% not found."
    ));
    $element = new Form_Element_Email('email');
    $element->setLabel('Email Address:');
    $element->setDescription("Enter your email address.");
    $element->setRequired(true);
    $element->addValidator('NotEmpty', true, array('messages'=>array(
      Zend_Validate_NotEmpty::INVALID      => "Invalid type given. String, integer or float expected",
      Zend_Validate_NotEmpty::IS_EMPTY    => "Email Address is required."
    )));
    $element->addValidator($this->validator['email'], true);
    $element->addValidator($validator, true);
    $element->setMaxLength(127);
    $element->setTrim(true);
    $element->setInvalidMessage("Not a valid email address.");
    $element->setDecorators($this->field);
    $element->setDijitParam('regExpGen', 'dojox.validate.regexp.emailAddress');
    $this->addElement($element);

    $element = new Zend_Dojo_Form_Element_SubmitButton('submit');
    $element->setLabel('Send Password Request');
    $element->setDecorators($this->button);
    $element->setIgnore(TRUE);
    $this->addElement($element);

    $this->addElement('hash', 'csrf', array('ignore' => true, 'decorators'=>$this->hidden));

  }
}