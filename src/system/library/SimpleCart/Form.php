<?php
/**
 * Base form class
 *
 * @package    Simplecart
 * @subpackage Form
 * @author     spekkionu
 * @license    New BSD http://www.opensource.org/licenses/bsd-license.php
 */
class SimpleCart_Form extends ZendX_JQuery_Form {

  public $validator = array();

  public $field = array(
    'ViewHelper',
    array('Description', array('tag' => 'div', 'class'=>'form-description', 'placement'=>'append', 'escape'=>false)),
    'Errors',
    array(array('element'=>'HtmlTag'), array('tag' => 'div', 'class'=>'form-element')),
    array(array('label-container-open'=>'HtmlTag'), array('tag' => 'div', 'class'=>'form-label', 'placement'=>'prepend', 'closeOnly'=>true)),
    array('Label', array('placement'=>'prepend')),
    array(array('label-container-close'=>'HtmlTag'), array('tag' => 'div', 'class'=>'form-label', 'placement'=>'prepend', 'openOnly'=>true)),
    array(array('row'=>'HtmlTag'), array('tag' => 'div', 'class'=>'form-row'))
  );

  public $password_strength = array(
    array(array('password_meter_bar'=>'HtmlTag'), array('tag' => 'div', 'class'=>'password-meter-bar')),
    array(array('password_meter_bg'=>'HtmlTag'), array('tag' => 'div', 'class'=>'password-meter-bg')),
    array(array('password_meter_message'=>'HtmlTag'), array('tag' => 'div', 'class'=>'password-meter-message', 'placement'=>'prepend')),
    array(array('password_meter'=>'HtmlTag'), array('tag' => 'div', 'class'=>'password-meter')),
    array('ViewHelper', array('placement'=>'prepend')),
    array('Description', array('tag' => 'div', 'class'=>'form-description', 'placement'=>'append', 'escape'=>false)),
    'Errors',
    array(array('element'=>'HtmlTag'), array('tag' => 'div', 'class'=>'form-element form-element-password')),
    array(array('label-container-open'=>'HtmlTag'), array('tag' => 'div', 'class'=>'form-label', 'placement'=>'prepend', 'closeOnly'=>true)),
    array('Label', array('placement'=>'prepend')),
    array(array('label-container-close'=>'HtmlTag'), array('tag' => 'div', 'class'=>'form-label', 'placement'=>'prepend', 'openOnly'=>true)),
    array(array('row'=>'HtmlTag'), array('tag' => 'div', 'class'=>'form-row'))
  );

  public $inline = array(
    'ViewHelper',
    array('Description', array('tag' => 'div', 'class'=>'form-description', 'placement'=>'append', 'escape'=>false)),
    'Errors',
    array('Label', array('placement'=>'prepend', 'class'=>'form-label')),
    array(array('element'=>'HtmlTag'), array('tag' => 'div', 'class'=>'form-element')),
    array(array('row'=>'HtmlTag'), array('tag' => 'div', 'class'=>'form-row'))
  );

  public $hidden = array(
    'ViewHelper'
  );

  public $button = array(
    'ViewHelper',
    array(array('element'=>'HtmlTag'), array('tag' => 'div', 'class'=>'form-element')),
    array(array('row'=>'HtmlTag'), array('tag' => 'div', 'class'=>'form-row'))
  );

  public $buttonOpen = array(
    'ViewHelper',
    array('element'=>'HtmlTag', array('tag' => 'div', 'class'=>'form-element', 'openOnly'=>true)),
    array(array('row'=>'HtmlTag'), array('tag' => 'div', 'class'=>'form-row', 'openOnly'=>true))
  );

  public $buttonMiddle = array(
    'ViewHelper'
  );

  public $buttonClose = array(
    'ViewHelper',
    array('HtmlTag', array('tag' => 'div', 'class'=>'form-element',  'closeOnly'=>true)),
    array(array('row'=>'HtmlTag'), array('tag' => 'div', 'class'=>'form-row', 'closeOnly'=>true))
  );



  public function init(){
    $this->setAction($this->getView()->url());
    $this->setMethod('post');
    ZendX_JQuery::enableForm($this);
    $this->addElementPrefixPath('Form_Decorator', 'Form/Decorator/', 'decorator');

    $this->validator['required'] = new Zend_Validate_NotEmpty();
    $this->validator['required']->setMessages(array(
      Zend_Validate_NotEmpty::IS_EMPTY => 'Required Field.'
    ));
    $this->validator['email'] = new Zend_Validate_EmailAddress(Zend_Validate_Hostname::ALLOW_ALL);
    $this->validator['email']->setMessages(array(
      Zend_Validate_EmailAddress::INVALID            => "'%value%' is not a valid email address.",
      Zend_Validate_EmailAddress::INVALID_HOSTNAME   => "'%value%' is not a valid email address.",
      Zend_Validate_EmailAddress::INVALID_MX_RECORD  => "'%value%' is not a valid email address.",
      Zend_Validate_EmailAddress::DOT_ATOM           => "'%value%' is not a valid email address.",
      Zend_Validate_EmailAddress::QUOTED_STRING      => "'%value%' is not a valid email address.",
      Zend_Validate_EmailAddress::INVALID_LOCAL_PART => "'%value%' is not a valid email address.",
      Zend_Validate_EmailAddress::LENGTH_EXCEEDED    => "'%value%' is not a valid email address."
    ));

    $this->validator['alnum'] = new Zend_Validate_Alnum();
    $this->validator['alnum']->setMessages(array(
      Zend_Validate_Alnum::NOT_ALNUM => 'Can only contain alphabetic and numeric characters.'
    ));

    $this->validator['slug'] = new Zend_Validate_Regex("/[a-z0-9-\\/]*/i");
    $this->validator['slug']->setMessages(array(
      Zend_Validate_Regex::NOT_MATCH => 'Can only contain alphabetic and numeric characters and "/", and "-".'
    ));

    $this->validator['nospace'] = new Zend_Validate_Regex('/^\S*$/i');
    $this->validator['nospace']->setMessages(array(
      Zend_Validate_Regex::NOT_MATCH => "Cannot contain spaces."
    ));

  }

  /**
   * Returns array of form validation error messages for use in json response
   * @return array
   */
  public function getJsonErrors(){
    $array = array();
    foreach($this->getMessages() as $element=>$messages){
      $array[$element] = implode("<br />", $messages);
    }
    unset($element, $messages);
    return $array;
  }

  /**
   * Add error class to invalid elements
   * @param array $data
   * @return boolean
   */
  public function isValid($data){
    $valid = parent::isValid($data);
    if(!$valid){
      foreach($this->getMessages() as $element=>$messages){
        $class = $this->getElement($element)->getAttrib('class');
        if(!$class){
          $class = 'error';
        }else{
          // Check if already has error class
          $classes = explode(' ', $class);
          if(!in_array('error', $classes)){
            // Does not already have error class
            $class .= ' error';
          }
        }
        $this->getElement($element)->setAttrib('class', $class);
      }
    }
    return $valid;
  }

}