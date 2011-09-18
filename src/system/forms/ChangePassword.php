<?php
/**
 * Change password form
 * 
 * Used in both front end and admin
 *
 * @package Simplecart
 * @subpackage Form
 * @author spekkionu
 */
class Form_ChangePassword extends SimpleCart_Form
{

  public function loadDefaultDecorators() {
    $this->setDecorators(array(
      'FormElements',
      array('Description', array('tag' => 'p', 'class' => 'form-help')),
      array('Fieldset', array()),
      array('Form', array('id' => 'form-change-password', 'class' => 'validate', 'accept-charset' => 'utf-8'))
    ));
  }

  public function init() {
    parent::init();

    $element = new Zend_Form_Element_Text('pin');
    $element->setLabel('PIN:');
    $element->setDescription("Enter the 4 digit PIN number sent yo your email.");
    $element->setRequired(true);
    $element->setFilters(array('StringTrim', 'StripTags'));
    $element->addValidator('NotEmpty', true, array('messages' => array(
        Zend_Validate_NotEmpty::INVALID => "Invalid type given. String, integer or float expected",
        Zend_Validate_NotEmpty::IS_EMPTY => "PIN number is required."
      )));
    $element->addValidator('Regex', true, array('pattern' => '/\d{4}/', 'messages' => array(
        Zend_Validate_Regex::NOT_MATCH => "Must be a 4 digit number."
      )));
    $element->setAttribs(array(
      'size' => 4,
      'maxlength' => 4,
      'autofocus' => 'autofocus',
      'data' => Zend_Json::encode(array('validate' => array(
          'required' => true,
          'digits' => true,
          'minlength' => 4,
          'maxlength' => 4,
          'messages' => array(
            'required' => 'PIN number is required.',
            'digits' => 'Must be a 4 digit number.',
            'minlength' => 'Must be a 4 digit number.',
            'maxlength' => 'Must be a 4 digit number.'
          )
        )))
    ));
    $element->setDecorators($this->field);
    $this->addElement($element);

    $element = new Zend_Form_Element_Password('old_password');
    $element->setLabel('Current Password:');
    $element->setDescription("Enter your current password.");
    $element->setRequired(true);
    $element->setFilters(array('StringTrim'));
    $element->addValidator('NotEmpty', true, array('messages' => array(
        Zend_Validate_NotEmpty::INVALID => "Invalid type given. String, integer or float expected",
        Zend_Validate_NotEmpty::IS_EMPTY => "Current password is required."
      )));
    $element->setAttribs(array(
      'size' => 20,
      'maxlength' => 20,
      'data' => Zend_Json::encode(array('validate' => array(
          'required' => true,
          'nowhitespace' => true,
          'messages' => array(
            'required' => 'Current password is required.',
            'nowhitespace' => 'Cannot contain spaces.'
          )
        )))
    ));
    $element->setDecorators($this->field);
    $this->addElement($element);

    $element = new Zend_Form_Element_Password('password');
    $element->setLabel('New Password:');
    $element->setDescription("Enter your desired new password.");
    $element->setRequired(true);
    $element->addValidator('NotEmpty', true, array('messages' => array(
        Zend_Validate_NotEmpty::INVALID => "Invalid type given. String, integer or float expected",
        Zend_Validate_NotEmpty::IS_EMPTY => "New password is required."
      )));
    $element->addValidator($this->validator['nospace'], true);
    $element->addValidator('StringLength', true, array('messages' => array(
        Zend_Validate_StringLength::INVALID => "Invalid type given. String, integer or float expected",
        Zend_Validate_StringLength::TOO_SHORT => "Must be at least %min% characters.",
        Zend_Validate_StringLength::TOO_LONG => "Must be no more than %max% characters."
      )));
    $element->setAttribs(array(
      'size' => 20,
      'maxlength' => 20,
      'data' => Zend_Json::encode(array('validate' => array(
          'required' => true,
          'nowhitespace' => true,
          'maxlength' => 20,
          'minlength' => 4,
          'password' => true,
          'messages' => array(
            'required' => 'New password is required.',
            'nowhitespace' => 'Cannot contain spaces.'
          )
        )))
    ));
    $element->setDecorators($this->password_strength);
    $this->addElement($element);

    $element = new Zend_Form_Element_Password('confirm_password');
    $element->setLabel('Repeat Password:');
    $element->setDescription("Repeat your desired new password.");
    $element->setRequired(true);
    $element->setFilters(array('StringTrim'));
    $element->addValidator('NotEmpty', true, array('messages' => array(
        Zend_Validate_NotEmpty::INVALID => "Invalid type given. String, integer or float expected",
        Zend_Validate_NotEmpty::IS_EMPTY => "You must repeat your password"
      )));
    $element->addValidator('Identical', true, array('token' => 'password', 'messages' => array(
        Zend_Validate_Identical::NOT_SAME => 'Passwords do not match',
        Zend_Validate_Identical::MISSING_TOKEN => 'Passwords do not match'
      )));
    $element->setAttribs(array(
      'size' => 20,
      'maxlength' => 20,
      'data' => Zend_Json::encode(array('validate' => array(
          'required' => true,
          'nowhitespace' => true,
          'equalTo' => "#password",
          'messages' => array(
            'required' => 'You must repeat your password.',
            'nowhitespace' => 'Cannot contain spaces.',
            'equalTo' => 'Passwords do not match'
          )
        )))
    ));
    $element->setDecorators($this->field);
    $this->addElement($element);

    $element = new Zend_Form_Element_Submit('submit');
    $element->setLabel('Change Password');
    $element->setDecorators($this->button);
    $element->setIgnore(TRUE);
    $element->setAttrib('class', 'ui-button ui-widget ui-state-default ui-corner-all');
    $this->addElement($element);

    $this->addElement('hash', 'csrf', array('ignore' => true, 'decorators' => $this->hidden));
  }

}