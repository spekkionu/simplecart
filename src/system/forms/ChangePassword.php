<?php

class Form_ChangePassword extends SimpleCart_Form {

  public function loadDefaultDecorators(){
    $this->setDecorators(array(
      'FormElements',
      array('Description', array('tag'=>'p','class'=>'form-help')),
      array('Fieldset', array()),
      array('DijitForm', array('id'=>'form-change-password', 'accept-charset'=>'utf-8'))

    ));
  }

  public function init(){
    parent::init();

    $element = new Zend_Dojo_Form_Element_ValidationTextBox('pin');
    $element->setLabel('PIN:');
    $element->setDescription("Enter the 4 digit PIN number sent yo your email.");
    $element->setRequired(true);
    $element->addValidator('NotEmpty', true, array('messages'=>array(
      Zend_Validate_NotEmpty::INVALID      => "Invalid type given. String, integer or float expected",
      Zend_Validate_NotEmpty::IS_EMPTY    => "PIN number is required."
    )));
    $element->addValidator('Regex', true, array('pattern'=>'/\d{4}/', 'messages'=>array(
      Zend_Validate_Regex::NOT_MATCH => "Must be a 4 digit number."
    )));
    $element->setTrim(true);
    $element->setRegExp("\d{4}");
    $element->setInvalidMessage("Must be a 4 digit number.");
    $element->setDecorators($this->field);
    $element->setAttribs(array(
      'size' => 4,
      'maxlength' => 4
    ));
    $this->addElement($element);

    $element = new Zend_Dojo_Form_Element_PasswordTextBox('old_password');
    $element->setLabel('Current Password:');
    $element->setDescription("Enter your current password.");
    $element->setRequired(true);
    $element->addValidator('NotEmpty', true, array('messages'=>array(
      Zend_Validate_NotEmpty::INVALID      => "Invalid type given. String, integer or float expected",
      Zend_Validate_NotEmpty::IS_EMPTY    => "Current password is required."
    )));
    $element->setTrim(true);
    $element->setRegExp("[\S]+");
    $element->setInvalidMessage("Cannot contain spaces.");
    $element->setDecorators($this->field);
    $this->addElement($element);

    $element = new Zend_Dojo_Form_Element_PasswordTextBox('password');
    $element->setLabel('New Password:');
    $element->setDescription("Enter your desired new password.");
    $element->setRequired(true);
    $element->addValidator('NotEmpty', false, array('messages'=>array(
      Zend_Validate_NotEmpty::INVALID      => "Invalid type given. String, integer or float expected",
      Zend_Validate_NotEmpty::IS_EMPTY    => "New password is required."
    )));
    $element->addValidator($this->validator['nospace'], true);
    $element->setTrim(true);
    $element->setRegExp("[\S]+");
    $element->setInvalidMessage("Cannot contain spaces.");
    $element->setDecorators($this->field);
    $this->addElement($element);

    $element = new Zend_Dojo_Form_Element_PasswordTextBox('confirm_password');
    $element->setLabel('Repeat Password:');
    $element->setDescription("Repeat your desired new password.");
    $element->setRequired(true);
    $element->addValidator('NotEmpty', true, array('messages'=>array(
      Zend_Validate_NotEmpty::INVALID      => "Invalid type given. String, integer or float expected",
      Zend_Validate_NotEmpty::IS_EMPTY    => "You must repeat your password"
    )));
    $element->addValidator('Identical', true, array('token'=>'password', 'messages'=>array(
      Zend_Validate_Identical::NOT_SAME => 'Passwords do not match',
      Zend_Validate_Identical::MISSING_TOKEN => 'Passwords do not match'
    )));
    $element->setTrim(true);
    $element->setRegExp("[\S]+");
    $element->setInvalidMessage("Cannot contain spaces.");
    $element->setDecorators($this->field);
    $this->addElement($element);

    $element = new Zend_Dojo_Form_Element_SubmitButton('submit');
    $element->setLabel('Change Password');
    $element->setDecorators($this->button);
    $element->setIgnore(TRUE);
    $element->setDijitParam('iconClass', 'icon icon-key');
    $this->addElement($element);

    $this->addElement('hash', 'csrf', array('ignore' => true, 'decorators'=>$this->hidden));

  }
}