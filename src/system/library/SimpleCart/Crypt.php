<?php

/**
 * Encryption Class
 *
 * @package    Simplecart
 * @subpackage Model
 * @author     spekkionu
 * @license New BSD http://www.opensource.org/licenses/bsd-license.php
 */
class SimpleCart_Crypt {

	/**
	 * Encryption key
	 * @var string $_key
	 */
	private $_key;

	/**
   * Encryption algorithm, defaults to MCRYPT_BLOWFISH
   * @var string $_algorithm
   */
	private $_algorithm;

	/**
	 * Class constructor, sets encryption key and algorithm
	 * @param string $key The Encryption key to use.
	 * @param string $algorithm The encryption algorithm.  Defaults to MCRYPT_BLOWFISH.  A good algorithm is MCRYPT_RIJNDAEL_256
	 */
	public function __construct($key, $algorithm = MCRYPT_BLOWFISH){
		// Make sure mcrypt library is available.
		if (!extension_loaded('mcrypt')) throw new Extension('MCRYPT extension is not loaded.');
		$this->setAlgorithm($algorithm);
		$this->setKey($key);
	}

	/**
	 * Sets the encryption key.
	 * @param $key
	 * @return Crypt
	 */
	public function setKey($key){
		$this->_key = $key;
		return $this;
	}

	/**
	 * Returns the correct size key for the algorithm
	 * @return string
	 */
	private function _getKey(){
		$key_size = mcrypt_get_key_size($this->_algorithm, MCRYPT_MODE_ECB);
    $key = str_pad($this->_key, $key_size, '0');
    $key = substr($key, 0, $key_size);
    return $key;
	}

	/**
	 * Generates a random IV
	 * @return string
	 */
	private function _generateIV(){
		// Generate an IV of the correct size.
    $iv_size = mcrypt_get_iv_size($this->_algorithm, MCRYPT_MODE_ECB);
    return mcrypt_create_iv($iv_size, MCRYPT_RAND);
	}

	/**
	 *
	 * @param $algorithm
	 * @return Crypt
	 */
	public function setAlgorithm($algorithm){
	  // Make sure algorythm is available
    if(!in_array($algorithm, mcrypt_list_algorithms())) throw new Exception("MCRYPT Algorithm {$algorithm} is not available.");
    $this->_algorithm = $algorithm;
    return $this;
	}

	/**
	 * Encrypts data
	 * @param mixed $data
	 * @return string
	 */
	public function encrypt($data){
		// Serialize the data into a string
		$data = serialize($data);
    // Encrypt the data
    $data = mcrypt_encrypt($this->_algorithm,  $this->_getKey(), $data, MCRYPT_MODE_ECB,  $this->_generateIV());
    // Encode and trim it
    return trim(base64_encode($data));
	}

	/**
	 * Decrypts data
	 * @param string $data
	 * @return string
	 */
	public function decrypt($data){
		// Decode the data
		$data = base64_decode(trim($data));
    // Decrypt the data
    $data = mcrypt_decrypt($this->_algorithm, $this->_getKey(), $data, MCRYPT_MODE_ECB, $this->_generateIV());
    // Unserialize the data
    return unserialize(trim($data));
	}
}