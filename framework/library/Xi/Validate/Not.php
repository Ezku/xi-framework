<?php
/**
 * @category    Xi
 * @package     Xi_Validate
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 */
class Xi_Validate_Not extends Xi_Validate_Outer
{
    public function isValid($value)
    {
        return !parent::isValid($value);
    }
}