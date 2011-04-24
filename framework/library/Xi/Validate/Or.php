<?php
/**
 * @category    Xi
 * @package     Xi_Validate
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
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
