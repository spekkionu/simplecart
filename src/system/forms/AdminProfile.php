<?php

class Form_AdminProfile extends SimpleCart_Form {

  public function loadDefaultDecorators(){
    $this->setDecorators(array(
      'FormElements',
      array('Description', array('tag'=>'p','class'=>'form-help')),
      array('Fieldset', array()),
      array('DijitForm', array('id'=>'form-profile', 'accept-charset'=>'utf-8'))

    ));
  }

  public function init(){
    parent::init();
    $this->getView()->dojo()->requireModule('dojox.validate.regexp');

    $element = new Zend_Dojo_Form_Element_ValidationTextBox('firstname');
    $element->setLabel('First Name:');
    $element->setDescription("Enter your first name.");
    $element->setRequired(true);
    $element->addValidator('NotEmpty', true, array('messages'=>array(
      Zend_Validate_NotEmpty::INVALID      => "Invalid type given. String, integer or float expected",
      Zend_Validate_NotEmpty::IS_EMPTY    => "First name is required."
    )));
    $element->setMaxLength(32);
    $element->setTrim(true);
    $element->setDecorators($this->field);
    $this->addElement($element);

    $element = new Zend_Dojo_Form_Element_ValidationTextBox('lastname');
    $element->setLabel('Last Name:');
    $element->setDescription("Enter your last name.");
    $element->setRequired(true);
    $element->addValidator('NotEmpty', true, array('messages'=>array(
      Zend_Validate_NotEmpty::INVALID      => "Invalid type given. String, integer or float expected",
      Zend_Validate_NotEmpty::IS_EMPTY    => "Last name is required."
    )));
    $element->setMaxLength(64);
    $element->setTrim(true);
    $element->setDecorators($this->field);
    $this->addElement($element);

    $element = new Form_Element_Email('email');
    $element->setLabel('Email Address:');
    $element->setDescription("Enter your email address.");
    $element->setRequired(true);
    $element->addValidator('NotEmpty', true, array('messages'=>array(
      Zend_Validate_NotEmpty::INVALID      => "Invalid type given. String, integer or float expected",
      Zend_Validate_NotEmpty::IS_EMPTY    => "Email Address is required."
    )));
    $element->addValidator($this->validator['email']);
    $element->setMaxLength(127);
    $element->setTrim(true);
    $element->setInvalidMessage("Not a valid email address.");
    $element->setDecorators($this->field);
    $element->setDijitParam('regExpGen', 'dojox.validate.regexp.emailAddress');
    $this->addElement($element);

    $element = new Zend_Dojo_Form_Element_SubmitButton('submit');
    $element->setLabel('Save Profile');
    $element->setDecorators($this->button);
    $element->setIgnore(TRUE);
    $element->setDijitParam('iconClass', 'icon icon-save');
    $this->addElement($element);

    $this->addElement('hash', 'csrf', array('ignore' => true, 'decorators'=>$this->hidden));

  }
  
  /**
   * Adds database validators
   * @param int $id Account to exclude from unique requirements
   * @return Form_AdminProfile
   */
  public function addDbValidators($id = null){
    $validator = new Validate_Doctrine_NoRecordExists(array(
      'table' => 'Admin',
      'field' => 'email'
    ));
    if($id){
      $validator->setExclude(array('field'=>'id', 'value' => $id));
    }
    $validator->setMessages(array(
      Validate_Doctrine_RecordExists::ERROR_RECORD_FOUND => "Another account exists with this email address."
    ));
    $this->getElement('email')->addValidator($validator, true);
    return $this;
  }
}