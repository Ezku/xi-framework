<?php
/**
 * Constrains contents to instances of a certain type or types using {@link
 * Xi_Validate_Instanceof}.
 *
 * @category    Xi
 * @package     Xi_Array
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @link        http://www.xi-framework.com
 */
class Xi_Array_Validate_Type extends Xi_Array_Validate
{
    /**
     * @param array data
     * @param array valid types
     * @return void
     */
    public function __construct($data = array(), $types = array())
    {
        parent::__construct($data, new Xi_Validate_Instanceof($types));
    }
}
