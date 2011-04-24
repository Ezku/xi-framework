<?php
/**
 * @category    Xi
 * @package     Xi_Validate
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 */
class Xi_Validate_Or extends Xi_Validate_Composite
{
    public function isValid($value)
    {
        foreach ($this->getValidators() as $validator) {
            if ($validator->isValid($value)) {
                return true;
            }
        }
        return false;
    }
}
