<?php
/**
 * @category    Xi
 * @package     Xi_Validate
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 */
class Xi_Validate_Filter_Method extends Xi_Validate_Filter
{
    public function __construct($validator, $method, $default = null)
    {
        parent::__construct($validator, new Xi_Filter_Operation_Method($method, $default));
    }
}
