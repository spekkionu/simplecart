<?php

/**
 * Table_User
 *
 * This class has been auto-generated by the Doctrine ORM Framework
 *
 * @package    Simplecart
 * @subpackage Model
 * @author     spekkionu
 * @license New BSD http://www.opensource.org/licenses/bsd-license.php
 */
class Table_User extends Doctrine_Table
{

  /**
   * Returns an instance of this class.
   *
   * @return object Table_User
   */
  public static function getInstance() {
    return Doctrine_Core::getTable('User');
  }

}