<?php
/**
 * Retrieves the value from a method call on an object
 *
 * @category    Xi
 * @package     Xi_Filter
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_Filter_Operation_Method extends Xi_Filter_Operation_Abstract
{
    public function filter($value)
    {
        $method = $this->getArgument();
        if (is_object($value) && !method_exists($value, $method)) {
            return $this->_default;
        }

        return $value->{$method}();
    }
}

