<?php

/**
 * Confirms a record exists in a table.
 *
 * @category   Zend
 * @package    Zend_Validate
 * @uses       Validate_Doctrine_Abstract
 */
class Validate_Doctrine_RecordExists extends Validate_Doctrine_Abstract
{
    public function isValid($value)
    {
        $valid = true;
        $this->_setValue($value);

        $result = $this->_query($value);
        if ($result === false) {
            $valid = false;
            $this->_error(self::ERROR_NO_RECORD_FOUND);
        }

        return $valid;
    }
}
