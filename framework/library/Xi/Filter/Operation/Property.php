<?php
/**
 * Retrieves the value from a property on an object
 *
 * @category    Xi
 * @package     Xi_Filter
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_Filter_Operation_Property extends Xi_Filter_Operation_Abstract
{
    public function filter($value)
    {
        $property = $this->getArgument();
        if (!isset($value->{$property})) {
            return $this->_default;
        }

        return $value->{$property};
    }
}

