<?php
/**
 * Login form.
 *
 * Used in both admin and front end
 *
 * @package    Simplecart
 * @subpackage Form
 * @author     spekkionu
 * @license    New BSD http://www.opensource.org/licenses/bsd-license.php
 */
class Form_Login extends SimpleCart_Form
{

  public function loadDefaultDecorators() {
    $this->setDecorators(array(
      'FormElements',
      array('Description', array('tag' => 'p', 'class' => 'form-help')),
      array('Fieldset', array()),
      array('Form', array('id' => 'form-login', 'class' => 'validate', 'accept-charset' => 'utf-8'))
    ));
  }

  public function init() {
    parent::init();

    $element = new Zend_Form_Element_Text('username');
    $element->setLabel('Username:');
    $element->setDescription("Enter your username.");
    $element->setRequired(true);
    $element->addValidator('NotEmpty', true, array('messages' => array(
        Zend_Validate_NotEmpty::INVALID => "Invalid type given. String, integer or float expected",
        Zend_Validate_NotEmpty::IS_EMPTY => "Username is required."
      )));
    $element->addValidator('Alnum', true, array('messages' => array(
        Zend_Validate_Alnum::INVALID => "Invalid type given. String, integer or float expected",
        Zend_Validate_Alnum::NOT_ALNUM => "May only contain letters and numbers.",
        Zend_Validate_Alnum::STRING_EMPTY => "'%value%' is an empty string"
      )));
    $element->setFilters(array('StringTrim', 'StripTags'));
    $element->setDecorators($this->field);
    $element->setAttribs(array(
      'size' => 20,
      'maxlength' => 20,
      'autofocus' => 'autofocus',
      'data' => Zend_Json::encode(array('validate' => array(
          'required' => true,
          'alphanumeric' => true,
          'messages' => array(
            'required' => 'Username is required.',
            'alphanumeric' => 'May only contain letters and numbers.'
          )
        )))
    ));
    $this->addElement($element);

    $element = new Zend_Form_Element_Password('password');
    $element->setLabel('Password:');
    $element->setDescription("Enter your password.");
    $element->setRequired(true);
    $element->addValidator('NotEmpty', true, array('messages' => array(
        Zend_Validate_NotEmpty::INVALID => "Invalid type given. String, integer or float expected",
        Zend_Validate_NotEmpty::IS_EMPTY => "Password is required."
      )));
    $element->addValidator($this->validator['nospace'], true);
    $element->setFilters(array('StringTrim'));
    $element->setDecorators($this->field);
    $element->setAttribs(array(
      'size' => 20,
      'maxlength' => 20,
      'data' => Zend_Json::encode(array('validate' => array(
          'required' => true,
          'nowhitespace' => true,
          'messages' => array(
            'required' => 'Password is required.',
            'nowhitespace' => 'Cannot contain spaces.'
          )
        )))
    ));
    $this->addElement($element);

    $element = new Zend_Form_Element_Submit('submit');
    $element->setLabel('Login');
    $element->setDecorators($this->button);
    $element->setIgnore(TRUE);
    $element->setAttrib('class', 'ui-button ui-widget ui-state-default ui-corner-all');
    $this->addElement($element);

    $this->addElement('hash', 'csrf', array('ignore' => true, 'decorators' => $this->hidden));
  }

}