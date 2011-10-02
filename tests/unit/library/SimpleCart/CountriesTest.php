<?php

class SimpleCart_CountriesTest extends PHPUnit_Framework_TestCase
{

  protected function setUp() {
    SimpleCart_Countries::setXML();
  }

  public function testGetArray() {
    $countries = SimpleCart_Countries::getArray();
    $this->assertInternalType('array', $countries);
    $this->assertArrayHasKey('US', $countries);
  }

  public function testGetPairs() {
    $countries = SimpleCart_Countries::getPairs();
    $this->assertInternalType('array', $countries);
    $this->assertArrayHasKey('US', $countries);
  }

  public function testGetCountry() {
    $country = SimpleCart_Countries::getCountry('US', true);
    $this->assertEquals('United States', $country);
  }

  public function testSetXml() {
    SimpleCart_Countries::setXML(DATADIR . '/etc/countries.xml');
    $countries = SimpleCart_Countries::getPairs();
    $this->assertSame(array('US' => 'United States'), $countries);
  }

  public function testNoCountry(){
    $country = SimpleCart_Countries::getCountry('FAKE', true);
    $this->assertFalse($country);
  }

  public function testIsCached(){
    SimpleCart_Countries::clearCache();
    $this->assertFalse(SimpleCart_Countries::isCached());
    SimpleCart_Countries::getArray();
    $this->assertTrue(SimpleCart_Countries::isCached());
  }

  public function testClearCache(){
    SimpleCart_Countries::getArray();
    $this->assertTrue(SimpleCart_Countries::isCached());
    SimpleCart_Countries::clearCache();
    $this->assertFalse(SimpleCart_Countries::isCached());
  }

}