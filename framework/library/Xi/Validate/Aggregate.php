<?php
/**
 * Describes an object that can provide a Zend_Validate_Interface instance
 * 
 * @category    Xi
 * @package     Xi_Validate
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @link        http://www.xi-framework.com
 */
interface Xi_Validate_Aggregate
{
    /**
     * Retrieve aggregate validator
     *
     * @return Zend_Validate_Interface
     */
    public function getValidator();
}
