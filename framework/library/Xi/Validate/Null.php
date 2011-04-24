<?php
/**
 * A null validator
 * 
 * @category    Xi
 * @package     Xi_Validate
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_Validate_Null extends Xi_Validate_Abstract
{
    /**
     * Validate nothing: always indicate a valid value
     *
     * @param mixed $value
     * @return true
     */
    public function isValid($value)
    {
        return true;
    }
}
