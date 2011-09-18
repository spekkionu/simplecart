<?php
/**
 * Forgot password form
 * 
 * Used in both front end and admin
 *
 * @package Simplecart
 * @subpackage Form
 * @author spekkionu
 */
class Form_ForgotPassword extends SimpleCart_Form
{

  public function loadDefaultDecorators() {
    $this->setDecorators(array(
      'FormElements',
      array('Description', array('tag' => 'p', 'class' => 'form-help')),
      array('Fieldset', array()),
      array('Form', array('id' => 'form-forgot-password', 'class' => 'validate', 'accept-charset' => 'utf-8'))
    ));
  }

  public function init() {
    parent::init();

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
    $element->setFilters(array('StringTrim', 'StripTags'));
    $element->addValidator('NotEmpty', true, array('messages' => array(
        Zend_Validate_NotEmpty::INVALID => "Invalid type given. String, integer or float expected",
        Zend_Validate_NotEmpty::IS_EMPTY => "Email Address is required."
      )));
    $element->addValidator($this->validator['email'], true);
    $element->addValidator($validator, true);
    $element->setAttribs(array(
      'size' => 50,
      'maxlength' => 127,
      'autofocus' => 'autofocus',
      'data' => Zend_Json::encode(array('validate' => array(
          'required' => true,
          'email' => true,
          'messages' => array(
            'required' => 'Email Address is required.'
          )
        )))
    ));
    $element->setDecorators($this->field);
    $this->addElement($element);

    $element = new Zend_Form_Element_Submit('submit');
    $element->setLabel('Send Password Request');
    $element->setDecorators($this->button);
    $element->setAttrib('class', 'ui-button ui-widget ui-state-default ui-corner-all');
    $element->setIgnore(TRUE);
    $this->addElement($element);

    $this->addElement('hash', 'csrf', array('ignore' => true, 'decorators' => $this->hidden));
  }

}