<?php
/**
 * Validates a url
 *
 * @package    Simplecart
 * @subpackage Validate
 * @author     spekkionu
 * @license    New BSD http://www.opensource.org/licenses/bsd-license.php
 */
class Validate_Url extends Zend_Validate_Abstract {

  const INVALID = 'urlInvalid';

  protected $_messageTemplates = array(
    self::INVALID => "'%value%' is not a valid URL.",
  );

  /**
   * Validates the Url
   * @param string $value
   * @return bool
   */
  public function isValid($value) {
    $valueString = (string) $value;
    $this->_setValue($valueString);

    if (!Zend_Uri::check($value)) {
      $this->_error(self::INVALID);
      return false;
    }
    return true;
  }
}