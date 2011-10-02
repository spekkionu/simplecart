<?php

class SimpleCart_StatesTest extends PHPUnit_Framework_TestCase
{

  protected function setUp() {
    SimpleCart_States::setXML();
    SimpleCart_States::clearCache();
  }

  public function testGetArray() {
    $states = SimpleCart_States::getArray();
    $this->assertInternalType('array', $states);
    $this->assertArrayHasKey('CA', $states);
  }

  public function testGetPairs() {
    $states = SimpleCart_States::getPairs();
    $this->assertInternalType('array', $states);
    $this->assertArrayHasKey('CA', $states);
  }

  public function testGetState() {
    $state = SimpleCart_States::getState('CA', true);
    $this->assertEquals('California', $state);
  }

  public function testNoCountry(){
    $state = SimpleCart_States::getState('FAKE', true);
    $this->assertFalse($state);
  }

  public function testSetXml(){
    SimpleCart_States::setXML(DATADIR.'/etc/states.xml');
    $states = SimpleCart_States::getPairs();
    $this->assertSame(array('CA'=>'California'), $states);
  }

  public function testIsCached(){
    SimpleCart_States::clearCache();
    $this->assertFalse(SimpleCart_States::isCached());
    SimpleCart_States::getArray();
    $this->assertTrue(SimpleCart_States::isCached());
  }

  public function testClearCache(){
    SimpleCart_States::getArray();
    $this->assertTrue(SimpleCart_States::isCached());
    SimpleCart_States::clearCache();
    $this->assertFalse(SimpleCart_States::isCached());
  }

}