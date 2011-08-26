<?php

class SimpleCart_Form extends Zend_Dojo_Form {

  public $validator = array();

  public $field = array(
    'DijitElement',
    array('Description', array('tag' => 'div', 'class'=>'form-description', 'placement'=>'append', 'escape'=>false)),
    'Errors',
    array(array('element'=>'HtmlTag'), array('tag' => 'div', 'class'=>'form-element')),
    array(array('label-container-open'=>'HtmlTag'), array('tag' => 'div', 'class'=>'form-label', 'placement'=>'prepend', 'closeOnly'=>true)),
    //array('DijitTooltip', array('tag' => 'span', 'class'=>'form-tooltip', 'placement'=>'prepend', 'escape'=>false)),
    array('Label', array('placement'=>'prepend')),
    array(array('label-container-close'=>'HtmlTag'), array('tag' => 'div', 'class'=>'form-label', 'placement'=>'prepend', 'openOnly'=>true)),
    array(array('row'=>'HtmlTag'), array('tag' => 'div', 'class'=>'form-row'))
  );

  public $inline = array(
    'DijitElement',
    array('Description', array('tag' => 'div', 'class'=>'form-description', 'placement'=>'append', 'escape'=>false)),
    'Errors',
    //array('DijitTooltip', array('tag' => 'span', 'class'=>'form-tooltip', 'placement'=>'prepend', 'escape'=>false)),
    array('Label', array('placement'=>'prepend')),
    array(array('element'=>'HtmlTag'), array('tag' => 'div', 'class'=>'form-element')),
    array(array('row'=>'HtmlTag'), array('tag' => 'div', 'class'=>'form-row'))
  );

  public $hidden = array(
    'ViewHelper'
  );

  public $button = array(
    'DijitElement',
    array(array('element'=>'HtmlTag'), array('tag' => 'div', 'class'=>'form-element')),
    array(array('row'=>'HtmlTag'), array('tag' => 'div', 'class'=>'form-row'))
  );

  public $buttonOpen = array(
    'DijitElement',
    array('element'=>'HtmlTag', array('tag' => 'div', 'class'=>'form-element', 'openOnly'=>true)),
    array(array('row'=>'HtmlTag'), array('tag' => 'div', 'class'=>'form-row', 'openOnly'=>true))
  );

  public $buttonMiddle = array(
    'DijitElement'
  );

  public $buttonClose = array(
    'DijitElement',
    array('HtmlTag', array('tag' => 'div', 'class'=>'form-element',  'closeOnly'=>true)),
    array(array('row'=>'HtmlTag'), array('tag' => 'div', 'class'=>'form-row', 'closeOnly'=>true))
  );



  public function init(){
    $this->setAction($this->getView()->url());
    $this->setMethod('post');
    Zend_Dojo::enableForm($this);
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

}