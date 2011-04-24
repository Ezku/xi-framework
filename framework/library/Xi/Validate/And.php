<?php
/**
 * @category    Xi
 * @package     Xi_Validate
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @link        http://www.xi-framework.com
 */
class Xi_Validate_And extends Xi_Validate_Composite
{
    public function isValid($value)
    {
        $valid = true;
        foreach ($this->_validators as $validator) {
            $valid = $validator->isValid($value) && $valid;
        }
        return $valid;
    }
}
