<?php
class DebugTest extends PHPUnit_Framework_TestCase
{
    public function testDump(){
      ob_start();
      Debug::dump("thisismystring");
      $string = ob_get_clean();
      $this->assertInternalType('string', $string);
      $found = stristr($string, 'thisismystring');
      $this->assertTrue($found !== FALSE, "passed string not found");
    }

    public function testDumpWithLabel(){
      ob_start();
      Debug::dump("thisismystring", 'thisismylabel');
      $string = ob_get_clean();
      $this->assertInternalType('string', $string);
      $found = stristr($string, 'thisismylabel');
      $this->assertTrue($found !== FALSE, "passed label not found");
    }

    public function testZend(){
      ob_start();
      Debug::zend("var", 'Label');
      $string = ob_get_clean();
      $this->assertInternalType('string', $string);
    }

    public function testTrace(){
      ob_start();
      Debug::trace();
      $string = ob_get_clean();
      $this->assertInternalType('string', $string);
    }
}