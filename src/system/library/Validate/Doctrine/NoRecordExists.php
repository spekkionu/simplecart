<?php

/**
 * Confirms a record does not exist in a table.
 *
 * @package    Simplecart
 * @subpackage Validate
 * @author     spekkionu
 * @license    New BSD http://www.opensource.org/licenses/bsd-license.php
 */
class Validate_Doctrine_NoRecordExists extends Validate_Doctrine_Abstract
{
    public function isValid($value)
    {
        $valid = true;
        $this->_setValue($value);

        $result = $this->_query($value);
        if ($result !== false) {
            $valid = false;
            $this->_error(self::ERROR_RECORD_FOUND);
        }

        return $valid;
    }
}
