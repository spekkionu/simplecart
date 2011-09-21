<?php

/**
 * Debugging functions
 *
 * @package    Simplecart
 * @author     spekkionu
 * @license New BSD http://www.opensource.org/licenses/bsd-license.php
 */
class Debug
{

  /**
   * Dumps data as html
   * @param mixed $data
   * @param string $label
   * @return void
   */
  public static function dump($var, $label=null) {
    if ($label) {
      Kint::dump($label, $var);
    } else {
      Kint::dump($var);
    }
  }

  /**
   * Dumps data through Zend_Debug
   * @param mixed $var
   * @param string $label
   * @param boolean $echo
   * @return string
   */
  public static function zend($var, $label=null, $echo = TRUE) {
    return Zend_Debug::dump($var, $label, $echo);
  }

  /**
   * Dump a trace
   * @return void
   */
  public static function trace() {
    Kint::trace();
  }

}