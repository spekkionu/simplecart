<?php
class SimpleCart_CryptTest extends PHPUnit_Framework_TestCase
{
    /**
	 * The encryption key
	 * @var string
	 */
	const KEY = 'fdJTHvhtgyfjcfdbgfdJUGFbfdsgGFDjuhNMfdgFDhgfnvfnhgKhgfHdsvbFDuHGmVCbFDShghgfjnm';

	const DATA = 'This is the test string to be encrypted.';

  /**
   * Tests Crypt->__construct()
   */
  public function test__construct(){
  	$crypt = new SimpleCart_Crypt(self::KEY);
  	$this->assertInstanceOf('SimpleCart_Crypt',$crypt, 'Testing class constructor');
    unset($crypt);
  }

  /**
   * Tests Crypt->encrypt()
   */
  public function testEncrypt(){
    // Encrypt Data
    $crypt = new SimpleCart_Crypt(self::KEY);
  	$encrypted = $crypt->encrypt(self::DATA);
    $this->assertTrue($encrypted != self::DATA, 'Testing data encryption');

    unset($crypt, $encrypted);
  }

  /**
   * Tests Crypt->decrypt()
   */
  public function testDecrypt(){
  	// Encrypt the data
    $crypt = new SimpleCart_Crypt(self::KEY);
    $encrypted = $crypt->encrypt(self::DATA);
    // Decrypt the data
    $decrypted = $crypt->decrypt($encrypted);
    $this->assertEquals($decrypted, self::DATA, 'Testing data decryption');

    unset($crypt, $encrypted, $decrypted);
  }
}