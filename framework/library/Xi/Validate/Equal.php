<?php
/**
 * @category    Xi
 * @package     Xi_Validate
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 */
class Xi_Validate_Equal extends Zend_Validate_Identical 
{
    /**
     * Defined by Zend_Validate_Interface
     *
     * @param  string $value
     * @return boolean
     */
    public function isValid($value)
    {
        $this->_setValue($value);
        $token = $this->getToken();
        
        if ($value != $token)  {
            $this->_error(self::NOT_SAME);
            return false;
        }

        return true;
    }
}
