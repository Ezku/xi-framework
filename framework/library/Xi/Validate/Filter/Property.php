<?php
/**
 * @category    Xi
 * @package     Xi_Validate
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @link        http://www.xi-framework.com
 */
class Xi_Validate_Filter_Property extends Xi_Validate_Filter
{
    public function __construct($validator, $property, $default = null)
    {
        parent::__construct($validator, new Xi_Filter_Operation_Property($property, $default));
    }
}
