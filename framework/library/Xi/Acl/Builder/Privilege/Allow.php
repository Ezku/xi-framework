<?php
/**
 * @category    Xi
 * @package     Xi_Acl
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 */
class Xi_Acl_Builder_Privilege_Allow extends Xi_Acl_Builder_Privilege_Abstract
{
    /**
     * @var Xi_Acl_Builder_Privilege_Operation_Interface
     */
    protected static $_operation;
    
    /**
     * @return Xi_Acl_Builder_Privilege_Operation_Interface
     */
    public function getOperation()
    {
        if (null === self::$_operation) {
            self::$_operation = new Xi_Acl_Builder_Privilege_Operation_Allow;
        }
        return self::$_operation;
    }
}
