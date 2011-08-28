<?php

class Form_AdminProfile extends SimpleCart_Form {

  public function loadDefaultDecorators(){
    $this->setDecorators(array(
      'FormElements',
      array('Description', array('tag'=>'p','class'=>'form-help')),
      array('Fieldset', array()),
      array('Form', array('id'=>'form-profile','class'=>'validate', 'accept-charset'=>'utf-8'))

    ));
  }

  public function init(){
    parent::init();

    $element = new Zend_Form_Element_Text('firstname');
    $element->setLabel('First Name:');
    $element->setDescription("Enter your first name.");
    $element->setRequired(true);
    $element->setFilters(array('StringTrim', 'StripTags'));
    $element->addValidator('NotEmpty', true, array('messages'=>array(
      Zend_Validate_NotEmpty::INVALID      => "Invalid type given. String, integer or float expected",
      Zend_Validate_NotEmpty::IS_EMPTY    => "First name is required."
    )));
    $element->addValidator('StringLength', true, array('max'=>32,'messages'=>array(
      Zend_Validate_StringLength::INVALID      => "Invalid type given. String, integer or float expected",
      Zend_Validate_StringLength::TOO_LONG    => "Must be no more than %max% characters."
    )));
    $element->setAttribs(array(
      'size' => 32,
      'maxlength' => 32,
      'autofocus' => 'autofocus',
      'data' => Zend_Json::encode(array('validate'=>array(
        'required' => true,
        'messages' => array(
          'required' => 'First name is required.',
          'maxlength' => 'Must be no more than 32 characters.'
        )
      )))
    ));
    $element->setDecorators($this->field);
    $this->addElement($element);

    $element = new Zend_Form_Element_Text('lastname');
    $element->setLabel('Last Name:');
    $element->setDescription("Enter your last name.");
    $element->setRequired(true);
    $element->setFilters(array('StringTrim', 'StripTags'));
    $element->addValidator('NotEmpty', true, array('messages'=>array(
      Zend_Validate_NotEmpty::INVALID      => "Invalid type given. String, integer or float expected",
      Zend_Validate_NotEmpty::IS_EMPTY    => "Last name is required."
    )));
    $element->addValidator('StringLength', true, array('max'=>64,'messages'=>array(
      Zend_Validate_StringLength::INVALID      => "Invalid type given. String, integer or float expected",
      Zend_Validate_StringLength::TOO_LONG    => "Must be no more than %max% characters."
    )));
    $element->setAttribs(array(
      'size' => 40,
      'maxlength' => 64,
      'data' => Zend_Json::encode(array('validate'=>array(
        'required' => true,
        'messages' => array(
          'required' => 'Last name is required.',
          'maxlength' => 'Must be no more than 64 characters.'
        )
      )))
    ));
    $element->setDecorators($this->field);
    $this->addElement($element);

    $element = new Form_Element_Email('email');
    $element->setLabel('Email Address:');
    $element->setDescription("Enter your email address.");
    $element->setRequired(true);
    $element->setFilters(array('StringTrim', 'StripTags'));
    $element->addValidator('NotEmpty', true, array('messages'=>array(
      Zend_Validate_NotEmpty::INVALID      => "Invalid type given. String, integer or float expected",
      Zend_Validate_NotEmpty::IS_EMPTY    => "Email Address is required."
    )));
    $element->addValidator('StringLength', true, array('max'=>127,'messages'=>array(
      Zend_Validate_StringLength::INVALID      => "Invalid type given. String, integer or float expected",
      Zend_Validate_StringLength::TOO_LONG    => "Must be no more than %max% characters."
    )));
    $element->addValidator($this->validator['email'], true);
    $element->setAttribs(array(
      'size' => 50,
      'maxlength' => 127,
      'data' => Zend_Json::encode(array('validate'=>array(
        'required' => true,
        'email' => true,
        'messages' => array(
          'required' => 'Email Address is required.',
          'maxlength' => 'Must be no more than 127 characters.'
        )
      )))
    ));
    $element->setDecorators($this->field);
    $this->addElement($element);

    $element = new Zend_Form_Element_Submit('submit');
    $element->setLabel('Save Profile');
    $element->setDecorators($this->button);
    $element->setIgnore(TRUE);
    $element->setAttrib('class', 'ui-button ui-widget ui-state-default ui-corner-all');
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