<?php

/**
 * User
 *
 * @package    Simplecart
 * @subpackage Model
 * @author     spekkionu
 * @license New BSD http://www.opensource.org/licenses/bsd-license.php
 */
class User extends Base_User
{

  public function setPassword($password) {
    return $this->_set('password', self::encryptPassword($password));
  }

  public static function encryptPassword($password) {
    $crypt_key = 'fdhjhkHGFJUyjnmmukRGRGhyjktyyreHGFJrthrGJHTYJgfjhewTJH';
    return hash('sha512', $password . $crypt_key);
  }

}