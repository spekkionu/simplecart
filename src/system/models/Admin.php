<?php

/**
 * Admin
 *
 * @package    Simplecart
 * @subpackage Model
 * @author     spekkionu
 * @license New BSD http://www.opensource.org/licenses/bsd-license.php
 */
class Admin extends Base_Admin
{

  /**
   * Encrypts and sets account password
   * @param  string $password
   * @return Admin
   */
  public function setPassword($password) {
    $this->_set('password', self::encryptPassword($password));
    return $this;
  }

  /**
   * Encrypts and sets PIN number
   * @param  string $pin
   * @return Admin
   */
  public function setPasswordKey($pin) {
    $this->_set('password_key', self::encryptPin($pin));
    return $this;
  }

  /**
   * Encrypts and sets token
   * @param  string $token
   * @return Admin
   */
  public function setToken($token) {
    $this->_set('token', self::encryptToken($token));
    return $this;
  }

  /**
   * Encrypts password
   * @param  string $password
   * @return string
   */
  public static function encryptPassword($password) {
    $crypt_key = 'gitybTYJgfdbRTYHFDBhgjkrfdgRTYUJGFDMrtgrtujhgmrtGRTJU';
    return self::encrypt($password, $crypt_key);
  }

  /**
   * Encrypts token
   * @param  string $token
   * @return string
   */
  public static function encryptToken($token) {
    $crypt_key = 'fdGGFHNhjfgRTYNBfdgrehfBGRGJfdghJHFDBjJTYJd';
    return self::encrypt($token, $crypt_key);
  }

  /**
   * Encrypts PIN
   * @param  string $pin
   * @return string
   */
  public static function encryptPin($pin) {
    $crypt_key = 'fhgrgfhFHGrtgfdHRTEGGFDJHRTHfdnhtyHREGh';
    return self::encrypt($pin, $crypt_key);
  }

  /**
   * Encrypts value
   * @param  string $value
   * @param  string $crypt_key
   * @return string
   */
  private static function encrypt($value, $crypt_key) {
    return hash('sha512', $value . $crypt_key);
  }

}
