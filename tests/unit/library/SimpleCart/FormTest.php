<?php
class SimpleCart_FormTest extends PHPUnit_Framework_TestCase
{

  public function test__construct(){
  	$form = new SimpleCart_Form();
  	$this->assertInstanceOf('SimpleCart_Form',$form, 'Testing class constructor');
    unset($form);
  }

  public function testIsValid(){
    $form = new SimpleCart_Form();
    $form->addElement('text', 'name');
    $form->getElement('name')->setRequired(true);
    $this->assertFalse($form->isValid(array()));
    $this->assertTrue($form->isValid(array('name'=>'this is not empty')));
  }


  public function testGetJsonErrors(){
    $form = new SimpleCart_Form();
    $form->addElement('text', 'name');
    $form->getElement('name')->setRequired(true);
    $form->isValid(array());
    $errors = $form->getJsonErrors();
    $this->assertInternalType('array', $errors);
    $this->assertArrayHasKey('name', $errors);
  }

  public function testMultipleErrors(){
    $form = new SimpleCart_Form();
    $form->addElement('text', 'name');
    $form->getElement('name')->setAttrib('class', 'form-element');
    $form->getElement('name')->addValidator('Digits', false);
    $form->getElement('name')->addValidator('Alnum', false);
    $form->isValid(array('name'=>'Invalid Value !@#$'));
    $errors = $form->getJsonErrors(true);
    $this->assertInternalType('array', $errors);
    $this->assertArrayHasKey('name', $errors);
    $this->assertInternalType('array', $errors['name']);
    //$this->assertRegExp("/\\n/i", $errors['name']);
  }


}