<?php

class Form_Category extends SimpleCart_Form {

  public function loadDefaultDecorators(){
    $this->setDecorators(array(
      'FormElements',
      array('Description', array('tag'=>'p','class'=>'form-help')),
      array('Fieldset', array()),
      array('Form', array('id'=>'form-category','class'=>'validate', 'accept-charset'=>'utf-8'))

    ));
  }

  public function init(){
    parent::init();

    $element = new Zend_Form_Element_Text('name');
    $element->setLabel('Category Name:');
    $element->setDescription("Enter the name of the category.");
    $element->setRequired(true);
    $element->setFilters(array('StringTrim', 'StripTags'));
    $element->addValidator('NotEmpty', true, array('messages'=>array(
      Zend_Validate_NotEmpty::INVALID      => "Invalid type given. String, integer or float expected",
      Zend_Validate_NotEmpty::IS_EMPTY    => "Category name is required."
    )));
    $element->addValidator('StringLength', true, array('max'=>100,'messages'=>array(
      Zend_Validate_StringLength::INVALID      => "Invalid type given. String, integer or float expected",
      Zend_Validate_StringLength::TOO_LONG    => "Must be no more than %max% characters."
    )));
    $element->setAttribs(array(
      'size' => 40,
      'maxlength' => 100,
      'autofocus' => 'autofocus',
      'data' => Zend_Json::encode(array('validate'=>array(
        'required' => true,
        'messages' => array(
          'required' => 'Category name is required.',
          'maxlength' => 'Must be no more than 100 characters.'
        )
      )))
    ));
    $element->setDecorators($this->field);
    $this->addElement($element);

    $element = new Zend_Form_Element_Text('path');
    $element->setLabel('Category Path:');
    $element->setDescription("The path to the category page.");
    $element->setRequired(true);
    $element->addFilter('StripTags');
    $element->addFilter('Callback', array('callback'=>'Category::urlize'));
    $element->addFilter('StringTrim', array('charlist'=>"/\\"));
    $element->addValidator('NotEmpty', true, array('messages'=>array(
      Zend_Validate_NotEmpty::INVALID      => "Invalid type given. String, integer or float expected",
      Zend_Validate_NotEmpty::IS_EMPTY    => "Path is required."
    )));
    $element->addValidator('StringLength', true, array('max'=>255,'messages'=>array(
      Zend_Validate_StringLength::INVALID      => "Invalid type given. String, integer or float expected",
      Zend_Validate_StringLength::TOO_LONG    => "Must be no more than %max% characters."
    )));
    $element->setAttribs(array(
      'size' => 40,
      'maxlength' => 255,
      'data' => Zend_Json::encode(array('validate'=>array(
        'required' => true,
        'messages' => array(
          'required' => 'Path is required.',
          'maxlength' => 'Must be no more than 255 characters.'
        )
      )))
    ));
    $element->setDecorators($this->field);
    $this->addElement($element);

    $categories = Doctrine::getTable('Category')->getCategories();
    $element = new Form_Element_Tree('parent');
    $element->setLabel('Parent Category:');
    $element->setDescription("The parent category.");
    $element->setRequired(true);
    $element->setMultiOptions($categories);
    $element->setFilters(array('Digits'));
    $element->addValidator('NotEmpty', true, array('messages'=>array(
      Zend_Validate_NotEmpty::INVALID      => "Invalid type given. String, integer or float expected",
      Zend_Validate_NotEmpty::IS_EMPTY    => "Parent category is required."
    )));
    $element->setAttribs(array(
      'data' => Zend_Json::encode(array('validate'=>array(
        'required' => true,
        'messages' => array(
          'required' => 'Parent category is required.'
        )
      )))
    ));
    $element->setDecorators(array(
      array(array('tree'=>'HtmlTag'), array('tag' => 'div', 'class'=>'parent-tree','placement'=>'prepend')),
      'ViewHelper',
      array('Description', array('tag' => 'div', 'class'=>'form-description', 'placement'=>'append', 'escape'=>false)),
      'Errors',
      array(array('element'=>'HtmlTag'), array('tag' => 'div', 'class'=>'form-element')),
      array(array('label-container-open'=>'HtmlTag'), array('tag' => 'div', 'class'=>'form-label', 'placement'=>'prepend', 'closeOnly'=>true)),
      array('Label', array('placement'=>'prepend')),
      array(array('label-container-close'=>'HtmlTag'), array('tag' => 'div', 'class'=>'form-label', 'placement'=>'prepend', 'openOnly'=>true)),
      array(array('row'=>'HtmlTag'), array('tag' => 'div', 'class'=>'form-row'))
    ));
    $this->addElement($element);



    $element = new Zend_Form_Element_Checkbox('active');
    $element->setLabel('Active:');
    $element->setDescription("Only active categories appear on the front end.");
    $element->setFilters(array('Digits'));
    $element->setDecorators($this->inline);
    $element->setValue(1);
    $this->addElement($element);

    $element = new Zend_Form_Element_Submit('submit');
    $element->setLabel('Save Category');
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
  public function addDbValidators($route_id = null){
    $validator = new Validate_Doctrine_NoRecordExists(array(
      'table' => 'Route',
      'field' => 'path'
    ));
    if($route_id){
      $validator->setExclude(array('field'=>'id', 'value' => $route_id));
    }
    $validator->setMessages(array(
      Validate_Doctrine_RecordExists::ERROR_RECORD_FOUND => "Another page exists with this path."
    ));
    $this->getElement('path')->addValidator($validator, true);
    return $this;
  }
}