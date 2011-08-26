<?php

class Form_Login extends SimpleCart_Form {

  public function loadDefaultDecorators(){
    $this->setDecorators(array(
      'FormElements',
      array('Description', array('tag'=>'p','class'=>'form-help')),
      array('Fieldset', array('legend'=>'Login Form')),
      array('DijitForm', array('id'=>'form-login', 'accept-charset'=>'utf-8'))

    ));
  }

  public function init(){
    parent::init();

    $element = new Zend_Dojo_Form_Element_ValidationTextBox('username');
    $element->setLabel('Username:');
    $element->setDescription("Enter your username.");
    $element->setRequired(true);
    $element->addValidator('NotEmpty', true, array('messages'=>array(
      Zend_Validate_NotEmpty::INVALID      => "Invalid type given. String, integer or float expected",
      Zend_Validate_NotEmpty::IS_EMPTY    => "Username is required."
    )));
    $element->addValidator('Alnum', false, array('messages'=>array(
      Zend_Validate_Alnum::INVALID      => "Invalid type given. String, integer or float expected",
      Zend_Validate_Alnum::NOT_ALNUM    => "May only contain letters and numbers",
      Zend_Validate_Alnum::STRING_EMPTY => "'%value%' is an empty string"
    )));
    $element->setMaxLength(20);
    $element->setRegExp("[a-zA-Z0-9]+");
    $element->setInvalidMessage("May only contain letters and numbers.");
    $element->setTrim(true);
    $element->setFilters(array('StringTrim', 'StripTags'));
    $element->setDecorators($this->field);
    $element->setAttribs(array(
      'maxlength' => 20
    ));
    $this->addElement($element);

    $element = new Zend_Dojo_Form_Element_PasswordTextBox('password');
    $element->setLabel('Password:');
    $element->setDescription("Enter your password.");
    $element->setRequired(true);
    $element->addValidator('NotEmpty', true, array('messages'=>array(
      Zend_Validate_NotEmpty::INVALID      => "Invalid type given. String, integer or float expected",
      Zend_Validate_NotEmpty::IS_EMPTY    => "Password is required."
    )));
    $element->setTrim(true);
    $element->setRegExp("[\S]+");
    $element->setInvalidMessage("Cannot contain spaces.");
    $element->setFilters(array('StringTrim', 'StripTags'));
    $element->setDecorators($this->field);
    $element->setAttribs(array(
      'maxlength' => 20
    ));
    $this->addElement($element);

    $element = new Zend_Dojo_Form_Element_SubmitButton('submit');
    $element->setLabel('Login');
    $element->setDecorators($this->button);
    $element->setDijitParam('iconClass', 'icon icon-login');
    $this->addElement($element);

    $this->addElement('hash', 'csrf', array('ignore' => true, 'decorators'=>$this->hidden));

  }
}